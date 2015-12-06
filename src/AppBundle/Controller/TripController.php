<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class TripController extends Controller
{
	
	/**
     * @Route("/create/trip", name="create/trip")
     */
    public function createTripAction(Request $request)
	{
		
			
		
		return $this->redirectToRoute('user/profile');
    }
	
}