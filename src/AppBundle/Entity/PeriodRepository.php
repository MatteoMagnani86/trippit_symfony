<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PeriodRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PeriodRepository extends \Doctrine\ORM\EntityRepository
{	
	public function findAll()
    {
		return $this->findBy(array(), array('description' => 'ASC'));		
    }
}