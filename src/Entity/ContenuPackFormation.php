<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContenuPackFormationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContenuPackFormationRepository::class)]
class ContenuPackFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contenusDePack'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['contenusDePack'])]
    private ?PackFormation $PackFormation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['contenusDePack'])]
    private ?Formation $Formation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['contenusDePack'])]
    private ?Formateurs $Formateur = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['contenusDePack'])]
    private ?float $pourcentageDeFormateur = null;

    #[ORM\Column]
    #[Groups(['contenusDePack'])]
    private ?float $montantDeFormateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPackFormation(): ?PackFormation
    {
        return $this->PackFormation;
    }

    public function setPackFormation(?PackFormation $PackFormation): static
    {
        $this->PackFormation = $PackFormation;

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

    public function getFormateur(): ?Formateurs
    {
        return $this->Formateur;
    }

    public function setFormateur(?Formateurs $Formateur): static
    {
        $this->Formateur = $Formateur;

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

    public function getPourcentageDeFormateur(): ?float
    {
        return $this->pourcentageDeFormateur;
    }

    public function setPourcentageDeFormateur(float $pourcentageDeFormateur): static
    {
        $this->pourcentageDeFormateur = $pourcentageDeFormateur;

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
}
