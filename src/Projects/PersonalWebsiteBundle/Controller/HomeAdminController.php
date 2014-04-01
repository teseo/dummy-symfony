<?php

namespace Projects\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Projects\PersonalWebsiteBundle\Entity\users;
use Projects\PersonalWebsiteBundle\Modals\login;

/**
 * Admin Home Controller
 */
class HomeAdminController extends Controller
{
	const REMEMBER_ME_VALUE = 1;

	const LOGIN_TEMPLATE = 'ProjectsPersonalWebsiteBundle:admin:home/login.html.twig';

	const SECTION_CATEGORIES = 'categoeries';
	const SECTION_CONTENT = 'content';

	/**
	 * Index page.
	 *
	 * If loggedin -> categories manager shown
	 * if !loggdin -> login page shown
	 *
	 * @author Javier Mellado <sol@javiermellado.com>
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws Exception
	 */
	public function indexAction(Request $request) {
		$session = $request->getSession();

		if($request->getMethod() == 'POST'){

			//User sends login request
			$entityManager = $this->getDoctrine()->getManager();
			$userRepository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:user');
			$name = $request->get('name');
			$password = md5($request->get('password'));
			$rememberMe = $request->get('remember-me');

			/** @var $user user */
			$user = $userRepository->findOneBy(array('name' => $name,'password' => $password));

			if ($user){
				if($rememberMe == self::REMEMBER_ME_VALUE){
					$login = new login();
					$login->setName($name);
					$session->set('login', $login);
				}
				$data = array(
						'name' => $user->getName(),
						'section' => 'categories'
						);
				return $this->forward('ProjectsPersonalWebsiteBundle:HomeAdmin:displaySection',$data);
			} else {
				return $this->render(self::LOGIN_TEMPLATE);
			}
		} else {
			$data = array(
				'section' => 'categories'
			);
			return $this->forward('ProjectsPersonalWebsiteBundle:HomeAdmin:displaySection',$data);
		}
    }

	/**
	 * Displays selected section
	 *
	 * @author Javier Mellado <sol@javiermellado.com>s
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws Exception
	 */
	public function displaySectionAction(Request $request){
		$section = $request->get('section');
		$session = $request->getSession();

		$data = array(
			'section' => $section
		);
		$template = 'ProjectsPersonalWebsiteBundle:admin:home/content.html.twig';

		switch($section){
			case self::SECTION_CONTENT:
				$data['section'] = 'content';
				$data['list'] = $this->getContentSectionData();
			break;
			default:
				$data['list'] = $this->getCategoriesSectionData();
		}
		return $this->prepare($session, $template, $data);

	}

	/**
	 * Display all categories content editor
	 */
	private function getCategoriesSectionData(){
		//User sends login request
		$entityManager = $this->getDoctrine()->getManager();
		/** @var $categoryRepository \Projects\PersonalWebsiteBundle\Entity\categoryRepository */

		$categoryRepository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:category');
		if($categoryRepository->getTotalRowCount() > 0){
			return $categoryRepository->findAll();
		} else{
			return array();
		}

	}
	/**
	 * Display all categories content editor
	 */
	private function getContentSectionData(){
		//User sends login request
		$entityManager = $this->getDoctrine()->getManager();
		$userRepository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:user');
	}
	/**
	 * Log out and return to login page
	 *
	 * @author Javier Mellado <sol@javiermellado.com>
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function logoutAction(Request $request){
		$session = $request->getSession();
		$session->clear();
		return $this->render(self::LOGIN_TEMPLATE);
	}



	/**
	 *
	 * Verifies if session is valid
	 * redirects to the desired template on success
	 * redirects to login page if not success
	 *
	 * @author Javier Mellado <sol@javiermellado.com>
	 * @param \Symfony\Component\HttpFoundation\Session\Session $session
	 * @param string $successTemplate
	 * @param array $data
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	private function prepare(\Symfony\Component\HttpFoundation\Session\Session $session, $successTemplate, $data = NULL){
		if($session->has('login')){

			//User already has a login session
			$loginSessionData = $session->get('login');
			$data['_loggedInUserName'] = $loginSessionData->getName();

			return $this->render($successTemplate, $data);
		} else {
			//admin login page
			return $this->render(self::LOGIN_TEMPLATE);
		}
	}
}
