<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enemy
 *
 * @ORM\Table(name="enemy")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\EnemyRepository")
 */
class Enemy
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Chapter", inversedBy="enemies")
     * @ORM\JoinColumn(name="chapter_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $chapter;

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
     * Set name
     *
     * @param string $name
     *
     * @return Enemy
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
     * Set combatSkill
     *
     * @param integer $combatSkill
     *
     * @return Enemy
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
     * @return Enemy
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

    public function __toString()
    {
        return $this->name . ' E:' . strval($this->enduranceMax) . ' H:' . strval($this->combatSkill);
    }

    /**
     * Set chapter
     *
     * @param \LoneWolfAppBundle\Entity\Chapter $chapter
     *
     * @return Enemy
     */
    public function setChapter(\LoneWolfAppBundle\Entity\Chapter $chapter)
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * Get chapter
     *
     * @return \LoneWolfAppBundle\Entity\Chapter
     */
    public function getChapter()
    {
        return $this->chapter;
    }
}
