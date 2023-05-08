<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreurRepository;

/**
 *@ORM\Entity(repositoryClass=LivreurRepository::class)
 */

 
class Livreur implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livreur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivreur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="num", type="string", length=20, nullable=false)
     */
    private $num;

    public function getIdLivreur(): ?int
    {
        return $this->idLivreur;
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

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->idLivreur,
            'nom' => $this->nom,
            'num' => $this->num

        );
    }

    public function constructor($nom, $num)
    {
        $this->nom = $nom;
        $this->num = $num;

    }
}
