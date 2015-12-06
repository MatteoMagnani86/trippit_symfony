<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\User;

// il nome della classe deve essere uguale al nome del file controller
class TrippitController extends Controller{
	
	// action per richiamare il login agenzia
	/**
     * @Route("/agency/login", name="agency/login")
     */
    public function loginAction(Request $request){
        return $this->render('default/agencyLogin.html.twig');
    }

    // action per richiamare il report per l'agenzia
    /**
     * @Route("/agency/data", name="agency/data")
     */
    public function dataAction(Request $request){
        return $this->render('default/agencyData.html.twig');
    }
	
	// action per richiamare la home utente
    /**
     * @Route("/user/home", name="user/home")
     */
	public function homeAction(Request $request){
        return $this->render('default/home.html.twig');
    }

    // action per richiamare il login user
    /**
     * @Route("/user/login", name="user/login")
     */
    public function userLoginAction(Request $request){
        return $this->render('default/userLogin.html.twig');
    }

    // action per richiamare il profilo
    /**
     * @Route("/user/profile", name="user/profile")
     */
    public function profileAction(Request $request){
		
		$id = 2; 		
		
		$user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id);
				 
		$periods = $this->getDoctrine()
                ->getRepository('AppBundle:Period')
				->findAll();							
				
        return $this->render('default/profile.html.twig', ['user' => $user,'periods' => $periods,]);		
    }   

    // action per richiamare le impostazioni
    /**
     * @Route("/user/settings", name="user/settings")
     */
    public function settingsAction(Request $request){
        $id = 2;
		
		$user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id);
				
        return $this->render('default/settings.html.twig', ['user' => $user,]);
    }

    // action per richiamare askAdvice
    /**
     * @Route("/user/askadvice", name="user/askadvice")
     */
    public function askadviceAction(Request $request){
        return $this->render('default/askAdvice.html.twig');
    }

    // action per richiamare giveAdvice
    /**
     * @Route("/user/giveadvice", name="user/giveadvice")
     */
    public function giveadviceAction(Request $request){
        return $this->render('default/giveAdvice.html.twig');
    }

}