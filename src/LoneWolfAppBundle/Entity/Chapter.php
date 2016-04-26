<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Chapter
 *
 * @ORM\Table(name="chapter")
 * @UniqueEntity(fields={"chapterValue", "story"})
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\ChapterRepository")
 */
class Chapter
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
     * @ORM\Column(name="directions", type="array", nullable=true)
     */
    private $directions;

    /**
     * @ORM\OneToMany(targetEntity="LoneWolfAppBundle\Entity\Enemy", mappedBy="chapter")
     */
    private $enemies;

    /**
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Story", inversedBy="chapters")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $story;

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
     * Constructor
     */
    public function __construct()
    {
        $this->enemies = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set directions
     *
     * @param array $directions
     *
     * @return Chapter
     */
    public function setDirections($directions)
    {
        $this->directions = $directions;

        return $this;
    }

    /**
     * Get directions
     *
     * @return array
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * Add enemy
     *
     * @param \LoneWolfAppBundle\Entity\Enemy $enemy
     *
     * @return Chapter
     */
    public function addEnemy(\LoneWolfAppBundle\Entity\Enemy $enemy)
    {
        $this->enemies[] = $enemy;

        return $this;
    }

    /**
     * Remove enemy
     *
     * @param \LoneWolfAppBundle\Entity\Enemy $enemy
     */
    public function removeEnemy(\LoneWolfAppBundle\Entity\Enemy $enemy)
    {
        $this->enemies->removeElement($enemy);
    }

    /**
     * Get enemies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnemies()
    {
        return $this->enemies;
    }

    /**
     * Set story
     *
     * @param \LoneWolfAppBundle\Entity\Story $story
     *
     * @return Chapter
     */
    public function setStory(\LoneWolfAppBundle\Entity\Story $story)
    {
        $this->story = $story;

        return $this;
    }

    /**
     * Get story
     *
     * @return \LoneWolfAppBundle\Entity\Story
     */
    public function getStory()
    {
        return $this->story;
    }

    public function __toString()
    {
        return strval($this->chapterValue);
    }
}
