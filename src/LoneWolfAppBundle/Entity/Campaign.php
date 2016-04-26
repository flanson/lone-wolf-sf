<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Story
 *
 * @ORM\Table(name="campaign")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\CampaignRepository")
 */
class Campaign
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
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="LoneWolfAppBundle\Entity\Story", mappedBy="campaign")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $storyList;

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
        $this->storyList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add storyList
     *
     * @param \LoneWolfAppBundle\Entity\Story $storyList
     *
     * @return Campaign
     */
    public function addStoryList(\LoneWolfAppBundle\Entity\Story $storyList)
    {
        $this->storyList[] = $storyList;

        return $this;
    }

    /**
     * Remove storyList
     *
     * @param \LoneWolfAppBundle\Entity\Story $storyList
     */
    public function removeStoryList(\LoneWolfAppBundle\Entity\Story $storyList)
    {
        $this->storyList->removeElement($storyList);
    }

    /**
     * Get storyList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStoryList()
    {
        return $this->storyList;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Campaign
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
