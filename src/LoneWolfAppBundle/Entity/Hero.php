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
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Story")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=true)
     */
    private $currentStory;

    /**
     * @var int
     *
     * @ORM\Column(name="currentChapter", type="integer", nullable=true)
     */
    private $currentChapter;

    /**
     * @ORM\OneToOne(targetEntity="LoneWolfAppBundle\Entity\Enemy")
     * @ORM\JoinColumn(name="enemy_id", referencedColumnName="id", nullable=true)
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
     * Set currentChapter
     *
     * @param integer $currentChapter
     *
     * @return Hero
     */
    public function setCurrentChapter($currentChapter)
    {
        $this->currentChapter = $currentChapter;

        return $this;
    }

    /**
     * Get currentChapter
     *
     * @return integer
     */
    public function getCurrentChapter()
    {
        return $this->currentChapter;
    }

    /**
     * Set currentStory
     *
     * @param Story $currentStory
     *
     * @return Hero
     */
    public function setCurrentStory(Story $currentStory = null)
    {
        $this->currentStory = $currentStory;

        return $this;
    }

    /**
     * Get currentStory
     *
     * @return Story
     */
    public function getCurrentStory()
    {
        return $this->currentStory;
    }

    /**
     * Set currentEnemy
     *
     * @param Enemy $currentEnemy
     *
     * @return Hero
     */
    public function setCurrentEnemy(Enemy $currentEnemy = null)
    {
        $this->currentEnemy = $currentEnemy;

        return $this;
    }

    /**
     * Get currentEnemy
     *
     * @return Enemy
     */
    public function getCurrentEnemy()
    {
        return $this->currentEnemy;
    }

    public function __toString()
    {
        return strval($this->id);
    }
}
