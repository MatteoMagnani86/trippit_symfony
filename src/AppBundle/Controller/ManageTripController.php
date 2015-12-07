<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ManageTripController extends Controller
{
	
	/**
     * @Route("/create/trip{urlid}", name="create/trip")
     */
    public function createTripAction(Request $request)
	{
		$idUtente = $request->get('urlid');
			
		
		
		
		return $this->redirect($request->server->get('HTTP_REFERER'));
    }
	
}