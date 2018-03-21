<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\Column(type="simple_array")
     */
    protected $tags;

    public function getId()
    {
      return $this->id;
    }

    public function getUser()
    {
      return $this->user;
    }

    public function setUser($user)
    {
      $this->user = $user;
    }

    public function getTitle()
    {
      return $this->title;
    }

    public function setTitle($title)
    {
      $this->title = $title;
    }

    public function getBody()
    {
      return $this->body;
    }

    public function setBody($body)
    {
      $this->body = $body;
    }

    public function getTags()
    {
      return $this->tags;
    }

    public function setTags($tags)
    {
      $this->tags = $tags;
    }

    public function __toString()
    {
      return (string) $this->getTitle();
    }
}
