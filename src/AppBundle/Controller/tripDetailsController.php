<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Agency;
use AppBundle\Entity\Trip;
use AppBundle\Entity\Destination;
use AppBundle\Entity\Place;
use AppBundle\Entity\Plan;

class tripDetailsController extends Controller
{	    
	// action finalizzata alla creazione della pagina di dettaglio del viaggio
    /**
     * @Route("/tripdetails/", name="tripdetails")
     */
    public function tripDetailsAction(Request $request)
	{		
        // istruzioni necessarie all'identificazione dell'utente in base al facebook id di connessione
		$userManager = $this->container->get('fos_user.user_manager');
        $userConnected = $userManager->findUserByUsername($this->container->get('security.context')->getToken()->getUser());
		
		// recupero dal DB il viaggio  
		$tripRepository = $this->getDoctrine()
			->getRepository('AppBundle:Trip');
		
		$trip = $tripRepository->findOneById($request->get('tripId'));

        // recupero dal DB la lista dei commenti associati ad uno specifico utente
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('
			SELECT a.description, a.author, t.id AS trip
			FROM AppBundle:trip t, AppBundle:agency u, AppBundle:advice a
			WHERE a.trip = t.id AND t.user = u.id AND u.id = :idUtente'
		)->setParameter('idUtente', $userConnected->getId());	

		$advicesList = $query->getResult();

		// creo il form di aggiunta memo alla schedule
		$formPlanner = $this->createFormBuilder()
			->add('place', 'text', array('label' => 'Che posto vuoi vedere?'))
			->add('day', 'integer', array('label' => 'In che giorno ci vuoi andare?'))
			->add('note', 'textarea', array('label' => 'Scrivi una nota'))
			->add('save', 'submit', array('label' => 'Salva'))
			->getForm();

		$formPlanner->handleRequest($request);		

		if ($formPlanner->isValid())
		{			
			$this->newPlan($formPlanner,$trip);			
			return $this->redirect($request->getUri());
		};

		// recupero dal DB la lista dei plan dell'utente, selezionandoli per id trip
		$emPlan = $this->getDoctrine()->getEntityManager();
		$planQuery = $emPlan->createQuery('
			SELECT p
			FROM AppBundle:plan p
			WHERE p.tripId = :idTrip
			ORDER BY p.day ASC'
		)->setParameter('idTrip', $trip->getId());

		$planList = $planQuery->getResult();

		// recupero dal DB la lista dei places legati a questo plan dell'utente, selezionandoli per id trip
		$emPlace = $this->getDoctrine()->getEntityManager();
		$placeQuery = $emPlace->createQuery('
			SELECT l
			FROM AppBundle:place l, AppBundle:plan p
			WHERE p.tripId = :idTrip
			ORDER BY l.id ASC'
		)->setParameter('idTrip', $trip->getId());

		$placeList = $placeQuery->getResult();

    	return $this->render('default/tripDetails.html.twig', [
			'formPlanner' => $formPlanner->createView(),
			'userConnected' => $userConnected,
			'advicesList' => $advicesList,
			'trip' => $trip,
			'planList' => $planList,
			'placeList' => $placeList,
		]);
	}
	
	// funzione finalizzata alla gestione della creazione di un nuovo plan
	private function newPlan($formPlanner, $trip)
	{
		$data = $formPlanner->getData();
		
		// recupero il place inserito dall'utente
		$userPlace = $data['place'];
		
		$arrayPlace = explode(", ", $userPlace);
		
		// recupero tutti i place per verificare che quello inserito esista o meno nel database
		$emPlace = $this->getDoctrine()->getEntityManager();
		$queryPlace = $emPlace->createQuery('
			SELECT p.id, p.name, p.city, p.nation
			FROM AppBundle:place p'
		);
	
		$placeList = $queryPlace->getResult();

		$controllo = 1;

		// verifico che il place inserito sia presente nel DB
		foreach ($placeList as $pl)
		{ 

			// var_dump($pl); die();

			if($pl['name'] === $arrayPlace[0] && $pl['city'] === $arrayPlace[1] && $pl['nation'] === $arrayPlace[3])
			{
				// se accerto che il place già esiste nel DB, la uso
				$erPlace = $this->getDoctrine()->getRepository('AppBundle:Place');
				$place = $erPlace->findOneById($pl['id']);
				
				$controllo = 0;
				break;
			}
			else
			{
				$controllo = 1;
				continue;
			}		
		}
		
		// se accerto che la destinazione inserita dall'utente non esiste nel DB, ne creo una nuova
		if($controllo === 1)
		{
			$newPlace = new Place();
				
			$newPlace->setName($arrayPlace[0]);
			$newPlace->setCity($arrayPlace[1]);
			$newPlace->setNation($arrayPlace[3]);
			$newPlace->setLatitude('non impostata');
			$newPlace->setLongitude('non impostata');
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($newPlace);
			$em->flush();			
			
			$place = $newPlace;			
		}	

		// recupero il day selezionato dall'utente
		$descriptionDay = $data['day'];

		// recupero la note selezionato dall'utente
		$descriptionNote = $data['note'];
		
		$plan = new Plan();
		
		$plan->setTripId($trip);
		$plan->addPlace($place);
		$plan->setDay($descriptionDay);
		$plan->setComment($descriptionNote);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($plan);
		$em->flush();
	}
}