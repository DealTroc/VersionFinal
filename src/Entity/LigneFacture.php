<?php

namespace App\Entity;
use App\Repository\LigneFactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=LigneFactureRepository::class)
 */
class LigneFacture implements \JsonSerializable
{

     
   /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
     private  $id;
    

     /**
    * @ORM\Column(type="float")
     */
    private  $prixInitial;

  /**
     * @ORM\Column(type="float", options={"default": 0})
     * @Assert\Type(type="float", message="Le prix doit être un nombre décimal")
     * @Assert\NotBlank(message="Le saisie du prix de vente est obligatoire")
     * 
     */
    private $prixVente;
   /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float", message="Le prix doit être un nombre décimal")
     * @Assert\NotBlank(message="Le saisie du prix de livraison est obligatoire")
     * 
     */
    private  $prixLivraison;


   /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float", message="Le prix doit être un nombre décimal")
     * @Assert\NotBlank(message="Le saisie du prix total est obligatoire")
     * 
     */
    private  $prixTotal;
   

   /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float", message="Le revenu doit être un nombre décimal")
     * @Assert\NotBlank(message="Le saisie du revenu est obligatoire")
     *  
     */
    private  $revenu;
    

    

       /**
     * @ORM\ManyToOne(targetEntity=Facture::class, inversedBy="lignes")
     * * @ORM\JoinColumn(name="id_facture", referencedColumnName="id_facture",nullable=false)
     *
     */
    private $facture;
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixInitial(): ?float
    {
        return $this->prixInitial;
    }

    public function setPrixInitial(float $prixInitial): self
    {
        $this->prixInitial = $prixInitial;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getPrixLivraison(): ?float
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(float $prixLivraison): self
    {
        $this->prixLivraison = $prixLivraison;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getRevenu(): ?float
    {
        return $this->revenu;
    }

    public function setRevenu(float $revenu): self
    {
        $this->revenu = $revenu;

        return $this;
    }






    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture ;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'facture' => $this->facture,
            'prixInitial' => $this->prixInitial,
            'prixVente' => $this->prixVente,
            'prixLivraison' => $this->prixLivraison,
            'prixTotal' => $this->prixTotal,
            'revenu' => $this->revenu

        );
    }

    public function constructor($facture, $prixInitial, $prixVente, $prixLivraison, $prixTotal, $revenu)
    {
        $this->facture = $facture;
        $this->prixInitial = $prixInitial;
        $this->prixVente = $prixVente;
        $this->prixLivraison = $prixLivraison;
        $this->prixTotal = $prixTotal;
        $this->revenu = $revenu;

    }
}
