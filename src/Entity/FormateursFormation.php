<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateursFormationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormateursFormationRepository::class)]
class FormateursFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['relationFormationFormateur'])] 
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'formateursFormations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateurs $Formateurs = null;

    #[ORM\ManyToOne(inversedBy: 'formateursFormations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['relationFormationFormateur'])] 
    private ?Formation $Formation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['relationFormationFormateur'])] 
    private ?string $TypeDePaiement = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['relationFormationFormateur'])] 
    private ?float $montant = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['relationFormationFormateur'])] 
    private ?float $pourcentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormateurs(): ?Formateurs
    {
        return $this->Formateurs;
    }

    public function setFormateurs(?Formateurs $Formateurs): static
    {
        $this->Formateurs = $Formateurs;

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

    public function getTypeDePaiement(): ?string
    {
        return $this->TypeDePaiement;
    }

    public function setTypeDePaiement(?string $TypeDePaiement): static
    {
        $this->TypeDePaiement = $TypeDePaiement;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?float $pourcentage): static
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }
}
