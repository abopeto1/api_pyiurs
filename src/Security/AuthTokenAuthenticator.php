<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\HttpUtils;

class AuthTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * Lifetime token
     */
    const TOKEN_VALIDITY_DURATION = 12 * 3600;

    private $httpUtils;

    public function __construct(HttpUtils $httpUtils)
    {
        $this->httpUtils = $httpUtils;
    }

    public function createToken(Request $request, $providerKey)
    {
        $targetUrl = '/auth-tokens';
        if($request->getMethod() === "POST" && $this->httpUtils->checkRequestPath($request, $targetUrl)){
            return;
        }

        $authTokenHeader = $request->headers->get('X-Auth-Token');

        if(!$authTokenHeader){
            throw new BadCredentialsException('X-Auth-Token header is required');
        }

        return new PreAuthenticatedToken(
            'anon.', $authTokenHeader, $providerKey
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if(!$userProvider instanceof AuthTokenUserProvider) {
            throw new \Zend\Code\Exception\InvalidArgumentException(
                sprintf(
                    'The user provider must be an insance of AuthTokenUserProvider (%s was given)',
                    get_class($userProvider)
                )
            );
        }

        $authTokenHeader = $token->getCredentials();
        $authToken = $userProvider->getAuthToken($authTokenHeader);

        if(!$authToken || !$this->isTokenValid($authToken)){
            throw new BadCredentialsException('Invalid Authentication Token');
        }

        $user = $authToken->getUser();
        $pre = new PreAuthenticatedToken(
            $user, $authTokenHeader, $providerKey, $user->getRoles()
        );

        $pre->setAuthenticated(true);

        return $pre;
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    private function isTokenValid($authToken)
    {
        return (time() - $authToken->getCreated()->getTimeStamp()) < self::TOKEN_VALIDITY_DURATION;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw $exception;
    }
}