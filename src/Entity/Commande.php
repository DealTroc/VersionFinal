<?php

namespace App\Entity;
use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande implements \JsonSerializable
{
      /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $idCommande;

     /**
    * @ORM\Column(type="integer")
     */
    private $idUtilisateur = NULL;

   /**
    * @ORM\Column(type="integer")
     */
    private $idProduit = NULL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=true, options={"default"="NULL"})
     * @Assert\NotBlank(message="le choix du role est obligatoire")
     */
    private $role = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $status = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_livreur", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idLivreur = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateurA", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idUtilisateura = NULL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_livraison", type="date", nullable=false)
     * @Assert\NotBlank(message="le choix de la date de livraison est obligatoire")
     */
   
     private $dateLivraison ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_confirmation", type="date", nullable=false)
     */
    private $dateConfirmation ;



  /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="commande", orphanRemoval=true)
     */
    private $factures;




  



    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(?int $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdLivreur(): ?int
    {
        return $this->idLivreur;
    }

    public function setIdLivreur(?int $idLivreur): self
    {
        $this->idLivreur = $idLivreur;

        return $this;
    }

    public function getIdUtilisateura(): ?int
    {
        return $this->idUtilisateura;
    }

    public function setIdUtilisateura(?int $idUtilisateura): self
    {
        $this->idUtilisateura = $idUtilisateura;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getDateConfirmation(): ?\DateTimeInterface
    {
        return $this->dateConfirmation;
    }

    public function setDateConfirmation(?\DateTimeInterface $dateConfirmation): self
    {
        $this->dateConfirmation = $dateConfirmation;

        return $this;
    }



    /**
     * @return Collection|Facture[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getCommande() === $this) {
                $facture->setCommande(null);
            }
        }

        return $this;
    }




    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->idCommande,
            'utilisateur' => $this->idUtilisateur,
            'produit' => $this->idProduit,
            'date' => $this->date->format("d-m-Y"),
            'role' => $this->role,
            'status' => $this->status,
            'livreur' => $this->idLivreur,
            'dateLivraison' => $this->dateLivraison->format("d-m-Y"),
            'dateConfirmation' => $this->dateConfirmation->format("d-m-Y")
        );
    }

    public function constructor($utilisateur, $produit, $date, $role, $status, $livreur, $dateLivraison, $dateConfirmation)
    {
        $this->idUtilisateur = $utilisateur->getIdUtilisateur();
        $this->idProduit = $produit->getIdproduit();
        $this->date = $date;
        $this->role = $role;
        $this->status = $status;
        $this->idLivreur = $livreur->getIdLivreur();
        $this->dateLivraison = $dateLivraison;
        $this->dateConfirmation = $dateConfirmation;
    }
    
}
