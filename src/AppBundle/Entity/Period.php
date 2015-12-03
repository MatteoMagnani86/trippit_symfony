<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Period
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PeriodRepository")
 */
class Period
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
     * @ORM\Column(name="description", type="string", length=50)
     */
    private $description;

	
	/**
	* @ORM\OneToMany(targetEntity="Trip", mappedBy="period")
	*/
	private $trips;
	
	// OneToMany relations
	public function __construct()
	{
		$this->trips = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     *
     * @return Period
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
     * Add trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return Period
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
}
