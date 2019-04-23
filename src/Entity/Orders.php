<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_number;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $house;

    /**
     * @ORM\Column(type="integer")
     */
    private $flat_number;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $shipping_method;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $payment_method;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DiscountCodes")
     */
    private $discount_code;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $total;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $shipping_cost;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"CURRENT_TIMESTAMP"})
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
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedProducts", mappedBy="order")
     */
    private $orderedProducts;

    public function __construct()
    {
        $this->orderedProducts = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getOrderNumber(): ?int {
        return $this->order_number;
    }

    public function setOrderNumber(int $order_number): self {
        $this->order_number = $order_number;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string {
        return $this->surname;
    }

    public function setSurname(?string $surname): self {
        $this->surname = $surname;

        return $this;
    }

    public function getCompany(): ?string {
        return $this->company;
    }

    public function setCompany(?string $company): self {
        $this->company = $company;

        return $this;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setCity(?string $city): self {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string {
        return $this->zip;
    }

    public function setZip(?string $zip): self {
        $this->zip = $zip;

        return $this;
    }

    public function getStreet(): ?string {
        return $this->street;
    }

    public function setStreet(?string $street): self {
        $this->street = $street;

        return $this;
    }

    public function getHouse(): ?string {
        return $this->house;
    }

    public function setHouse(?string $house): self {
        $this->house = $house;

        return $this;
    }

    public function getFlatNumber(): ?int {
        return $this->flat_number;
    }

    public function setFlatNumber(?int $flat_number): self {
        $this->flat_number = $flat_number;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function setPhone(?string $phone): self {
        $this->phone = $phone;

        return $this;
    }

    public function getShippingMethod(): ?string {
        return $this->shipping_method;
    }

    public function setShippingMethod(string $shipping_method): self {
        $this->shipping_method = $shipping_method;

        return $this;
    }

    public function getPaymentMethod(): ?string {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): self {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getDiscountCode(): ?DiscountCodes {
        return $this->discount_code;
    }

    public function setDiscountCode(?DiscountCodes $discount_code): self {
        $this->discount_code = $discount_code;

        return $this;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total): self {
        $this->total = $total;

        return $this;
    }

    public function getShippingCost() {
        return $this->shipping_cost;
    }

    public function setShippingCost($shipping_cost): self {
        $this->shipping_cost = $shipping_cost;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self {
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

    public function setDeleted(?\DateTimeInterface $deleted): self {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection|OrderedProducts[]
     */
    public function getOrderedProducts(): Collection
    {
        return $this->orderedProducts;
    }

    public function addOrderedProduct(OrderedProducts $orderedProduct): self
    {
        if (!$this->orderedProducts->contains($orderedProduct)) {
            $this->orderedProducts[] = $orderedProduct;
            $orderedProduct->setOrderId($this);
        }

        return $this;
    }

    public function removeOrderedProduct(OrderedProducts $orderedProduct): self
    {
        if ($this->orderedProducts->contains($orderedProduct)) {
            $this->orderedProducts->removeElement($orderedProduct);
            // set the owning side to null (unless already changed)
            if ($orderedProduct->getOrderId() === $this) {
                $orderedProduct->setOrderId(null);
            }
        }

        return $this;
    }

}
