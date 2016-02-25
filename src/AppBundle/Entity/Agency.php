<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Agency
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AgencyRepository")
 */
class Agency extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

	/** @ORM\Column(name="location", type="string", length=255, nullable=true) */
    protected $location;

	/** @ORM\Column(name="picture", type="string", length=255, nullable=true) */
    protected $picture;
	


    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="email_secondary", type="string", length=255)
     */
    private $emailSecondary;

    /**
     * @var phone
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var motto
     *
     * @ORM\Column(name="motto", type="string", length=255)
     */
    private $motto;


	
	/**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="user")
     */
    private $trips;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Relations", mappedBy="user_follower")
     */
    private $followers;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Relations", mappedBy="user_following")
     */
    private $followings;	
	
	// OneToMany relations
    public function __construct()
    {
		// richiamo il costruttore del Fos Bundle per abilitare il popolamento del campo 'salt'
		parent::__construct();
		
        $this->trips = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->followings = new ArrayCollection();
    }
	
	
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return Agency
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return Agency
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Agency
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Agency
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Add trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return Agency
     */
    public function addTrip(\AppBundle\Entity\Trip $trip)
    {
        $this->trips[] = $trip;

        return $this;
    }

    /**
     * Remove trip
     *
     * @param \AppBundle\Entity\Trip $trip
     */
    public function removeTrip(\AppBundle\Entity\Trip $trip)
    {
        $this->trips->removeElement($trip);
    }

    /**
     * Get trips
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * Add follower
     *
     * @param \AppBundle\Entity\Relations $follower
     *
     * @return Agency
     */
    public function addFollower(\AppBundle\Entity\Relations $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \AppBundle\Entity\Relations $follower
     */
    public function removeFollower(\AppBundle\Entity\Relations $follower)
    {
        $this->followers->removeElement($follower);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Add following
     *
     * @param \AppBundle\Entity\Relations $following
     *
     * @return Agency
     */
    public function addFollowing(\AppBundle\Entity\Relations $following)
    {
        $this->followings[] = $following;

        return $this;
    }

    /**
     * Remove following
     *
     * @param \AppBundle\Entity\Relations $following
     */
    public function removeFollowing(\AppBundle\Entity\Relations $following)
    {
        $this->followings->removeElement($following);
    }

    /**
     * Get followings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowings()
    {
        return $this->followings;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Agency
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set emailSecondary
     *
     * @param string $emailSecondary
     *
     * @return Agency
     */
    public function setEmailSecondary($emailSecondary)
    {
        $this->emailSecondary = $emailSecondary;

        return $this;
    }

    /**
     * Get emailSecondary
     *
     * @return string
     */
    public function getEmailSecondary()
    {
        return $this->emailSecondary;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Agency
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set motto
     *
     * @param string $motto
     *
     * @return Agency
     */
    public function setMotto($motto)
    {
        $this->motto = $motto;

        return $this;
    }

    /**
     * Get motto
     *
     * @return string
     */
    public function getMotto()
    {
        return $this->motto;
    }
}
