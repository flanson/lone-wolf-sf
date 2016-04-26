<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Story
 *
 * @ORM\Table(name="story")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\StoryRepository")
 */
class Story
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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="LoneWolfAppBundle\Entity\Chapter", mappedBy="story")
     */
    private $chapters;

    /**
     * @ORM\ManyToOne(targetEntity="LoneWolfAppBundle\Entity\Campaign", inversedBy="storyList")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $campaign;

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
     * @return Story
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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chapters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active = true;
    }

    /**
     * Add chapter
     *
     * @param \LoneWolfAppBundle\Entity\Chapter $chapter
     *
     * @return Story
     */
    public function addChapter(\LoneWolfAppBundle\Entity\Chapter $chapter)
    {
        $this->chapters[] = $chapter;

        return $this;
    }

    /**
     * Remove chapter
     *
     * @param \LoneWolfAppBundle\Entity\Chapter $chapter
     */
    public function removeChapter(\LoneWolfAppBundle\Entity\Chapter $chapter)
    {
        $this->chapters->removeElement($chapter);
    }

    /**
     * Get chapters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Story
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set campaign
     *
     * @param \LoneWolfAppBundle\Entity\Campaign $campaign
     *
     * @return Story
     */
    public function setCampaign(\LoneWolfAppBundle\Entity\Campaign $campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \LoneWolfAppBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Story
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
