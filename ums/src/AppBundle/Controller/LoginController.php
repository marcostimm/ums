<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends Controller
{


	/**
	* @Route("/login", name="login")
	**/
	public function loginAction(Request $request, AuthenticationUtils $authenticationsutils)
	{
		$errors = $authenticationsutils->getLastAuthenticationError();

		$lastUserName = $authenticationsutils->getLastUsername();

		return $this->render('AppBundle:Login:login.html.twig', array(
			'errors'    => $errors,
			'username'  => $lastUserName,

		));

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
