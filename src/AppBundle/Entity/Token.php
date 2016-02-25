<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Token
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TokenRepository")
 */
class Token
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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="burning_date", type="date")
     */
    private $burningDate;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="burned", type="boolean")
     */
    private $burned;	

    /**
	 * @ORM\ManyToOne(targetEntity="Trip", inversedBy="tokens")
	 * @ORM\JoinColumn(name="trip_id", referencedColumnName="id")
	 */
	private $trip;

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
     * Set token
     *
     * @param integer $token
     *
     * @return Token
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return integer
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set burningDate
     *
     * @param \DateTime $burningDate
     *
     * @return Token
     */
    public function setBurningDate($burningDate)
    {
        $this->burningDate = $burningDate;

        return $this;
    }

    /**
     * Get burningDate
     *
     * @return \DateTime
     */
    public function getBurningDate()
    {
        return $this->burningDate;
    }

    /**
     * Set trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return Token
     */
    public function setTrip(\AppBundle\Entity\Trip $trip = null)
    {
        $this->trip = $trip;

        return $this;
    }

    /**
     * Get trip
     *
     * @return \AppBundle\Entity\Trip
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * Set burned
     *
     * @param boolean $burned
     *
     * @return Token
     */
    public function setBurned($burned)
    {
        $this->burned = $burned;

        return $this;
    }

    /**
     * Get burned
     *
     * @return boolean
     */
    public function getBurned()
    {
        return $this->burned;
    }
}
