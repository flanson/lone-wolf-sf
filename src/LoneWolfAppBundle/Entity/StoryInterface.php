<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 23/03/2016
 * Time: 00:18
 */
namespace LoneWolfAppBundle\Entity;


/**
 * Story
 *
 * @ORM\Table(name="story")
 * @ORM\Entity(repositoryClass="LoneWolfAppBundle\Repository\StoryRepository")
 */
interface StoryInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Story
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();
}