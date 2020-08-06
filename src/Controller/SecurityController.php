<?php

namespace App\Controller;

use App\Entity\Bill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use App\Entity\User;

class SecurityController extends Controller
{
  /**
   * @Route("/login", name="login", methods={"POST"})
   */
  public function login(Request $request)
  {
      $user = $this->getUser();

      // return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
      return $this->json([
        'id' => $user->getId(), 'username' => $user->getUsername(), 'name' =>$user->getName(), 'lastname' =>$user->getLastname(),
        'roles' => $user->getRoles(),
      ]);
  }

  /**
   * List all users
   * @Rest\Get("/users")
   * @Rest\QueryParam(name="today",description="Toutes les ventes du jour actuel",default="")
   * @Rest\QueryParam(name="getBills",description="Get All Bills",default="")
   *
   * @return Response
   */
    public function getUsersAction(ParamFetcher $paramFetcher)
    {
      $users = null; $today = $paramFetcher->get('today'); $getBills = $paramFetcher->get('today');
      if($today != "" && $getBills){
        $users = $this->getDoctrine()->getRepository(User::class)->findBillByDate($today);
      } else {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
      }
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($users);
      $view->getContext()->setGroups(array(
        'users'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    }

  /**
   * List all users
   * @Rest\Get("/user/{id}")
   *
   * @return Response
   */
  public function getUserAction(Request $request)
  {
    $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
    $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth(date('Y-m-d'));
    $total = 0;
    foreach($bills as $bill){
      if($bill->getOperator() == $user){
        if($bill->getTypePaiement()->getId() == 2){
          $total += $bill->getAccompte();
        } else {
          $total += $bill->getNet();
        }
      }
    }
    $user->setTotalSellMonth($total);
    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $view->setData($user);
    $view->getContext()->setGroups(array(
      'user'
    ));
    $view->setHeader('Access-Control-Allow-Origin', '*');
    return $handler->handle($view);
  }

    // /**
    //  * @Route("/login", name="app_login")
    //  */
    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     // if ($this->getUser()) {
    //     //     return $this->redirectToRoute('target_path');
    //     // }
    //
    //     // get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     // last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();
    //
    //     // return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    //     return $this->json([
    //       'last_username' => $lastUsername, 'error' => $error
    //     ]);
    // }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
