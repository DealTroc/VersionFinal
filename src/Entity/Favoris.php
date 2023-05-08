<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Favoris
 *
 * @ORM\Table(name="favoris", indexes={@ORM\Index(name="AFavoris", columns={"Fimage"})})
 * @ORM\Entity
 */
class Favoris
{
    /**
     * @var int
     *
     * @ORM\Column(name="FIdProduit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fidproduit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Fimage", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $fimage = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="Furl", type="text", length=65535, nullable=false)
     */
    private $furl;

    /**
     * @var string
     *
     * @ORM\Column(name="Fdescription", type="text", length=65535, nullable=false)
     */
    private $fdescription;

    /**
     * @var string
     *
     * @ORM\Column(name="Ftitre", type="string", length=30, nullable=false)
     */
    private $ftitre;

    /**
     * @var string
     *
     * @ORM\Column(name="Fcategorie", type="string", length=30, nullable=false)
     */
    private $fcategorie;

    /**
     * @var int
     *
     * @ORM\Column(name="Fprix", type="integer", nullable=false)
     */
    private $fprix;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Flikes", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $flikes = NULL;

    /**
     * @var int
     *
     * @ORM\Column(name="Id_utilisateur", type="integer", nullable=false)
     */
    private $idUtilisateur;

    public function getFidproduit(): ?int
    {
        return $this->fidproduit;
    }

    public function getFimage(): ?string
    {
        return $this->fimage;
    }

    public function setFimage(?string $fimage): self
    {
        $this->fimage = $fimage;

        return $this;
    }

    public function getFurl(): ?string
    {
        return $this->furl;
    }

    public function setFurl(string $furl): self
    {
        $this->furl = $furl;

        return $this;
    }

    public function getFdescription(): ?string
    {
        return $this->fdescription;
    }

    public function setFdescription(string $fdescription): self
    {
        $this->fdescription = $fdescription;

        return $this;
    }

    public function getFtitre(): ?string
    {
        return $this->ftitre;
    }

    public function setFtitre(string $ftitre): self
    {
        $this->ftitre = $ftitre;

        return $this;
    }

    public function getFcategorie(): ?string
    {
        return $this->fcategorie;
    }

    public function setFcategorie(string $fcategorie): self
    {
        $this->fcategorie = $fcategorie;

        return $this;
    }

    public function getFprix(): ?int
    {
        return $this->fprix;
    }

    public function setFprix(int $fprix): self
    {
        $this->fprix = $fprix;

        return $this;
    }

    public function getFlikes(): ?int
    {
        return $this->flikes;
    }

    public function setFlikes(?int $flikes): self
    {
        $this->flikes = $flikes;

        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }


}
