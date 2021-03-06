<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Utils\FileUploadHandler;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagesRepository")
 */
class Images {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products", inversedBy="productImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
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


    public function getId(): ?int {
        return $this->id;
    }

    public function getProduct(): ?Products {
        return $this->product;
    }

    public function setProduct(?Products $product): self {
        $this->product = $product;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?int {
        return $this->type;
    }

    public function setType(int $type): self {
        $this->type = $type;

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

    public function uploadFile(string $targetDirectory, object $uploadedFile) {
        $fileUploadHandler = new FileUploadHandler();

        $fileUploadHandler
                ->setTargetDirectory($targetDirectory)
                ->setFile($uploadedFile)
        ;

        $this->setName($fileUploadHandler->uploadFile());
        $this->setCreated(new \DateTime('now'));
        $this->setType(1);
    }

}
