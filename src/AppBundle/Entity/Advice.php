<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advice
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AdviceRepository")
 */
class Advice
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=50)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime")
     */
    private $insertDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_like", type="boolean")
     */
    private $userLike;

    /**
     * @var boolean
     *
     * @ORM\Column(name="agency_like", type="boolean")
     */
    private $agencyLike;

	
	/**
	*@ORM\ManyToOne(targetEntity="Trip", inversedBy="advices")
	*@ORM\JoinColumn(name="trip_id", referencedColumnName="id")
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
     * Set description
     *
     * @param string $description
     *
     * @return Advice
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advice
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return Advice
     */
    public function setInsertDate($insertDate)
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    /**
     * Get insertDate
     *
     * @return \DateTime
     */
    public function getInsertDate()
    {
        return $this->insertDate;
    }

    /**
     * Set userLike
     *
     * @param boolean $userLike
     *
     * @return Advice
     */
    public function setUserLike($userLike)
    {
        $this->userLike = $userLike;

        return $this;
    }

    /**
     * Get userLike
     *
     * @return boolean
     */
    public function getUserLike()
    {
        return $this->userLike;
    }

    /**
     * Set agencyLike
     *
     * @param boolean $agencyLike
     *
     * @return Advice
     */
    public function setAgencyLike($agencyLike)
    {
        $this->agencyLike = $agencyLike;

        return $this;
    }

    /**
     * Get agencyLike
     *
     * @return boolean
     */
    public function getAgencyLike()
    {
        return $this->agencyLike;
    }

    /**
     * Set trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return Advice
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
}
