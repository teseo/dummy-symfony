<?php

namespace Projects\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Projects\PersonalWebsiteBundle\Entity\users;
use Projects\PersonalWebsiteBundle\Entity\category;
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
	 * Display category information to be edited
	 *
	 * @author Javier Mellado <sol@javiermellado.com>
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @throws Exception
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editCategoryAction(Request $request){
		$session = $request->getSession();

		$entityManager = $this->getDoctrine()->getManager();
		/** @var $categoryRepository \Projects\PersonalWebsiteBundle\Entity\categoryRepository */
		$categoryRepository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:category');
		$categoryId = $request->get('id');

		$category = $categoryRepository->findOneBy(array ('id' => $categoryId));
		$categories = $categoryRepository->findAll();
		if (!$category){

			throw new Exception ('Category not found');
		}
		$categoryEntity = new category();
		$form = $this->createFormBuilder($categoryEntity)
			->add('category', 'text')
			->add('save', 'submit')
			->getForm();

		$data = array(
			'edit' => true,
			'category' => $category,
			'list' => $categories,
			'form' => $form->createView(),
			'section' => 'categories');
		$template = 'ProjectsPersonalWebsiteBundle:admin:home/content.html.twig';

		return $this->prepare($session, $template, $data);


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
	 * PRIVATE METHODS
	 */

	/**
	 *
	 * Verifies if session is valid
	 * redirects to the desired template on success
	 * redirects to login page if not success
	 *
	 * @author Javier Mellado <sol@javiermellado.com>
	 * @param \Symfony\Component\HttpFoundation\Se