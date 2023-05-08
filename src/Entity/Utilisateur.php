<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity
 */
class Utilisateur implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_utilisateur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUtilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=50, nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="string", length=500, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="motDepasse", type="string", length=8, nullable=false)
     */
    private $motdepasse;

    /**
     * @var string
     *
     * @ORM\Column(name="AdresseEmail", type="string", length=500, nullable=false)
     */
    private $adresseemail;

    /**
     * @var string
     *
     * @ORM\Column(name="Image", type="text", length=65535, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombreProduitAchetes", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $nombreproduitachetes = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombreProduitPublier", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $nombreproduitpublier = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombreProduitVendus", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $nombreproduitvendus = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="avis", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $avis = NULL;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     */
    private $role;

   



    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): self
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }

    public function getAdresseemail(): ?string
    {
        return $this->adresseemail;
    }

    public function setAdresseemail(string $adresseemail): self
    {
        $this->adresseemail = $adresseemail;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNombreproduitachetes(): ?int
    {
        return $this->nombreproduitachetes;
    }

    public function setNombreproduitachetes(?int $nombreproduitachetes): self
    {
        $this->nombreproduitachetes = $nombreproduitachetes;

        return $this;
    }

    public function getNombreproduitpublier(): ?int
    {
        return $this->nombreproduitpublier;
    }

    public function setNombreproduitpublier(?int $nombreproduitpublier): self
    {
        $this->nombreproduitpublier = $nombreproduitpublier;

        return $this;
    }

    public function getNombreproduitvendus(): ?int
    {
        return $this->nombreproduitvendus;
    }

    public function setNombreproduitvendus(?int $nombreproduitvendus): self
    {
        $this->nombreproduitvendus = $nombreproduitvendus;

        return $this;
    }

    public function getAvis(): ?int
    {
        return $this->avis;
    }

    public function setAvis(?int $avis): self
    {
        $this->avis = $avis;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->idUtilisateur,
            'email' => $this->adresseemail
        );
    }

    public function constructor($email)
    {
        $this->adresseemail = $email;
    }
}
