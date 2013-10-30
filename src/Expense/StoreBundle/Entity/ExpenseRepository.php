<?php

namespace Expense\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Expense\SiteBundle\ExpenseSiteBundle;
class ExpenseRepository extends EntityRepository{
	public function getTotalSpendingOf($user, $from, $to){
		 return $this->getEntityManager()->createQuery(
				"select sum(e.amount) from ExpenseStoreBundle:Expense e join e.user u where u.id=?1 " .
				" and e.created between ?2 and ?3"
				)
				->setParameter(1, $user->getId())
				->setParameter(2, $from)
				->setParameter(3, $to)
				->getSingleScalarResult(); 
		
	}
}

?>