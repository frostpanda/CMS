<?php

namespace App\Entity;

use App\Utils\FileUploadHandler;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SlidersRepository")
 */
class Sliders {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

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

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?int {
        return $this->position;
    }

    public function setPosition(int $position): self {
        $this->position = $position;

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
    }

}
