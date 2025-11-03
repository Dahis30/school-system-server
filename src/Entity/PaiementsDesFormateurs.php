<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PaiementsDesFormateursRepository;

#[ORM\Entity(repositoryClass: PaiementsDesFormateursRepository::class)]
class PaiementsDesFormateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?float $montant = null;

    #[ORM\Column(length: 80)]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?string $modePaiement = null;

    #[ORM\Column]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?\DateTimeImmutable $datePaiement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?string $commentaire = null;

    #[ORM\Column]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['paiements-indemnites-formateures' ])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'paiementsDesFormateurs')]
    #[ORM\JoinColumn(nullable: false , onDelete: 'CASCADE')]
    private ?IndemnitesDeFormateures $IndemniteDeFormateur = null;

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

    public function setModePaiement(string $modePaiement): static
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIndemniteDeFormateur(): ?IndemnitesDeFormateures
    {
        return $this->IndemniteDeFormateur;
    }

    public function setIndemniteDeFormateur(?IndemnitesDeFormateures $IndemniteDeFormateur): static
    {
        $this->IndemniteDeFormateur = $IndemniteDeFormateur;

        return $this;
    }
}
