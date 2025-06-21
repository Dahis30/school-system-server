<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbonnementRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['abonnements'])]  
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentresDeFormation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $Etudiant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateurs $Formateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $Formation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $DateDebut = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $DateFin = null;

    #[ORM\Column]
    #[Groups(['abonnements'])]
    private ?float $MontantAbonnement = null;

    #[ORM\Column]
    #[Groups(['abonnements'])]
    private ?float $MontantFormateur = null;

    #[ORM\Column(length: 255)]
    private ?string $Statut = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->Etudiant;
    }

    public function setEtudiant(?Etudiant $Etudiant): static
    {
        $this->Etudiant = $Etudiant;

        return $this;
    }

    public function getCentresDeFormation(): ?CentresDeFormation
    {
        return $this->CentresDeFormation;
    }

    public function setCentresDeFormation(?CentresDeFormation $CentresDeFormation): static
    {
        $this->CentresDeFormation = $CentresDeFormation;

        return $this;
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

    public function getFormation(): ?Formation
    {
        return $this->Formation;
    }

    public function setFormation(?Formation $Formation): static
    {
        $this->Formation = $Formation;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $DateDebut): static
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeImmutable $DateFin): static
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getMontantAbonnement(): ?float
    {
        return $this->MontantAbonnement;
    }

    public function setMontantAbonnement(float $MontantAbonnement): static
    {
        $this->MontantAbonnement = $MontantAbonnement;

        return $this;
    }

    public function getMontantFormateur(): ?float
    {
        return $this->MontantFormateur;
    }

    public function setMontantFormateur(float $MontantFormateur): static
    {
        $this->MontantFormateur = $MontantFormateur;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->Statut;
    }

    public function setStatut(string $Statut): static
    {
        $this->Statut = $Statut;

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
}
