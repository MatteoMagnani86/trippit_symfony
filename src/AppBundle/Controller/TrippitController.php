<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Trip;
use AppBundle\Entity\Agency;
use AppBundle\Entity\Advice;

class trippitController extends Controller
{	
    // action finalizzata alla creazione della pagina di reportistica dell'agenzia
    /**
     * @Route("/agency/data", name="agency/data")
     */
    public function dataAction(Request $request)
	{		
		// recupero dal DB la lista di tutti gli utenti
		$userRepository = $this->getDoctrine()
			->getRepository('AppBundle:Agency');

		$userQuery = $userRepository->createQueryBuilder('u')
			->where('u.facebook_access_token IS NOT NULL')
			->orderBy('u.username', 'ASC')
			->getQuery();

		$usersList = $userQuery->getResult();	
		
		// recupero dal DB la lista dei periodi con i relativi utenti
		$emPeriod = $this->getDoctrine()->getEntityManager();
		$queryPeriod = $emPeriod->createQuery('
			SELECT t.id, p.description AS period, d.name AS destination, COUNT(d.id) AS numpersone
			FROM AppBundle:period p, AppBundle:destination d, AppBundle:trip t
			WHERE t.destination = d.id AND t.period = p.id AND t.archived = 0000-00-00
			GROUP BY p.description, d.name
			ORDER BY numpersone DESC'
		);		

		$periodsList = $queryPeriod->getResult();
				
		// recupero dal DB la lista delle destinazioni con i relativi utenti
		$emDestination = $this->getDoctrine()->getEntityManager();
		$queryDestination = $emDestination->createQuery('
			SELECT d.name AS name, d.latitude, d.longitude, COUNT(d.id) AS user
			FROM AppBundle:destination d, AppBundle:trip t, AppBundle:agency u 
			WHERE d.id = t.destination AND t.user = u.id AND t.archived = 0000-00-00
			GROUP BY d.name
			ORDER BY d.name ASC'
		);
		
		$destinationsList = $queryDestination->getResult();
	
		return $this->render('default/agencyData.html.twig',[
			'usersList' => $usersList,
			'periodList' => $periodsList,
			'destinationsList' => $destinationsList,
		]);	
    }	
	
	// action finalizzata alla creazione della home utente
    /**
     * @Route("/home", name="home")
     */
	public function homeAction(Request $request)
	{		
		// istruzioni necessarie all'identificazione dell'utente in base al facebook id di connessione
		$userManager = $this->container->get('fos_user.user_manager');
        $userConnected = $userManager->findUserByUsername($this->container->get('security.context')->getToken()->getUser());
		
		return $this->render('default/home.html.twig', [
			'userConnected' => $userConnected,
		]);
    }
	
    // action finalizzata alla creazione della pagina di login utente
    /**
     * @Route("/login/", name="login")
     */
    public function userLoginAction(Request $request)
	{		
        return $this->render('default/userLogin.html.twig');
    }
	
    // action finalizzata alla creazione delle pagina impostazioni utente
    /**
     * @Route("/settings", name="settings")
     */
    public function settingsAction(Request $request)
	{		
		// istruzioni necessarie all'identificazione dell'utente in base al facebook id di connessione
		$userManager = $this->container->get('fos_user.user_manager');
        $userConnected = $userManager->findUserByUsername($this->container->get('security.context')->getToken()->getUser());	
		
		// form per l'inserimento del commento
		$formSettings = $this->createFormBuilder()
			->add('age', 'integer', array('data' => $userConnected->getAge()))
			->add('secondMail', 'text', array('data' => $userConnected->getEmailSecondary()))
			->add('phone', 'text', array('data' => $userConnected->getPhone()))
			->add('motto', 'text', array('data' => $userConnected->getMotto()))
			->add('save', 'submit', array('label' => 'Salva'))
			->getForm();	
			
		$formSettings->handleRequest($request);	
		
		// se il form è valido, salvo il commento inserito
		if ($formSettings->isValid())
		{			
			$data = $formSettings->getData();
			
			$userConnected->setAge($data['age']);
			$userConnected->setEmailSecondary($data['secondMail']);
			$userConnected->setPhone($data['phone']);
			$userConnected->setMotto($data['motto']);
				
			$em = $this->getDoctrine()->getManager();
			$em->persist($userConnected);
			$em->flush();

			return $this->redirect($request->getUri());
		};
			
        return $this->render('default/settings.html.twig', [
			'formSettings' => $formSettings->createView(),
			'userConnected' => $userConnected,			
		]);
    }
}