<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 *`@Vich\Uploadable
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="produit", cascade={"all"})
     */
    private $images;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"all"})
     * @ORM\JoinColumn(name="image_principale_id", nullable=false)
     */
    private $imagePrincipale;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="produits")
     */
    private $categorieProduit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CommandeOrder", mappedBy="produit")
     */
    private $commandeOrders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Panier", mappedBy="articles")
     */
    private $paniers;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->commandeOrders = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function __toString() {
        return (string) "Article";
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCategorieProduit(): ?Categorie
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(?Categorie $categorieProduit): self
    {
        $this->categorieProduit = $categorieProduit;

        return $this;
    }

    /**
     * @return Collection|CommandeOrder[]
     */
    public function getCommandeOrders(): Collection
    {
        return $this->commandeOrders;
    }

    public function addCommandeOrder(CommandeOrder $commandeOrder): self
    {
        if (!$this->commandeOrders->contains($commandeOrder)) {
            $this->commandeOrders[] = $commandeOrder;
            $commandeOrder->addProduit($this);
        }

        return $this;
    }

    public function removeCommandeOrder(CommandeOrder $commandeOrder): self
    {
        if ($this->commandeOrders->contains($commandeOrder)) {
            $this->commandeOrders->removeElement($commandeOrder);
            $commandeOrder->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->addArticle($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->contains($panier)) {
            $this->paniers->removeElement($panier);
            $panier->removeArticle($this);
        }

        return $this;
    }



    public function getImagePrincipale(): ?Image
    {
        return $this->imagePrincipale;
    }

    public function setImagePrincipale(?Image $imagePrincipale): self
    {
        $this->imagePrincipale = $imagePrincipale;

        return $this;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

  
}
