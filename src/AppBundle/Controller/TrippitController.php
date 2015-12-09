<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\User;

// il nome della classe deve essere uguale al nome del file controller
class TrippitController extends Controller
{	

	// action per richiamare il login agenzia
	 // /**
     // * @Route("/agency/login", name="agency/login")
     // */
    // public function loginAction(Request $request)
	// {
    //     return $this->render('vendor/friendsofsymfony/user-bundle/Resources/views/Security/login.html.twig');
    // }

	
    // action per richiamare il report per l'agenzia
    /**
     * @Route("/agency/data", name="agency/data")
     */
    public function dataAction(Request $request)
	{
		// recupero dal DB tutti gli utenti
		$erUser = $this->getDoctrine()->getRepository('AppBundle:User');
		$usersList = $erUser->findAll();

		// recupero dal DB la lista completa dei periodi
		$erPeriod = $this->getDoctrine()->getRepository('AppBundle:Period');				
		$periodsList = $erPeriod->findAll();
	
		// recupero dal DB la lista completa delle destinazione	
		$erDestination = $this->getDoctrine()->getRepository('AppBundle:Destination');
        $destinationsList = $erDestination->findAll();
		
		// recupero dal DB la lista completa dei viaggi		
		$erTrip = $this->getDoctrine()->getRepository('AppBundle:Trip');
        $tripsList = $erTrip->findAll();
		
		return $this->render('default/agencyData.html.twig',[
			'usersList' => $usersList,
			'periodsList' => $periodsList,
			'destinationsList' => $destinationsList,
			'tripsList' => $tripsList,
		]);	
    }
	
	
	// action per richiamare la home utente
    /**
     * @Route("/user/home/{urlid}", name="user/home")
     */
	public function homeAction(Request $request)
	{
		$idUtente = $request->get('urlid');
        
		return $this->render('default/home.html.twig',[
			'id' => $idUtente,
		]);
    }

	
    // action per richiamare il login user
    /**
     * @Route("/user/login/{urlid}", name="user/login")
     */
    public function userLoginAction(Request $request)
	{
		$idUtente = $request->get('urlid');
		
        return $this->render('default/userLogin.html.twig',[
			'id' => $idUtente,
		]);
    }

	
    // action per richiamare il profilo
    /**
     * @Route("/user/profile/{urlid}", name="user/profile")
     */
    public function profileAction(Request $request)
	{
		$idUtente = $request->get('urlid');	
		
		// recupero dal DB l'utente, selezionandolo per id utente
		$erUser = $this->getDoctrine()->getRepository('AppBundle:User');
		$user = $erUser->find($idUtente);			 

		// recupero dal DB la lista completa dei periodi
		$erPeriod = $this->getDoctrine()->getRepository('AppBundle:Period');				
		$periodsList = $erPeriod->findAll();
				
		// recupero dal DB la lista completa dei viaggi dell'utente, selezionandoli per id utente		
		$erTrip = $this->getDoctrine()->getRepository('AppBundle:Trip');
        $tripsList = $erTrip->findByUser($idUtente);
		
		// recupero dal DB la lista completa dei commenti
		$erAdvice = $this->getDoctrine()->getRepository('AppBundle:Advice');
        $advicesList = $erAdvice->findByTrip(1);		
		
        return $this->render('default/profile.html.twig',[
			'id' => $idUtente,
			'user' => $user,
			'periodsList' => $periodsList,
			'tripsList' => $tripsList,
			'advicesList' => $advicesList,
		]);		
    }   

	
    // action per richiamare le impostazioni
    /**
     * @Route("/user/settings/{urlid}", name="user/settings")
     */
    public function settingsAction(Request $request)
	{
		$idUtente = $request->get('urlid');
		
		// recupero dal DB l'utente, selezionandolo per id utente
		$erUser = $this->getDoctrine()->getRepository('AppBundle:User');
		$user = $erUser->find($idUtente);
				
        return $this->render('default/settings.html.twig', [
			'id' => $idUtente,
			'user' => $user,			
		]);
    }

	
    // action per richiamare askAdvice
    /**
     * @Route("/user/askadvice/{urlid}", name="user/askadvice")
     */
    public function askadviceAction(Request $request)
	{
		$idUtente = $request->get('urlid');
		$destination = $request->get('destination');
		$period = $request->get('period');
		
        return $this->render('default/askAdvice.html.twig', [
			'id' => $idUtente,
			'destination' => $destination,
			'period' => $period,
		]);
    }

	
    // action per richiamare giveAdvice
    /**
     * @Route("/user/giveadvice{urlid}", name="user/giveadvice")
     */
    public function giveadviceAction(Request $request)
	{
		$idUtente = $request->get('urlid');
		
        return $this->render('default/giveAdvice.html.twig', [
			'id' => $idUtente,
		]);
    }
	
}