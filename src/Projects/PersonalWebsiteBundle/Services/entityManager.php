<?php
namespace Projects\PersonalWebsiteBundle\Services;
class entityManager
{
	private $em;

	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}
}
