<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 13/03/2016
 * Time: 20:26
 */

namespace LoneWolfAppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="LoneWolfAppBundle\Entity\Hero")
     * @ORM\JoinColumn(name="hero_id", referencedColumnName="id")
     */
    private $hero;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set hero
     *
     * @param Hero $hero
     *
     * @return User
     */
    public function setHero(Hero $hero = null)
    {
        $this->hero = $hero;

        return $this;
    }

    /**
     * Get hero
     *
     * @return Hero
     */
    public function getHero()
    {
        return $this->hero;
    }
}
