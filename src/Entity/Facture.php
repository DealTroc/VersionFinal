<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture implements \JsonSerializable
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idFacture ;


 /**
    * @ORM\Column(type="date")
   
    */
    private  $dateFacturation ;




  /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float", message="La commission doit être un nombre décimal")
     * @Assert\NotBlank(message="Le saisie du commission est obligatoire")
     * 
     */
    private $commission ;




 /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le saisie du statut est obligatoire")
     
     */
    private  $statut ;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="factures")
     * * @ORM\JoinColumn(name="commande_id", referencedColumnName="id_commande",nullable=false)
     *
     */
    private $commande;




     /**
     * @ORM\OneToMany(targetEntity=LigneFacture::class, mappedBy="facture", orphanRemoval=true)
     */
    private $lignes;



    public function getIdFacture(): ?int
    {
        return $this->idFacture;
    }

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->dateFacturation;
    }

    public function setDateFacturation(\DateTimeInterface $dateFacturation): self
    {
        $this->dateFacturation = $dateFacturation;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission ;
    }

    public function setCommission(float $commission): self
    {
        $this->commission  = $commission;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }




    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }




    /**
     * 
     * 
     * 
     *  public function getIdCommande(): ?Commande
     * {
     *     return $this->idCommande;
     *  }

     *  public function setIdCommande(?Commande $idCommande): self
     * {
     *    $this->idCommande = $idCommande;

     *    return $this;
     *}

     * 
     * 
     * ***/





        /**
         * @return Collection|LigneFacture[]
         */
        public function getLignes(): Collection
        {
            return $this->lignes;
        }

        public function addLigne(LigneFacture $ligne): self
        {
            if (!$this->lignes->contains($ligne)) {
                $this->lignes[] = $ligne;
                $ligne->setFacture($this);
            }

            return $this;
        }

        public function removeLigne(LigneFacture $ligne): self
        {
            if ($this->lignes->removeElement($ligne)) {
                // set the owning side to null (unless already changed)
                if ($ligne->getFacture() === $this) {
                    $ligne->setFacture(null);
                }
            }
        
            return $this;
        }



    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->idFacture,
            'dateFacturation' => $this->dateFacturation->format("d-m-Y"),
            'commission' => $this->commission,
            'statut' => $this->statut,
            'commande' => $this->commande

        );
    }

    public function constructor($dateFacturation, $commission, $statut, $commande)
    {
        $this->dateFacturation = $dateFacturation;
        $this->commission = $commission;
        $this->statut = $statut;
        $this->commande = $commande;
    }
}
