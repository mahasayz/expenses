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
use Expense\StoreBundle\POJO\GraphData;

class ExpenseController extends Controller{
	
	public function getGraphData($user){
		$conn = $this->get('database_connection');
		$result = $conn->fetchAll("SELECT SUM(amount) AS amount, MONTHNAME(created) AS r FROM expense WHERE user_id = $user GROUP BY MONTH(created)");
	
		foreach ($result as $row){
			$rec = new GraphData();
			$rec->setPeriod($row['r']);
			$rec->setAmount($row['amount']);
			$arr[] = $rec;
		}
		$json = json_encode($arr);
		return $json;
	}
	
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
			$totalPagerCount = ceil(count($expensesCount) /Utils::$paginationLimit);
			
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
						  "graphData" => $this->getGraphData("1"),
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
		
		$countryOptions = array();
		foreach(Utils::getCountry() as $key => $value){
			$countryOptions[$key] = $key . " ($value)";
		}
		
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
				"countryOptions" => $countryOptions,
		);
														
	}
	
	/**
	 * @Route("/currencyConverter", name="expense_currency_converter")
	 */
	public function currencyConverterAction(Request $request){
		$loggedInUser = $this->getUser();
		$from = $request->request->get('from');
		$to = $request->request->get('to');
		$amount = $request->request->get('amount');
		
		$currencySymbol = Utils::getCountry()[$to];
		
		$convertedValue = Utils::currencyConverter($amount, $from, $to);
		
		if($convertedValue != null){
			return new Response("$currencySymbol $convertedValue");
		}else{
			return new Response("Not supported");
		}
		
	}
	
	
}

?>
