<?php

namespace AppBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Relations;
use AppBundle\Entity\Agency;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
	
	
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {	
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
		
		// setto preliminarmente dei parametri standard per le variabili che mi aspetto di recuperare da Facebook
		$idFacebook = 'Id non impostato';
		$firstName = 'Nome non impostato';
		$lastName = 'Cognome non impostato';
		$eMail = 'Email non impostata'; 
		$location = 'Location non impostata';
		$picture = 'Foto non impostata';
		$friends = 'Friends non impostati';
		$taggedPlaces = 'Tagged place non impostati';	
		
		// recupero la response completa e la salvo in un array 
		$data = $response->getResponse();	
		
		// creo un array contenente le chiavi dell'array $data
		$keys = array_keys($data);
		
		// ciclo l'array per impostare i parameri restituiti da Facebook
		foreach($keys as $key)
		{
			switch ($key)
			{
				case 'id': 
					$idFacebook = $data['id']; 
					break;
				case 'first_name': 
					$firstName = $data['first_name'];
					break;
				case 'last_name':
					$lastName = $data['last_name'];
					break;
				case 'email':
					$eMail = $data['email'];
					break;
				case 'location':
					$location = $data['location']['name'];
					break;
				case 'picture':
					$picture = $data['picture']['data']['url'];
					break;
				case 'friends':
					$friends = $data['friends']['data'];
					break;
				case 'tagged_places':
					$taggedPlaces = $data['tagged_places'];
					break;	
			}
		}
		
        // when the user is registrating
        if (null === $user)
		{
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
			
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
			
            // I have set all requested data with the user's username modify here with relevant data
            $user->setUsername($firstName . " " . $lastName);
            $user->setEmail($eMail);
            $user->setPassword('');
			$user->setLocation($location);
			$user->setPicture($picture);
			$user->setPhone('Inserisci il tuo numero di telefono!');
			$user->setMotto('Inserisci il tuo motto!');
			$user->setAge('Inserisci la tua età!');
			$user->setEmailSecondary('Inserisci un indirizzo mail alternativo!');			
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }
		
        // se l'utente esiste -> go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';	
		
        // aggiorno alcuni valori nella tabella utente
        $user->$setter($response->getAccessToken());
		$user->setUsername($firstName . " " . $lastName);
		$user->setLocation($location);
		$user->setEmail($eMail);
		$user->setPicture($picture);
		
		// salvo amici e tagged places in una session per poterli usare il un altro controller
		$_SESSION["friends"] = $friends;
		$_SESSION["tagged"] = $taggedPlaces;
			
        return $user;		
    }
}