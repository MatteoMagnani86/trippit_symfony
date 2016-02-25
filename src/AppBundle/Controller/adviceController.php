<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Mailer\TwigSwiftMailer as BaseMailer;
use AppBundle\Entity\Token;
use AppBundle\Entity\Trip;
use AppBundle\Entity\Destination;
use AppBundle\Entity\Advice;

class adviceController extends Controller
{	
	// action finalizzata alla creazione della pagina di richiesta consiglio
    /**
     * @Route("/askadvice", name="askadvice")
     */
    public function askadviceAction(Request $request)
	{		
		// istruzioni necessarie all'identificazione dell'utente in base al facebook id di connessione
		$userManager = $this->container->get('fos_user.user_manager');
        $userConnected = $userManager->findUserByUsername($this->container->get('security.context')->getToken()->getUser());
		
		// recupero amici e tagged places salvati nella session
		$taggedPlaces = $_SESSION['tagged'];
		$friends = $_SESSION['friends'];		
		
		$friendList = array();
		
		// scorro la lista degli amici di Facebook iscritti alla piattaforma
		foreach($friends as $friend)
		{
			// salvo un array contenente la lista degli amici di Facebook iscritti alla piattaforma	
			$friendRespository = $this->getDoctrine()
				->getRepository('AppBundle:Agency');
				
			$friendList[] = $friendRespository->findOneBy(array('facebook_id' => $friend['id']));	
		}
		
		// implementazione del form per la richiesta di consiglio
		$formAskAdvice = $this->createFormBuilder()
			->add('facebookCheck', 'checkbox', array('required' => false))
			->add('friendsCheck', 'checkbox', array('required' => false))
			->add('mailList', 'text')
			->add('body', 'textarea')
			->add('save', 'submit', array('label' => 'Invia'))
			->getForm();		
		
		$formAskAdvice->handleRequest($request);		
		
		// se il form viene compilato correttamente, invio la richiesta di consiglio
		if ($formAskAdvice->isValid())
		{
			$this->newAdvice($formAskAdvice, $request, $friends);
			$this->addFlash('notice', 'Hai inviato una richiesta di consiglio');
			return $this->redirect($request->server->get('HTTP_REFERER'));
		}

		$tripId = $request->get('tripId');
		$destination = $request->get('destination');
		$period = $request->get('period');
		
        return $this->render('default/askAdvice.html.twig', [
			'formAskAdvice' => $formAskAdvice->createView(),
			'userConnected' => $userConnected,
			'destination' => $destination,
			'period' => $period,
			'friendList' => $friendList,			
		]);
    }	
    
