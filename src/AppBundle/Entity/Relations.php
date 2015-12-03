<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relations
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RelationsRepository")
 */
class Relations
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
	*@ORM\ManyToOne(targetEntity="User", inversedBy="followers")
	*@ORM\JoinColumn(name="follower_id", referencedColumnName="id")
	*/
	private $follwer;
	
	/**
	*@ORM\ManyToOne(targetEntity="User", inversedBy="followings")
	*@ORM\JoinColumn(name="following_id", referencedColumnName="id")
	*/
	private $following;
	

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
     * Set follwer
     *
     * @param \AppBundle\Entity\User $follwer
     *
     * @return Relations
     */
    public function setFollwer(\AppBundle\Entity\User $follwer = null)
    {
        $this->follwer = $follwer;

        return $this;
    }

    /**
     * Get follwer
     *
     * @return \AppBundle\Entity\User
     */
    public function getFollwer()
    {
        return $this->follwer;
    }

    /**
     * Set following
     *
     * @param \AppBundle\Entity\User $following
     *
     * @return Relations
     */
    public function setFollowing(\AppBundle\Entity\User $following = null)
    {
        $this->following = $following;

        return $this;
    }

    /**
     * Get following
     *
     * @return \AppBundle\Entity\User
     */
    public function getFollowing()
    {
        return $this->following;
    }
}
