<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PaiementRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['paiements'])]  
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['paiements'])] 
    private ?float $montant = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['paiements'])] 
    private ?string $modePaiement = null;

    #[ORM\Column]
    #[Groups(['paiements'])]
    private ?\DateTimeImmutable $datePaiement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['paiements'])] 
    private ?string $commentaire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['paiements'])]  
    private ?Abonnement $Abonnement = null;

    #[ORM\Column]
    #[Groups(['paiements'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['paiements'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentreDeFormation = null;

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

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getDatePaiement($requiredDateTime = false): \DateTimeImmutable | string
    {
        if($requiredDateTime) return $this->datePaiement ;
        return $this->datePaiement->format('Y-m-d');
    }

    public function setDatePaiement(\DateTimeImmutable $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

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

    public function getAbonnement(): ?Abonnement
    {
        return $this->Abonnement;
    }

    public function setAbonnement(?Abonnement $Abonnement): static
    {
        $this->Abonnement = $Abonnement;

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
        return $this->CentreDeFormation;
    }

    public function setCentreDeFormation(?CentresDeFormation $CentreDeFormation): static
    {
        $this->CentreDeFormation = $CentreDeFormation;

        return $this;
    }
}