    // action finalizzata alla creazione della pagina di invio del consiglio
    /**
     * @Route("/giveadvice/{tokenNumber}", name="giveadvice")
     */
    public function giveadviceAction(Request $request, $tokenNumber)
	{		
		// istruzioni necessarie all'identificazione dell'utente in base al facebook id di connessione
		$userManager = $this->container->get('fos_user.user_manager');
        $userConnected = $userManager->findUserByUsername($this->container->get('security.context')->getToken()->getUser());
		
		// recupero il token			
		$tokenRepository = $this->getDoctrine()
			->getRepository('AppBundle:Token');
		
		$token = $tokenRepository->findOneByToken($tokenNumber);		
		
		// recupero viaggio e utente legati al token
		$trip = $token->getTrip();		
		$user = $trip->getUser();

		// recupero i parametri relativi ad utente e viaggio		
		$name = explode(" ", $user->getUsername());		
		$firstName = $name[0];
		$destination = $trip->getDestination();
		$period = $trip->getPeriod();	
		
		// se il token è attivo, permetto l'inserimento del consiglio
		if($token->getBurned() == '0')
		{		
			// form per l'inserimento del commento
			$formAdvice = $this->createFormBuilder()
				->add('author', 'text')
				->add('advice', 'textarea')
				->add('save', 'submit', array('label' => 'Invia'))
				->getForm();	
			
			$formAdvice->handleRequest($request);	
			
			// se il form è valido, salvo il commento inserito
			if ($formAdvice->isValid())
			{			
				$data = $formAdvice->getData();
				
				$newAdvice = new Advice();
						
				$newAdvice->setDescription($data['advice']);
				$newAdvice->setAuthor($data['author']);
				$newAdvice->setInsertDate($date = $this->updated = new \DateTime("now"));
				$newAdvice->setTrip($trip);
				$newAdvice->setUserLike(0);
				$newAdvice->setAgencyLike(0);
					
				$em = $this->getDoctrine()->getManager();
				$em->persist($newAdvice);
				$em->flush();
				
				// il token viene 'bruciato' per far in modo che non sia più valido
				$token->setBurningDate($date = $this->updated = new \DateTime("now"));
				$token->setBurned('1');
				$em->persist($token);
				$em->flush();

				return $this->redirect($request->getUri());
			};
			
			$activeToken = 'yes';
			
			return $this->render('default/giveAdvice.html.twig', [
				'formAdvice' => $formAdvice->createView(),
				'userConnected' => $userConnected,
				'user' => $user,
				'tokenAttivo' => $activeToken,
				'firstName' => $firstName,
				'destination' => $destination,
				'period' => $period,
			]);		
		}
		// se il token è 'bruciato', avviso del già avvenuto inserimento
		else
		{				
			$activeToken = 'no';
				
			return $this->render('default/giveAdvice.html.twig', [
				'userConnected' => $userConnected,
				'user' => $user,
				'tokenAttivo' => $activeToken,				
				'firstName' => $firstName,
				'destination' => $destination,
				'period' => $period,				
			]);			
		}    
    }

	// funzione finalizzata alla gestione della richiesta di consiglio
	private function newAdvice($formAskAdvice, $request, $friends)
	{	
		// seleziono il viaggio per il quale sto generando il token			
		$tripRepository = $this->getDoctrine()
			->getRepository('AppBundle:Trip');
		
		$trip = $tripRepository->findOneById($request->get('tripId'));		
		
		// recupero i dati inseriti nel form validato
		$data = $formAskAdvice->getData();		
		$body =	$data['body'];
		
		$receiverList = array();
		
		// salvo nella lista dei destinatari la mail degli altri amici
		if($data['friendsCheck'] == 1)
		{
			$receiver = $data['mailList'];
			$receiverList = explode(",", $receiver);
		}	
		
		// salvo nella lista dei destinatari la mail degli amici di facebook
		if($data['facebookCheck'] == 1)
		{	
			foreach($friends as $friend)
			{					
				$friendRespository = $this->getDoctrine()
					->getRepository('AppBundle:Agency');
				
				$friend = $friendRespository->findOneBy(array('facebook_id' => $friend['id']));
				
				$receiverList[] = $friend->getEMail();
			}		
		}	
		
		// istanzio l'oggetto mailer
		$mailer = $this->get('mailer');	
		
		// invio un diverso messaggio ad ogni indirizzo presente nell'array 
		foreach($receiverList as $receiver)
		{
			// generazione e salvataggio del token di accesso alla pagina d'inserimento commento
			$tokenNumber = bin2hex(openssl_random_pseudo_bytes(16));				
			
			$newToken = new Token();
						
			$newToken->setToken($tokenNumber);
			$newToken->setBurningDate($this->updated = new \DateTime("0000-00-00"));
			$newToken->setTrip($trip);
			$newToken->setBurned('0');
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($newToken);
			$em->flush();			
			
			// istanzio l'oggetto messaggio
			$message = \Swift_Message::newInstance();
			
			// setto l'indirizzo mail del mittente presente nel file parameters.yml
			$sender = $this->getParameter('mailer_user');
			
			// setto destinatario, oggetto e testo del messaggio			
			$object = 'Vorrei un consiglio per un viaggio';
			
			$message->setFrom($sender);
			$message->setTo($receiver);
			$message->setSubject($object);	
			$message->setBody($this->renderView('default/adviceMessage.html.twig', array('body' => $body,'tokenNumber' => $tokenNumber)),'text/html');

			// invio il messaggio
			$mailer->send($message);
		}
	}	
}