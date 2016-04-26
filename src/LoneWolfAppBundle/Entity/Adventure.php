<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adventure
 *
 * @ORM\Table(name="adventure")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\AdventureRepository")
 */
class Adventure
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
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Story")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $story;

    /**
     * @ORM\OneToMany(targetEntity="LoneWolfAppBundle\Entity\Etape", mappedBy="adventure")
     */
    private $etapes;

    /**
     * @ORM\OneToOne(targetEntity="LoneWolfAppBundle\Entity\Etape")
     * @ORM\JoinColumn(name="etape_id", referencedColumnName="id", nullable=true)
     */
    private $lastEtape;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->story->getName();
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etapes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set story
     *
     * @param \LoneWolfAppBundle\Entity\Story $story
     *
     * @return Adventure
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

    /**
     * Add etape
     *
     * @param \LoneWolfAppBundle\Entity\Etape $etape
     *
     * @return Adventure
     */
    public function addEtape(\LoneWolfAppBundle\Entity\Etape $etape)
    {
        $this->etapes[] = $etape;
        $this->setLastEtape($etape);

        return $this;
    }

    /**
     * Remove etape
     *
     * @param \LoneWolfAppBundle\Entity\Etape $etape
     */
    public function removeEtape(\LoneWolfAppBundle\Entity\Etape $etape)
    {
        $this->etapes->removeElement($etape);
    }

    /**
     * Get etapes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

    /**
     * Set lastEtape
     *
     * @param \LoneWolfAppBundle\Entity\Etape $lastEtape
     *
     * @return Adventure
     */
    public function setLastEtape(\LoneWolfAppBundle\Entity\Etape $lastEtape = null)
    {
        $this->lastEtape = $lastEtape;

        return $this;
    }

    /**
     * Get lastEtape
     *
     * @return \LoneWolfAppBundle\Entity\Etape
     */
    public function getLastEtape()
    {
        return $this->lastEtape;
    }
}
