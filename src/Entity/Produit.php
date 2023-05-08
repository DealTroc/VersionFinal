<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="ssss", columns={"Id_utilisateur"})})
 * @ORM\Entity
 */
class Produit implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idproduit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", length=65535, nullable=true, options={"default"="NULL"})
     * @Assert\NotBlank(message="veuillez ajouter l'image de l'article")
     */
    private $image = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="veuillez ajouter une desription de l'article")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=20, nullable=false)
     */
    private $categorie;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

   

    /**
     * @var string
     *
     * @ORM\Column(name="id_utilisateur", type="string", length=10, nullable=false)
     */
    private $idUtilisateur=16;


 
    private $discount;



    public function getIdproduit(): ?int
    {
        return $this->idproduit;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
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

    

    public function getIdUtilisateur(): ?string
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(string $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }
    public function getDiscount(): ?int
    {
        return $this->discount;
    }
    
    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;
    
        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->idproduit,
            'image' => $this->image,
            'description' => $this->description,
            'titre' => $this->titre,
            'categorie' => $this->categorie,
            'prix' => (String) $this->prix,
            'utilisateur' => $this->idUtilisateur

        );
    }

    public function constructor($image, $description, $titre, $categorie, $prix, $utilisateur)
    {
        $this->image = $image;
        $this->description = $description;
        $this->titre = $titre;
        $this->categorie = $categorie;
        $this->prix = $prix;
        $this->idUtilisateur = $utilisateur->getIdUtilisateur();
    }
}
