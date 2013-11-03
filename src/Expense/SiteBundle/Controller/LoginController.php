<?php

namespace Expense\SiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Expense\StoreBundle\Entity\User;
use Expense\StoreBundle\Utils;

class LoginController extends Controller
{
	/**
	 * @Route("/login", name="_my_login")
	 * @Template()
	 */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

         return  array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            );
    }
    
    /**
     * @Route("/login_check", name="_my_login_check")
     */
    public function loginCheckAction()
    {
    }
    
    /**
     * @Route("/logout", name="_my_logout")
     */
    public function logoutAction()
    {
    	// The security layer will intercept this request
    }
    
    /**
     * @Route("/signup", name="_my_signup")
     * @Template()
     */
    public function signUpAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	//get country code and symbol for dropdown
    	$countryOptions = array();
    	foreach(Utils::getCountry() as $key => $value){
    		$countryOptions[$key] = $key . " ($value)";
    	}
    	
    	$user = new User();
    	$form= $this->createFormBuilder($user)
			    	->add("firstName")
			    	->add("lastName")
			    	->add("username")
			    	->add("password", "password")
			    	->add("countryCode", "choice", array(
							    'choices' => $countryOptions,
							    'preferred_choices' => array('USD'),
							))
			    	->getForm();
    	$form->handleRequest($request);
    	
    	if ($form->isValid()) {
    		$user = $form->getData();
    		
    		/////password encoding//
    		$factory = $this->get("security.encoder_factory");
    		$encoder = $factory->getEncoder($user);
    		$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
    		$user->setPassword($password);
    		///password encoding ends//
    		
    		$role = $em->getRepository("ExpenseStoreBundle:Role")->findOneByRole("CUSTOMER");
    		$user->addRole($role);
    			
    		$em->persist($user);
    		$em->flush();
    		
    		$this->get('session')->getFlashBag()->add(
    				'success',
    				'Account created successfully!'
    		);
    		
    		///send email
    		/* $message = \Swift_Message::newInstance()
				    		->setSubject("Account Created")
				    		->setFrom("alu@alu.com")
				    		->setTo($user->getUsername())
				    		->setBody(
				    				"Thankyou {$user->getFirstName()} your account is created"
				    		);
    		$this->get('mailer')->send($message); */
    		
    		return $this->redirect($this->generateUrl("_my_login"));
    	}
    	
    		return array(
    				"user" => $form->createView(),
    		);
    }
}