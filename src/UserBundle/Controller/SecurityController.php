<?php

namespace UserBundle\Controller;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends BaseController{

	public function loginAction(\Symfony\Component\HttpFoundation\Request $request){
        $session = $this->container->get('session'); 
        // $session->clear();
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        $cartAmount = $session->get('cartAmount');

        //$response = parent::loginAction($request);

        //do something else;
        $session = $this->container->get('session'); 
        // $session->clear();
        $session->set('logged', 'false');

        if(!empty($this->getUser())){
            $session->set('logged', 'true');
            if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
                return $this->redirectToRoute('backoffice_arrivage_new');
            }
            else{
                return $this->redirectToRoute('cart_after_login');
            }
        }



        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));


        return $response;
    }

}