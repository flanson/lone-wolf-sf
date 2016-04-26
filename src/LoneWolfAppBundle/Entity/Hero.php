<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hero
 *
 * @ORM\Table(name="hero")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\HeroRepository")
 */
class Hero
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="combatSkill", type="integer")
     */
    private $combatSkill;

    /**
     * @var int
     *
     * @ORM\Column(name="enduranceMax", type="integer")
     */
    private $enduranceMax;

    /**
     * @var int
     *
     * @ORM\Column(name="life", type="integer", nullable=true)
     */
    private $life;

    /**
     * @ORM\OneToOne(targetEntity="LoneWolfAppBundle\Entity\Adventure")
     * @ORM\JoinColumn(name="adventure_id", referencedColumnName="id", nullable=true)
     */
    private $currentAdventure;

    /**
     * @ORM\OneToOne(targetEntity="LoneWolfAppBundle\Entity\Combat")
     * @ORM\JoinColumn(name="combat_id", referencedColumnName="id", nullable=true)
     */
    private $currentEnemy;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set combatSkill
     *
     * @param integer $combatSkill
     *
     * @return Hero
     */
    public function setCombatSkill($combatSkill)
    {
        $this->combatSkill = $combatSkill;

        return $this;
    }

    /**
     * Get combatSkill
     *
     * @return int
     */
    public function getCombatSkill()
    {
        return $this->combatSkill;
    }

    /**
     * Set enduranceMax
     *
     * @param integer $enduranceMax
     *
     * @return Hero
     */
    public function setEnduranceMax($enduranceMax)
    {
        $this->enduranceMax = $enduranceMax;

        return $this;
    }

    /**
     * Get enduranceMax
     *
     * @return int
     */
    public function getEnduranceMax()
    {
        return $this->enduranceMax;
    }

    /**
     * Set life
     *
     * @param integer $life
     *
     * @return Hero
     */
    public function setLife($life)
    {
        $this->life = $life;

        return $this;
    }

    /**
     * Get life
     *
     * @return int
     */
    public function getLife()
    {
        return $this->life;
    }

    /**
     * Set currentEnemy
     *
     * @param Combat $currentEnemy
     *
     * @return Hero
     */
    public function setCurrentEnemy(Combat $currentEnemy = null)
    {
        $this->currentEnemy = $currentEnemy;

        return $this;
    }

    /**
     * Get currentEnemy
     *
     * @return Combat
     */
    public function getCurrentEnemy()
    {
        return $this->currentEnemy;
    }

    public function __toString()
    {
        return strval($this->id);
    }

    /**
     * Set currentAdventure
     *
     * @param \LoneWolfAppBundle\Entity\Adventure $currentAdventure
     *
     * @return Hero
     */
    public function setCurrentAdventure(\LoneWolfAppBundle\Entity\Adventure $currentAdventure = null)
    {
        $this->currentAdventure = $currentAdventure;

        return $this;
    }

    /**
     * Get currentAdventure
     *
     * @return \LoneWolfAppBundle\Entity\Adventure
     */
    public function getCurrentAdventure()
    {
        return $this->currentAdventure;
    }

    public function setLifeToMax()
    {
        $this->life = $this->enduranceMax;
    }
}
