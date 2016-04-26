<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Etape
 *
 * @ORM\Table(name="etape")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\EtapeRepository")
 */
class Etape
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
     * @var integer
     *
     * @ORM\Column(name="chapterValue", type="integer", nullable=false)
     */
    private $chapterValue;

    /**
     * @ORM\Column(name="enemies_defeated", type="array", nullable=true)
     */
    private $enemiesDefeated;

    /**
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Adventure", inversedBy="etapes")
     * @ORM\JoinColumn(name="adventure_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $adventure;

    /**
     * Etape constructor.
     */
    public function __construct()
    {
        $this->enemiesDefeated = [];
    }

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
     * Set chapterValue
     *
     * @param integer $chapterValue
     *
     * @return Chapter
     */
    public function setChapterValue($chapterValue)
    {
        $this->chapterValue = $chapterValue;

        return $this;
    }

    /**
     * Get chapterValue
     *
     * @return integer
     */
    public function getChapterValue()
    {
        return $this->chapterValue;
    }

    public function __toString()
    {
        return strval($this->chapterValue);
    }

    /**
     * Set enemiesDefeated
     *
     * @param array $enemiesDefeated
     *
     * @return Etape
     */
    public function setEnemiesDefeated($enemiesDefeated)
    {
        $this->enemiesDefeated = $enemiesDefeated;

        return $this;
    }

    /**
     * Set enemiesDefeated
     *
     * @param Enemy $enemy
     * @return Etape
     *
     */
    public function addEnemyDefeated(Enemy $enemy)
    {
        $this->enemiesDefeated[] = $enemy->getId();

        return $this;
    }

    /**
     * Get enemiesDefeated
     *
     * @return array
     */
    public function getEnemiesDefeated()
    {
        return $this->enemiesDefeated;
    }

    /**
     * Set adventure
     *
     * @param \LoneWolfAppBundle\Entity\Adventure $adventure
     *
     * @return Etape
     */
    public function setAdventure(\LoneWolfAppBundle\Entity\Adventure $adventure)
    {
        $this->adventure = $adventure;

        return $this;
    }

    /**
     * Get adventure
     *
     * @return \LoneWolfAppBundle\Entity\Adventure
     */
    public function getAdventure()
    {
        return $this->adventure;
    }
}
