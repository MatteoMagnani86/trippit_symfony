<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Trip;


class ManageTripController extends Controller
{
	
	/**
     * @Route("/create/trip{urlid}", name="create/trip")
     */
    public function createTripAction(Request $request)
	{
		$idUtente = $request->get('urlid');
		$erUser = $this->getDoctrine()->getRepository('AppBundle:User');
		$user = $erUser->find($idUtente);

		$erDestination = $this->getDoctrine()->getRepository('AppBundle:Destination');
		$Destination = $erDestination->find(3);
		
		$erPeriod = $this->getDoctrine()->getRepository('AppBundle:Period');
		$Period = $erPeriod->find(2);
		//$Period = $erPeriod->findByDescription($request->get('period'));
		
		$date = $this->updated = new \DateTime("now");
		$dateDefault = $this->updated = new \DateTime("0000-00-00");
			
		$trip = new Trip();		
		
		$trip->setUser($user);
		$trip->setDestination($Destination);
		$trip->setPeriod($Period);
		$trip->setInsertDate($date);
		$trip->setPicture('Nessuna immagine');
		$trip->setArchived($dateDefault);
		
		$em = $this->getDoctrine()->getManager();
        $em->persist($trip);
        $em->flush();
		
		return $this->redirect($request->server->get('HTTP_REFERER'));
    }
	
}