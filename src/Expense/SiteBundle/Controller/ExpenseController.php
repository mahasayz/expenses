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
	
	/**
	 * @Route("/graph", name="graph_data")
	 */
	public function getGraphData(Request $request){
		$user = $request->request->get('user');
			$type = $request->request->get('type');
			$month = $request->request->get('month');
		$res = $this->getGData($user, $type, $month);
		
		$response = new Response($res);
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	public function getGData($user, $type=NULL, $month=NULL){
		if (!isset($type))
			$type = "monthly";
		$conn = $this->get('database_connection');
		if ($type == "monthly")
			$result = $conn->fetchAll("SELECT SUM(amount) AS amount, MONTHNAME(created) AS r FROM expense WHERE user_id = 1 GROUP BY MONTH(created)");
		else
			$result = $conn->fetchAll("SELECT SUM(amount) AS amount, (WEEK(created) - WEEK(DATE_SUB(created, INTERVAL DAYOFMONTH(created)-1 DAY))+1) AS r FROM expense WHERE user_id=1 AND MONTHNAME(created)='$month' GROUP BY WEEKOFYEAR(created)");
		foreach ($result as $row){
			$rec = new GraphData();
			$rec->setPeriod($row['r']);
			$rec->setAmount($row['amount']);
			$rec->setType($type);
			$arr[] = $rec;
		}
		$json = "";
		if (isset($arr))
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
			
			if(count($expensesCount) == 0)
				$totalPagerCount = 1;
			else
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
						  "graphData" => $this->getGData("1"),
					)
				
		      );
	}
	
	/**
	 * @Route("/expenseList/{page}", requirements={"page" = "\d+"})
	 * @Template()
	 */
	public function expenseListAction(Request $request, $page){
		
		$loggedInUser = $this->getUser();
		
		$countryOptions = $this->getCountryOptions();
		
		
		if($loggedInUser != null){
			$expenses = $this->getExpenses($loggedInUser, $page);
		}
		
		
		return array(
				"expenses" => $expenses,
				"countryOptions" => $countryOptions,
		);
														
	}
	
	/**
	 * @Route("/expenseShowAll/{page}", requirements={"page" = "\d+"}, name="expense_showAll")
	 * @Template()
	 */
	public function expenseShowAllAction(Request $request, $page){
	
		$loggedInUser = $this->getUser();
	
		$countryOptions = $this->getCountryOptions();
	
		$expenses = "";
		if($loggedInUser != null){
			$expenses = $this->getExpenses($loggedInUser, $page, 10);
		}
	
	
		return array(
				"expenses" => $expenses,
				"countryOptions" => $countryOptions,
		);
	
	}
	
	/**
	 * @Route("/expenseShowAllPartial/{page}", requirements={"page" = "\d+"}, name="expense_showAll_partial")
	 */
	public function expenseShowAllPartialAction(Request $request, $page){
	
		$loggedInUser = $this->getUser();
	
		$countryOptions = $this->getCountryOptions();
	
		if($loggedInUser != null){
			$expenses = $this->getExpenses($loggedInUser, $page, 10);
		}
	
	
		return $this->render("ExpenseSiteBundle:Expense:expenseTemplate.html.twig",
				array(
						"expenses" => $expenses,
						"countryOptions" => $countryOptions,
						"nextPage" => $page + 1,
				)
					
		);
		
	
	}
	
	
	private function getCountryOptions(){
		$countryOptions = array();
		foreach(Utils::getCountry() as $key => $value){
			$countryOptions[$key] = $key . " ($value)";
		}
		return $countryOptions;
	}
	
	private function getExpenses($loggedInUser, $page, $limit = null){
		if($limit == null){
			$limit = Utils::$paginationLimit;
		}
		$em = $this->getDoctrine()->getManager();
		
		$dql = "Select  e from ExpenseStoreBundle:Expense e join e.user u where u.id=?1 order by e.created desc";
			
		$expenses = $em->createQuery($dql)
							->setParameter(1, $loggedInUser->getId())
							->setMaxResults($limit)
							->setFirstResult(($page -1) * $limit)
							->getResult();
		
		return $expenses;
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
			return new Response("$currencySymbol ". number_format($convertedValue, 2, '.', ''));
		}else{
			return new Response("Not supported");
		}
		
	}
	
	
	
}

?>
