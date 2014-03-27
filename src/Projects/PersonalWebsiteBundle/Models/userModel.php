<?php
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Projects\PersonalWebsiteBundle\Entity\users;

class userModel
{
	protected static $instance = null;

	protected function __construct()
	{
	}

	protected function __clone()
	{
	}

	public static function getInstance()
	{
		if (!isset(static::$instance)) {
			static::$instance = new static;
		}
		return static::$instance;
	}

	public function getUser($userData){
		$entityManager = $this->get('doctrine.orm.entity_manager');
		$repository = $entityManager->getRepository('ProjectsPersonalWebsiteBundle:users');
		return $repository->findOneBy($userData);
	}

	public function removeUser(){}

	public function updateUser(){}

	public function createUser(){}

}
