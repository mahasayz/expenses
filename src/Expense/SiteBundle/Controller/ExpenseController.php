<?php

namespace Expense\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Expense\StoreBundle\Entity\Expense;
use Expense\StoreBundle\Utils;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class ExpenseController extends Controller{
	
	/**
	 * @Route("/homepage/{ePage}", name="expense_homepage")
	 */
	public function homepageAction(Request $request, $ePage = 1){
		$log = $this->get('logger');
		$em = $this->getDoctrine()->getManager();
		
		$loggedInUser = $this->getUser();
		
			
		$expense = new Expense();
		$form= $this->createFormBuilder($expense)
					->add("amount")
					->add("description")
					->getForm();
		$form->handleRequest($request);
		
		if($loggedInUser == null){
			return $this->render("ExpenseSiteBundle:Expense:homepageNotLogin.html.twig",
					array(
							"expense" => $form->createView(),
					)
			
			);
		}
		
		
		/* find total expense starts here */
		//get total expense made today
		$to = new \DateTime();
		
		$from = clone $to;
		$from = $from->sub(\DateInterval::createFromDateString("1 days"));
		$todayExpense = $em->getRepository("ExpenseStoreBundle:Expense")->getTotalSpendingOf($loggedInUser, $from, $to );
		
		//get total expense made this week
		$from = clone $to;
		$from = $from->sub(\DateInterval::createFromDateString("7 days"));
		$weeklyExpense = $em->getRepository("ExpenseStoreBundle:Expense")->getTotalSpendingOf($loggedInUser, $from, $to );
		
		//get total expense made this month
		$from = clone $to;
		$currentDayOfMonth = date('j'); //get total days gone in this month
		$from = $from->sub(\DateInterval::createFromDateString($currentDayOfMonth . " days"));
		$monthlyExpense = $em->getRepository("ExpenseStoreBundle:Expense")->getTotalSpendingOf($loggedInUser, $from, $to );
		/* find total expense starts ends*/
		
		
		/* count number of expenses incured by the user and use this count in pager */
			$dql = "Select  e.id from ExpenseStoreBundle:Expense e join e.user u where u.id=?1 order by e.created desc";
			
			$expensesCount = $em->createQuery($dql)
						->setParameter(1, $loggedInUser->getId())
						->getResult();
			$totalPagerCount = 1 + intval(count($expensesCount) /Utils::$paginationLimit);
			
		/* expense counter ends */
		
		if ($form->isValid()) {
			$expense = $form->getData();
			
			$expense->setUser($loggedInUser);
			
			$expense->setCreated(new \DateTime());
			$em->persist($expense);
			$em->flush();
		}
			
		return $this->render("ExpenseSiteBundle:Expense:homepage.html.twig", 
					array(
						 "expense" => $form->createView(),
						  "todayExpense" => $todayExpense,
						  "weeklyExpense" =>	$weeklyExpense,
						  "monthlyExpense" => $monthlyExpense,
						  "ePage" => $ePage,
						  "totalPagerCount" => $totalPagerCount,	
					)
				
		      );
	}
	
	/**
	 * @Route("/expenseList/{page}", requirements={"page" = "\d+"})
	 * @Template()
	 */
	public function expenseListAction(Request $request, $page){
		$em = $this->getDoctrine()->getManager();
		$loggedInUser = $this->getUser();
		
		if($loggedInUser != null){
			$dql = "Select  e from ExpenseStoreBundle:Expense e join e.user u where u.id=?1 order by e.created desc";
			
			$expenses = $em->createQuery($dql)
								->setParameter(1, $loggedInUser->getId())
								->setMaxResults(Utils::$paginationLimit)
								->setFirstResult(($page -1) * Utils::$paginationLimit)
								->getResult();
		}
		
		
		return array(
				"expenses" => $expenses,
		);
														
	}
	
	
}

?>