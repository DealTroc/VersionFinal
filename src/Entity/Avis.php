<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="avis")
 * @ORM\Entity
 */
class Avis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_avis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAvis;

    /**
     * @var string
     *
     * @ORM\Column(name="etoile", type="string", length=20, nullable=false)
     */
    private $etoile;

    /**
     * @var string
     *
     * @ORM\Column(name="raison", type="string", length=20, nullable=false)
     */
    private $raison;

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function getEtoile(): ?string
    {
        return $this->etoile;
    }

    public function setEtoile(string $etoile): self
    {
        $this->etoile = $etoile;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }


}
