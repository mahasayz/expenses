<?php

namespace Expense\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ExpenseStoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
