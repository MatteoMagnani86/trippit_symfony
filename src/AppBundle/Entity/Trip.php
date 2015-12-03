<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trip
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TripRepository")
 */
class Trip
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
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime")
     */
    private $insertDate;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var boolean
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    private $archived;

	
	/**
	*@ORM\ManyToOne(targetEntity="User", inversedBy="trips")
	*@ORM\JoinColumn(name="user_id", referencedColumnName="id")
	*/
	private $user;
	
	/**
	*@ORM\ManyToOne(targetEntity="Destination", inversedBy="trips")
	*@ORM\JoinColumn(name="destination_id", referencedColumnName="id")
	*/
	private $destination;
	
	/**
	*@ORM\ManyToOne(targetEntity="Period", inversedBy="trips")
	*@ORM\JoinColumn(name="period_id", referencedColumnName="id")
	*/
	private $period;
	
	/**
	* @ORM\OneToMany(targetEntity="Advice", mappedBy="trip")
	*/
	private $advices;
	
	// OneToMany relations
	public function __construct()
	{
		$this->advices = new ArrayCollection();
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
     * Set data
     *
     * @param \DateTime $data
     *
     * @return Trip
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Trip
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
     * Set archived
     *
     * @param boolean $archived
     *
     * @return Trip
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return Trip
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Trip
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set destination
     *
     * @param \AppBundle\Entity\Destination $destination
     *
     * @return Trip
     */
    public function setDestination(\AppBundle\Entity\Destination $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \AppBundle\Entity\Destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set period
     *
     * @param \AppBundle\Entity\Period $period
     *
     * @return Trip
     */
    public function setPeriod(\AppBundle\Entity\Period $period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \AppBundle\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Add advice
     *
     * @param \AppBundle\Entity\Advice $advice
     *
     * @return Trip
     */
    public function addAdvice(\AppBundle\Entity\Advice $advice)
    {
        $this->advices[] = $advice;

        return $this;
    }

    /**
     * Remove advice
     *
     * @param \AppBundle\Entity\Advice $advice
     */
    public function removeAdvice(\AppBundle\Entity\Advice $advice)
    {
        $this->advices->removeElement($advice);
    }

    /**
     * Get advices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvices()
    {
        return $this->advices;
    }
}
