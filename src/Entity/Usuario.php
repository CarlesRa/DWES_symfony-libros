<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull(message="El Nombre de usuario es obligatorio")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull(message="El password es obligatorio")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull(message="El email es obligatorio")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull(message="El rol es obligatorio")
     * @Assert\Choice({"ROLE_USER", "ROLE_ADMIN"}, message="Roles permitidos: ROLE_ADMIN, ROLE_USER")
     */
    private $rol;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }


    public function serialize()
    {
        return serialize(array($this->id, $this->login, $this->password));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->login, $this->password) =
            unserialize($serialized, array('allowed_classes' => false));
    }

    public function getRoles()
    {
        return array($this->rol);
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return$this->login;
    }

    public function eraseCredentials()
    {
        
    }
}
