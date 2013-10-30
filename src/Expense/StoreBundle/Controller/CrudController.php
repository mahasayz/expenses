<?php

namespace Expense\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Expense\StoreBundle\Entity\User;
use Expense\StoreBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Expense\StoreBundle\Entity\Expense;

class CrudController extends Controller{
	/**
	 * @Route("/createRole", name="crud_create_role")
	 */
	public function createRoleAction(){
		$this->setup();
	
		$role = new Role();
		$role->setName("Role for customer.. show owner");
		$role->setRole("CUSTOMER");
		$this->em->persist($role);
	
		$this->em->flush();
	}
	
	/**
	 * @Route("/createUser", name="crud_create_user")
	 */
	public function createUserAction(){
		$this->setup();
	
		$user = new User();
		$user->setFirstName("Alim");
		
		/////password encoding//
		$user->setSalt("a");
		$factory = $this->get("security.encoder_factory");
		$encoder = $factory->getEncoder($user);
		$password = $encoder->encodePassword('alim', $user->getSalt());
		$user->setPassword($password);
		///password encoding ends//
		
		$user->setUsername("alim");
	
		$role = $this->em->getRepository("ExpenseStoreBundle:Role")->findOneByRole("CUSTOMER");
		$user->addRole($role);
		$this->em->persist($user);
	
		$this->em->flush();
	
		return new Response("Done");
	}
	
	/**
	 * @Route("/getTotalExpense", name="crud_total_expense")
	 */
	public function checkTotalExpenseAction(){
		$this->setup();
		
	 	$user = $this->em->getRepository("ExpenseStoreBundle:User")->findOneByUsername("maha");
		$to = new \DateTime();
		$from = clone $to;
		$from = $from->sub(\DateInterval::createFromDateString("365 days"));  
	 	
	 	
		var_dump($this->em->getRepository("ExpenseStoreBundle:Expense")->getTotalSpendingOf($user, $from, $to ) );
		
	}
	
	
	private function setup(){
		$this->log = $this->get('logger');
		$this->log->info("setup--------------\n\n");
		$this->em  = $this->getDoctrine()->getManager();
	}
}

?>