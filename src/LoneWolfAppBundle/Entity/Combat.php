<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Combat
 *
 * @ORM\Table(name="combat")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\CombatRepository")
 */
class Combat
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
     * @var int
     *
     * @ORM\Column(name="life", type="integer", nullable=true)
     */
    private $life;

    /**
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Enemy")
     * @ORM\JoinColumn(name="enemy_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $enemy;

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

    /**
     * Set life
     *
     * @param integer $life
     *
     * @return Enemy
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

    public function __toString()
    {
        return $this->name . ' E:' . strval($this->life) . '/' . strval($this->enduranceMax) . ' H:' . strval($this->combatSkill);
    }

    /**
     * Set enemy
     *
     * @param \LoneWolfAppBundle\Entity\Enemy $enemy
     *
     * @return Combat
     */
    public function setEnemy(\LoneWolfAppBundle\Entity\Enemy $enemy)
    {
        $this->enemy = $enemy;
        $this->setName($enemy->getName());
        $this->setCombatSkill($enemy->getCombatSkill());
        $this->setEnduranceMax($enemy->getEnduranceMax());
        $this->setLife($enemy->getEnduranceMax());

        return $this;
    }

    /**
     * Get enemy
     *
     * @return \LoneWolfAppBundle\Entity\Enemy
     */
    public function getEnemy()
    {
        return $this->enemy;
    }

    public function setLifeToMax()
    {
        $this->life = $this->enduranceMax;
    }
}
