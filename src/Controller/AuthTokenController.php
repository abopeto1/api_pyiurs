<?php

namespace App\Controller;

use App\Entity\AuthToken;
use App\Entity\Credentials;
use App\Entity\User;
use App\Entity\Taux;
use App\Form\CredentialsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthTokenController extends AbstractController
{
    /**
     * @Rest\Post("/auth-tokens")
     */
    public function postAuthTokensAction(Request $request, UserPasswordEncoderInterface $encoder, ViewHandlerInterface $handler)
    {
        $view = View::create();
        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);

        $form->submit($request->request->all());

        if(!$form->isValid()){
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByLogin($credentials->getLogin());

        if(!$user){ // User doesn't exist
            return $this->invalidCredentials();
        }

        // $encoder = $this->get('security.user_password_encoder.generic');
        $isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());

        if(!$isPasswordValid){ // Wrong Password
            return $this->invalidCredentials();
        }

        $authToken = new AuthToken();
        $authToken
            ->setValue(base64_encode(random_bytes(50)))
            ->setCreated(new \DateTime("Africa/Kinshasa"))
            ->setUser($user);
        
        $em->persist($authToken);
        $em->flush();

        $taux = $this->getDoctrine()->getRepository(Taux::class)->find(1);

        $authToken->taux = $taux;
        $view->setData($authToken);
        $view->getContext()->setGroups(array(
            'auth_tokens'
        ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    private function invalidCredentials(){
        return \FOS\RestBundle\View\View::create(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }
}
