<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 * @UniqueEntity(fields={"url"}, groups={"editProduct"}, repositoryMethod="checkIfProductUrlExist" ,message="Product URL must be unique!")
 */
class Products {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $url;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $old_price;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $rating;

    /**
     * @ORM\Column(type="integer")
     */
    private $votes;

    /**
     * @ORM\Column(type="smallint")
     */
    private $on_stock = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="smallint")
     */
    private $promoted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedProducts", mappedBy="product")
     */
    private $orderedProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="product")
     */
    private $productImages;
    private $images;


    public function __construct() {
        $this->productImages = new ArrayCollection();
        $this->orderedProducts = new ArrayCollection();
    }

    public function setImages(array $images) {
        $this->images = $images;

        return $this;
    }

    public function getImages(): ?array {
        return $this->images;
    }

    public function setSliderImages(array $sliderImages) {
        $this->sliderImages = $sliderImages;

        return $this;
    }

    public function getSliderImages() {
        return $this->sliderImages;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getCategory(): ?Categories {
        return $this->category;
    }

    public function setCategory(?Categories $category): self {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(string $url): self {
        $this->url = $url;

        return $this;
    }

    public function getOldPrice() {
        return $this->old_price;
    }

    public function setOldPrice($old_price): self {
        $this->old_price = $old_price;

        return $this;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price): self {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getRating(): ?float {
        return $this->rating;
    }

    public function setRating(float $rating): self {
        $this->rating = $rating;

        return $this;
    }

    public function getVotes(): ?int {
        return $this->votes;
    }

    public function setVotes(int $votes): self {
        $this->votes = $votes;

        return $this;
    }

    public function getOnStock(): ?int {
        return $this->on_stock;
    }

    public function setOnStock(string $on_stock): self {
        $this->on_stock = $on_stock;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self {
        $this->created = $created;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface {
        return $this->modified;
    }

    public function setModified(?\DateTimeInterface $modified): self {
        $this->modified = $modified;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface {
        return $this->deleted;
    }

    public function setDeleted(): self {
        $this->deleted = new \DateTime('now');

        return $this;
    }

    public function getPromoted(): ?int {
        return $this->promoted;
    }

    public function setPromoted(int $promoted): self {
        $this->promoted = $promoted;

        return $this;
    }

    /**
     * @return Collection|OrderedProducts[]
     */
    public function getOrderedProducts(): Collection {
        return $this->orderedProducts;
    }

    public function addOrderedProduct(OrderedProducts $orderedProduct): self {
        if (!$this->orderedProducts->contains($orderedProduct)) {
            $this->orderedProducts[] = $orderedProduct;
            $orderedProduct->setProductId($this);
        }

        return $this;
    }

    public function removeOrderedProduct(OrderedProducts $orderedProduct): self {
        if ($this->orderedProducts->contains($orderedProduct)) {
            $this->orderedProducts->removeElement($orderedProduct);
            // set the owning side to null (unless already changed)
            if ($orderedProduct->getProductId() === $this) {
                $orderedProduct->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getProductImages(): Collection {
        return $this->productImages;
    }

    public function addProductImage(Images $productImage): self {
        var_dump($productImage);
        if (!$this->productImages->contains($productImage)) {
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(Images $productImage): self {
        if ($this->productImages->contains($productImage)) {
            $this->productImages->removeElement($productImage);
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }
}
