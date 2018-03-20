<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user")
     */
    protected $notes;

    /**
     * Get the value of Id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNotes()
    {
      return $this->notes;
    }
}
