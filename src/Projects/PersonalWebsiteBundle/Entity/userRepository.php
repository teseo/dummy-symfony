<?php

namespace Projects\PersonalWebsiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Projects\PersonalWebsiteBundle\Entity\users;


/**
 * userRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class userRepository extends EntityRepository
{
	public function findAllOrderedByName() {
		return $this->getEntityManager()->createQuery(
			'SELECT p FROM ProjectsPersonalWebsiteBundle:user p ORDER BY p.name ASC'
		)->getResult();
	}
}