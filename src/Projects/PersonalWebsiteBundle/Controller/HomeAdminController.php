<?php

namespace Projects\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeAdminController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ProjectsPersonalWebsiteBundle:admin:home/index.html.twig', array('name' => $name));
    }

	public function loginAction()
	{
		$request = $this->getCurrentRequest();
		$session = $request->getSession();

		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR
			);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}

		return $this->render(
			'AcmeSecurityBundle:Security:login.html.twig',
			array(
				// last username entered by the user
				'last_username' => $session->get(SecurityContext::LAST_USERNAME),
				'error'         => $error,
			)
		);
	}

	public function dumpStringAction()
	{
		return $this->render('SimpleProfileBundle:Security:dumpString.html.twig', array());
	}
}
