<?php

namespace App\Entity;

use App\Repository\LibroRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LibroRepository::class)
 * @UniqueEntity("isbn", message="El isbn ya existe")
 */
class Libro
{

    /**
     * @var string $isbn
     * @Assert\NotNull(message="El isbn es obligatorio")
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private $isbn;

    /**
     * @Assert\NotNull(message="El título es obligatorio")
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @Assert\NotNull(message="El autor es obligatorio")
     * @ORM\Column(type="string", length=100)
     */
    private $autor;

    /**
     * @Assert\NotNull(message="El campo páginas es obligatorio")
     * @Assert\Range(
     *  min = 100,
     *  minMessage = "El número mínimo de páginas es 100 "
     * )
     * @ORM\Column(type="integer")
     */
    private $paginas;

    /**
     * @Assert\NotNull(message="El campo editorial es obligatorio")
     * @ORM\ManyToOne(targetEntity=Editorial::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $editorial;

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getPaginas(): ?int
    {
        return $this->paginas;
    }

    public function setPaginas(int $paginas): self
    {
        $this->paginas = $paginas;

        return $this;
    }

    public function getEditorial(): ?Editorial
    {
        return $this->editorial;
    }

    public function setEditorial(?Editorial $editorial): self
    {
        $this->editorial = $editorial;

        return $this;
    }
}
