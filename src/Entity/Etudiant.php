<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['etudiants'])]  
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['etudiants'])]  
    private ?string $nomComplet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['etudiants'])]  
    private ?string $numeroTelephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['etudiants'])]  
    private ?string $nomTuteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['etudiants'])]  
    private ?string $numeroTelephoneTuteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['etudiants'])]  
    private ?string $niveauScolaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['etudiants'])]  
    private ?string $groupe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['etudiants'])]  
    private ?string $adresse = null;

    #[ORM\Column]
    #[Groups(['etudiants'])]  
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['etudiants'])]  
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentreDeFormation = null;

    #[ORM\Column]
    #[Groups(['etudiants'])]  
    private ?bool $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): static
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(?string $numeroTelephone): static
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getNomTuteur(): ?string
    {
        return $this->nomTuteur;
    }

    public function setNomTuteur(?string $nomTuteur): static
    {
        $this->nomTuteur = $nomTuteur;

        return $this;
    }

    public function getNumeroTelephoneTuteur(): ?string
    {
        return $this->numeroTelephoneTuteur;
    }

    public function setNumeroTelephoneTuteur(?string $numeroTelephoneTuteur): static
    {
        $this->numeroTelephoneTuteur = $numeroTelephoneTuteur;

        return $this;
    }

    public function getNiveauScolaire(): ?string
    {
        return $this->niveauScolaire;
    }

    public function setNiveauScolaire(?string $niveauScolaire): static
    {
        $this->niveauScolaire = $niveauScolaire;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(?string $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreatedAt($requiredDateTime = false ): \DateTimeImmutable | string
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


    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
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

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
