<?php

namespace App\Entity;

use App\Repository\IndemnitesDeFormateuresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndemnitesDeFormateuresRepository::class)]
class IndemnitesDeFormateures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'indemnitesDeFormateures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateurs $Formateur = null;

    #[ORM\ManyToOne(inversedBy: 'indemnitesDeFormateures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Abonnement $Abonnement = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentreDeFormation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?float $montantDeFormateur = null;

    #[ORM\Column(nullable: true)]
    private ?float $MontantPayee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormateur(): ?Formateurs
    {
        return $this->Formateur;
    }

    public function setFormateur(?Formateurs $Formateur): static
    {
        $this->Formateur = $Formateur;

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

    public function getCentreDeFormation(): ?CentresDeFormation
    {
        return $this->CentreDeFormation;
    }

    public function setCentreDeFormation(?CentresDeFormation $CentreDeFormation): static
    {
        $this->CentreDeFormation = $CentreDeFormation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
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

    public function getMontantDeFormateur(): ?float
    {
        return $this->montantDeFormateur;
    }

    public function setMontantDeFormateur(float $montantDeFormateur): static
    {
        $this->montantDeFormateur = $montantDeFormateur;

        return $this;
    }

    
    public function getMontantPayee(): ?float
    {
        return $this->MontantPayee;
    }

    public function setMontantPayee(?float $MontantPayee): static
    {
        $this->MontantPayee = $MontantPayee;

        return $this;
    }
}
