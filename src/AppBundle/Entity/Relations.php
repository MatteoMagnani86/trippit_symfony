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
	* @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
	* @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
	*/
	private $user_follower;
	
	/**
	* @ORM\ManyToOne(targetEntity="User", inversedBy="followings")
	* @ORM\JoinColumn(name="following_id", referencedColumnName="id")
	*/
	private $user_following;
	

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
     * Set userFollower
     *
     * @param \AppBundle\Entity\User $userFollower
     *
     * @return Relations
     */
    public function setUserFollower(\AppBundle\Entity\User $userFollower = null)
    {
        $this->user_follower = $userFollower;

        return $this;
    }

    /**
     * Get userFollower
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserFollower()
    {
        return $this->user_follower;
    }

    /**
     * Set userFollowing
     *
     * @param \AppBundle\Entity\User $userFollowing
     *
     * @return Relations
     */
    public function setUserFollowing(\AppBundle\Entity\User $userFollowing = null)
    {
        $this->user_following = $userFollowing;

        return $this;
    }

    /**
     * Get userFollowing
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserFollowing()
    {
        return $this->user_following;
    }
}