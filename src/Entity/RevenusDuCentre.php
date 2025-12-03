<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RevenusDuCentreRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RevenusDuCentreRepository::class)]
class RevenusDuCentre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['revenus-centre'])]  
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['revenus-centre'])]  
    private ?float $montant = null;

    #[ORM\Column(length: 500)]
    #[Groups(['revenus-centre'])]  
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['revenus-centre'])]  
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 600, nullable: true)]
    #[Groups(['revenus-centre'])]  
    private ?string $commentaire = null;

    #[ORM\Column]
    #[Groups(['revenus-centre'])]  
    private ?\DateTimeImmutable $createdAt = null;
    
    #[ORM\Column(nullable: true)]
    #[Groups(['revenus-centre'])]  
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $centreDeFormation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate($requiredDateTime = false): \DateTimeImmutable | string
    {
        if($requiredDateTime) return $this->date ;
        return $this->date->format('Y-m-d');
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
        
    public function getCreatedAt($requiredDateTime = false): \DateTimeImmutable | string
    {
        if($requiredDateTime) return $this->createdAt ;
        return $this->createdAt->format('Y-m-d');
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    public function getUpdatedAt($requiredDateTime = false): \DateTimeImmutable | string | null
    {
        if($requiredDateTime) return $this->updatedAt;
        if(!$requiredDateTime && !empty($this->updatedAt) )return $this->updatedAt->format('Y-m-d'); 
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCentreDeFormation(): ?CentresDeFormation
    {
        return $this->centreDeFormation;
    }

    public function setCentreDeFormation(?CentresDeFormation $centreDeFormation): static
    {
        $this->centreDeFormation = $centreDeFormation;

        return $this;
    }
}
