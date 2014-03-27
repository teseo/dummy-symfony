<?php

namespace Projects\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Projects\PersonalWebsiteBundle\Entity\users;
use Projects\PersonalWebsiteBundle\Modals\login;


class HomeAdminController extends Controller
{
	const REMEMBER_ME_VALUE = 1;

    public function indexAction(Request $request)
    {
		$session = $request->getSession();
		$entityManager = $this->getDoctrine()->getManager();
		$repository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:users');
		if($request->getMethod() == 'POST'){
			$name = $request->get('name');
			$password = md5($request->get('password'));
			$rememberMe = $request->get('remember-me');

			/** @var $user users */
			$user = $repository->findOneBy(array('name' => $name,'password' => $password));
			if ($user){
				if($rememberMe == self::REMEMBER_ME_VALUE){
					$login = new login();
					$login->setPassword($password);
					$login->setName($name);
					$session->set('login', $login);
				}
				return $this->render('ProjectsPersonalWebsiteBundle:admin:home/loggedin.html.twig', array('name' => $user->getName()));
			} else {
				return $this->render('ProjectsPersonalWebsiteBundle:admin:home/login.html.twig', array('name' => 'Error'));
			}
		} else if($session->has('login')){
			$login = $session->get('login');
			$name = $login->getName();
			$password = $login->getPassword();
			/** @var $user users */
			$user = $repository->findOneBy(array('name' => $name,'password' => $password));
			if ($user){
				return $this->render('ProjectsPersonalWebsiteBundle:admin:home/loggedin.html.twig', array('name' => $user->getName()));
			}

		} else {
			return $this->render('ProjectsPersonalWebsiteBundle:admin:home/login.html.twig');
		}
    }

	public function logoutAction(Request $request){
		$session = $request->getSession();
		$session->clear();
		return $this->render('ProjectsPersonalWebsiteBundle:admin:home/login.html.twig');

	}

	public function cacheAction(){
		$memcache = new \Memcache();
		$memcache->connect('localhost', 11211);

		$cacheDriver = new \Doctrine\Common\Cache\MemcacheCache();
		$cacheDriver->setMemcache($memcache);
		$key = 'test';
		if( $user = $cacheDriver->fetch( $key ) ) {
		} else {
			$entityManager = $this->getDoctrine()->getManager();
			$repository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:users');
			$user = $repository->findOneBy(array('name' => 'javi'));
			$cacheDriver->save( $key, $user, 3600 );
		}
		return $this->render('ProjectsPersonalWebsiteBundle:admin:home/cache.html.twig', array('user' => $user) );

	}
}
