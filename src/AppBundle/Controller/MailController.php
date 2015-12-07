<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Mailer\TwigSwiftMailer as BaseMailer;

class MailController extends Controller
{
	
	/**
     * @Route("/askadvice/mail/{urlid}", name="askadvice/mail")
     */
    public function adviceMailAction(Request $request)
	{
		$idUtente = $request->get('urlid');
		
		// istanzio l'oggetto mailer
		$mailer = $this->get('mailer');
		
		// istanzio l'oggetto messaggio
		$message = \Swift_Message::newInstance();
		
		// setto l'indirizzo mail del mittente presente nel file parameters.yml
		$sender = $this->getParameter('mailer_user');
		
		// setto destinatario, oggetto e testo del messaggio
		$receiver = $request->get('eMailReceiver');
		$object = 'Vorrei un consiglio per un viaggio';
		$body =	$request->get('eMailBody');							
		
		// setto l'oggetto messaggio
		$message->setFrom($sender);
		$message->setTo($receiver);
		$message->setSubject($object);
		$message->setBody($body);	
			
		// invio il messaggio
		$mailer->send($message);		
		
		$this->addFlash('notice', 'Hai inviato una richiesta di consiglio');
		return $this->redirect($request->server->get('HTTP_REFERER'));
    }
	
}