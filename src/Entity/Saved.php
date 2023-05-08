<?php

namespace App\Entity;

use App\Repository\SavedRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SavedRepository::class)
 */
class Saved implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le saisie de l'image est obligatoire")
     */

    private $image;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le saisie de description est obligatoire")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le saisie de titre est obligatoire")
     */
    private ?string $titre = null;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le saisie de catégorie est obligatoire")
     */
    private ?string $categorie = null;


    /**
     * @ORM\Column(type="integer")
     */

    private ?int $prix = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'image' => $this->image,
            'description' => $this->description,
            'titre' => $this->titre,
            'categorie' => $this->categorie,
            'prix' => (string)$this->prix

        );
    }

    public function constructor($image, $description, $titre, $categorie, $prix)
    {
        $this->image = $image;
        $this->description = $description;
        $this->titre = $titre;
        $this->categorie = $categorie;
        $this->prix = $prix;
    }

}
