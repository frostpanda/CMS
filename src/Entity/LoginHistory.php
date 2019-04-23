<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoginHistoryRepository")
 */
class LoginHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Administrator", inversedBy="history")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=39)
     */
    private $ip_address;
    
    /**
     * 
     * @ORM\Column(type="smallint")
     */
    private $logged;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default" : "CURRENT_TIMESTAMP"})
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


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Administrator
    {
        return $this->user;
    }

    public function setUser(?Administrator $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }
    
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?\DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
    
    public function getLogged(): ?int
    {
        return $this->logged;
    }

    public function setLogged(int $logged): self
    {
        $this->logged = $logged;

        return $this;
    }
}
