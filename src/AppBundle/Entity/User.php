<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @var string
     *
     * @ORM\Column(name="id_facebook", type="string", length=50)
     */
    private $idFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50)
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth", type="date")
     */
    private $birth;

    /**
     * @var string
     *
     * @ORM\Column(name="first_mail", type="string", length=50)
     */
    private $firstMail;

    /**
     * @var string
     *
     * @ORM\Column(name="second_mail", type="string", length=50)
     */
    private $secondMail;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=50)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="slogan", type="string", length=255)
     */
    private $slogan;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var boolean
     *
     * @ORM\Column(name="data_privacy", type="boolean")
     */
    private $dataPrivacy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="trip_privacy", type="boolean")
     */
    private $tripPrivacy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="advice_privacy", type="boolean")
     */
    private $advicePrivacy;
	
	
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
     * Set name
     *
     * @param string $name
     *
     * @return user
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return user
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set birth
     *
     * @param \DateTime $birth
     *
     * @return user
     */
    public function setBirth($birth)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth
     *
     * @return \DateTime
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set firstMail
     *
     * @param string $firstMail
     *
     * @return user
     */
    public function setFirstMail($firstMail)
    {
        $this->firstMail = $firstMail;

        return $this;
    }

    /**
     * Get firstMail
     *
     * @return string
     */
    public function getFirstMail()
    {
        return $this->firstMail;
    }

    /**
     * Set secondMail
     *
     * @param string $secondMail
     *
     * @return user
     */
    public function setSecondMail($secondMail)
    {
        $this->secondMail = $secondMail;

        return $this;
    }

    /**
     * Get secondMail
     *
     * @return string
     */
    public function getSecondMail()
    {
        return $this->secondMail;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return user
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return user
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
     * Set city
     *
     * @param string $city
     *
     * @return user
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     *
     * @return user
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return user
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
     * Set dataPrivacy
     *
     * @param boolean $dataPrivacy
     *
     * @return user
     */
    public function setDataPrivacy($dataPrivacy)
    {
        $this->dataPrivacy = $dataPrivacy;

        return $this;
    }

    /**
     * Get dataPrivacy
     *
     * @return boolean
     */
    public function getDataPrivacy()
    {
        return $this->dataPrivacy;
    }

    /**
     * Set advicePrivacy
     *
     * @param boolean $advicePrivacy
     *
     * @return user
     */
    public function setAdvicePrivacy($advicePrivacy)
    {
        $this->advicePrivacy = $advicePrivacy;

        return $this;
    }

    /**
     * Get advicePrivacy
     *
     * @return boolean
     */
    public function getAdvicePrivacy()
    {
        return $this->advicePrivacy;
    }

    /**
     * Set tripPrivacy
     *
     * @param boolean $tripPrivacy
     *
     * @return User
     */
    public function setTripPrivacy($tripPrivacy)
    {
        $this->tripPrivacy = $tripPrivacy;

        return $this;
    }

    /**
     * Get tripPrivacy
     *
     * @return boolean
     */
    public function getTripPrivacy()
    {
        return $this->tripPrivacy;
    }

    /**
     * Add trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return User
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
     * @return User
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
     * @return User
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
     * Set idFacebook
     *
     * @param string $idFacebook
     *
     * @return User
     */
    public function setIdFacebook($idFacebook)
    {
        $this->idFacebook = $idFacebook;

        return $this;
    }

    /**
     * Get idFacebook
     *
     * @return string
     */
    public function getIdFacebook()
    {
        return $this->idFacebook;
    }
}