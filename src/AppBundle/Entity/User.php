<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("user")
 *
 * @ORM\Entity
 *
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     *
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     *
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function eraseCredentials()
    {
    }
}
