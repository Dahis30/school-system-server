<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContenuAbonnementRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContenuAbonnementRepository::class)]
class ContenuAbonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('contenu_abonnement')]  
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false , onDelete: 'CASCADE')]
    private ?Abonnement $Abonnement = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('contenu_abonnement')]  
    private ?ContenuPackFormation $Contenu = null;

    #[ORM\Column]
    #[Groups('contenu_abonnement')]  
    private ?float $pourcentageDeFormateur = null;

    #[ORM\Column]
    #[Groups('contenu_abonnement')]  
    private ?float $montantDeFormateur = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContenu(): ?ContenuPackFormation
    {
        return $this->Contenu;
    }

    public function setContenu(?ContenuPackFormation $Contenu): static
    {
        $this->Contenu = $Contenu;

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
