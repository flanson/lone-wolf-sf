<?php

namespace LoneWolfAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chapter
 *
 * @ORM\Table(name="chapter")
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}

