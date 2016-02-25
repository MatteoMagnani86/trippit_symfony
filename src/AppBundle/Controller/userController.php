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

class userController extends Controller
{    	
    // action finalizzata alla creazione della pagina profilo
    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request)
	{		
		// istruzioni necessarie all'identificazione dell'utente in base al facebook id di connessione
		$userManager = $this->container->get('fos_user.user_manager');
		$userConnected = $userManager->findUserByUsername($this->container->get('security.context')->getToken()->getUser());
		
		$userIdPublic = $request->get('userId');
		
		// la variabile userIdPublic non è impostata. Non vi è richiesta di visualizzzazione di un profilo da parte di terzi	
		if($userIdPublic == null)
		{	
			$publicProfile = false;	
			
			// creo il form per l'inserimento di un nuovo viaggio 	
			$formNewTrip = $this->formTrip();
			$formNewTrip->handleRequest($request);		
			
			// se il form viene compilato correttamente, inserisco il nuovo viaggio
			if ($formNewTrip->isValid())
			{
				$this->newTrip($formNewTrip, $userConnected, $request);
				return $this->redirect($request->getUri());
			}		
			
			// recupero dal DB la lista dei viaggi non archiviati dell'utente, selezionandoli per id utente
			$tripRepository = $this->getDoctrine()
				->getRepository('AppBundle:Trip');

			$tripQuery = $tripRepository->createQueryBuilder('t')
				->where('t.archived = 0000-00-00 AND t.user = :idUtente')
				->orderBy('t.id', 'DESC')
				->setParameter('idUtente', $userConnected->getId())
				->getQuery();			

			$tripList = $tripQuery->getResult();	
			
			$countTrip = count($tripList);		
			
			// recupero dal DB la lista dei commenti associati ad uno specifico utente
			$em = $this->getDoctrine()->getEntityManager();
			$query = $em->createQuery('
				SELECT a.description, a.author, t.id AS trip
				FROM AppBundle:trip t, AppBundle:agency u, AppBundle:advice a
				WHERE a.trip = t.id AND t.user = u.id AND u.id = :idUtente
				ORDER BY a.id DESC'
			)->setParameter('idUtente', $userConnected->getId());	

			$adviceList = $query->getResult();			
			
			// ritorno i parametri necessari alla costruzione della pagina profilo
			return $this->render('default/profile.html.twig',[
				'formNewTrip' => $formNewTrip->createView(),
				'publicProfile'=>$publicProfile,
				'userConnected'=>$userConnected,
				'tripsList' => $tripList,
				'advicesList' => $adviceList,
				'countTrip' => $countTrip,		
			]);		
		}
		// la variabile userIdPublic è impostata. Vi è una richiesta di visualizzazione di un profilo da parte di terzi
		else
		{
			$publicProfile = true;	
			
			// recupero dal DB l'utente pubblico, in base al suo id
			$erUtente = $this->getDoctrine()
				->getRepository('AppBundle:Agency');
			
			$userPublic = $erUtente->findOneById($userIdPublic);					
			
			// recupero dal DB la lista dei viaggi non archiviati dell'utente, selezionandoli per id utente
			$emTrip = $this->getDoctrine()->getEntityManager();
			$tripQuery = $emTrip->createQuery('
				SELECT t
				FROM AppBundle:trip t
				WHERE t.archived = 0000-00-00 AND t.user = :idUtente
				ORDER BY t.id DESC'
			)->setParameter('idUtente', $userIdPublic);

			$tripList = $tripQuery->getResult();
			
			$countTrip = count($tripList);		
			
			// recupero dal DB la lista dei commenti associati ad uno specifico utente
			$em = $this->getDoctrine()->getEntityManager();
			$query = $em->createQuery('
				SELECT a.description, a.author, t.id AS trip
				FROM AppBundle:trip t, AppBundle:agency u, AppBundle:advice a
				WHERE a.trip = t.id AND t.user = u.id AND u.id = :idUtente'
			)->setParameter('idUtente', $userIdPublic);	

			$adviceList = $query->getResult();
			
			// ritorno i parametri necessari alla costruzione della pagina profilo
			return $this->render('default/profile.html.twig',[
				'publicProfile'=>$publicProfile,
				'userConnected'=>$userConnected,	
				'userPublic'=>$userPublic,				
				'tripsList' => $tripList,
				'advicesList' => $adviceList,
				'countTrip' => $countTrip,
			]);
		}	
    }	
	
	// action finalizzata all'archiviazione di un viaggio
    /**
     * @Route("/archive/trip", name="archive_trip")
     */
	public function archiveTripAction(Request $request)
	{
		// recupero l'id del viaggio da archiviare
		$tripId = $request->get('tripId');
		
		// recupero dal BD il viaggio da archiviare
		$tripRepository = $this->getDoctrine()
			->getRepository('AppBundle:Trip');
					
		$trip = $tripRepository->findOneById($tripId);	
		
		// setto alla data corrente il campo 'archived' del viaggio da archiviare
		$trip->setArchived($this->updated = new \DateTime("now"));

		//salvo le modifiche fatte al viaggio selezionato
		$em = $this->getDoctrine()->getManager();
		$em->persist($trip);
		$em->flush();		
		
		// faccio un redirect alla pagina di profilo
		return $this->redirect($request->server->get('HTTP_REFERER'));		
	}
	
	// funzione finalizata alla creazione del form di inserimento di un nuovo viaggio
	private function formTrip()
	{
		// recupero dal DB la lista completa dei periodi e li ordino
		$periodRepository = $this->getDoctrine()
			->getRepository('AppBundle:Period');
			
		$periodQuery = $periodRepository->createQueryBuilder('p')
			->orderBy('p.id', 'ASC')
			->getQuery();

		$periodList = $periodQuery->getResult();
		
		// a partire dalla lista dei periodi, creo un array contenente solo le descrizioni dei periodi
		$periodDescription = array();
		
		foreach($periodList as $period)
		{
			$periodDescription[] = $period->getDescription();
		}
		
		// creo il form di inserimento nuovo viaggio
		$formNewTrip = $this->createFormBuilder()
			->add('destination', 'text', array('label' => 'Dove vorresti andare?'))
			->add('periods', 'choice', array('choices' => $periodDescription, 'label' => 'Quando vorresti partire?', 'placeholder' => 'Seleziona il periodo'))
			->add('save', 'submit', array('label' => 'Crea viaggio'))
			->getForm();
			
		return $formNewTrip;	
	}
	
	// funzione finalizzata alla gestione della creazione di un nuovo viaggio
	private function newTrip($formNewTrip, $userConnected, $request)
	{	
		// recupero i dati inseriti nel form validato
		$data = $formNewTrip->getData();
		
		// recupero la destinazione inserita dall'utente
		$userDestination = $data['destination'];		
		$destinationDetails = explode(", ", $userDestination);
		
		// recupero dal BD il periodo selezionato dall'utente
		$periodDescription = $data['periods'];
		
		$periodRepository = $this->getDoctrine()
			->getRepository('AppBundle:Period');
			
		$period = $periodRepository->findOneById($periodDescription + 1);
				
		// recupero tutte le destinazioni presenti nel DB per verificare se quella inserita esista o meno
		$destinationRepository = $this->getDoctrine()
			->getRepository('AppBundle:Destination');

		$destinationList = $destinationRepository->findAll();
		
		// verifico se la destinazione inserita sia presente o meno nel DB
		foreach ($destinationList as $destination)
		{ 
			if($destination->getName() === $destinationDetails[0] && $destination->getCountry() === $destinationDetails[1] && $destination->getLatitude() === $destinationDetails[2] && $destination->getLongitude() === $destinationDetails[3])
			{
				// se la destinazione è già presente nel DB, la seleziono
				$destinationRepository = $this->getDoctrine()
					->getRepository('AppBundle:Destination');
					
				$destinationChoice = $destinationRepository->findOneById($destination->getId());
				
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
			$newDestination = new Destination();
				
			$newDestination->setName($destinationDetails[0]);
			$newDestination->setCountry($destinationDetails[1]);
			$newDestination->setLatitude($destinationDetails[2]);
			$newDestination->setLongitude($destinationDetails[3]);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($newDestination);
			$em->flush();			
			
			$destinationChoice = $newDestination;			
		}	
		
		// creo il nuovo viaggio
		$trip = new Trip();
		
		$trip->setUser($userConnected);
		$trip->setDestination($destinationChoice);
		$trip->setPeriod($period);
		$trip->setInsertDate($this->updated = new \DateTime("now"));
		$trip->setPicture('Immagine non impostata');
		$trip->setArchived($this->updated = new \DateTime("0000-00-00"));
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($trip);
		$em->flush();			
	}	
}