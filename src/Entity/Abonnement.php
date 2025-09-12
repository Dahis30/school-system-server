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
    #[Groups(['abonnements' , 'paiements'])]  
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentresDeFormation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['abonnements' , 'abonnementsDePack'])]  
    private ?Etudiant $Etudiant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['abonnements'])]  
    private ?Formateurs $Formateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['abonnements'])]  
    private ?Formation $Formation = null;    

    #[ORM\Column]
    #[Groups(['abonnements' , 'abonnementsDePack'])]  
    private ?\DateTimeImmutable $DateDebut = null;

    #[ORM\Column]
    #[Groups(['abonnements' , 'abonnementsDePack'])]  
    private ?\DateTimeImmutable $DateFin = null;

    #[ORM\Column]
    #[Groups(['abonnements' , 'paiements' , 'abonnementsDePack' ])]
    private ?float $MontantAbonnement = null;

    #[ORM\Column (nullable: true) ]
    #[Groups(['abonnements'])]
    private ?float $MontantFormateur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['abonnements'])]  
    private ?string $Statut = null;

    #[ORM\Column]
    #[Groups(['abonnements' , 'abonnementsDePack'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['abonnements' , 'abonnementsDePack' ])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 600, nullable: true)]
    #[Groups(['abonnements' , 'abonnementsDePack'])]  
    private ?string $Commentaire = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['abonnements' , 'paiements' , 'abonnementsDePack'])]  
    private ?float $MontantPayee = null;

    #[ORM\ManyToOne]
    #[Groups(['abonnementsDePack'])]  
    private ?PackFormation $pack = null;

    #[ORM\Column(nullable: false)]
    private ?bool $relatedToPack = null;

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

    public function getDateDebut($requiredDateTime = false): \DateTimeImmutable | string
    {
        if($requiredDateTime) return $this->DateDebut ;
        return $this->DateDebut->format('Y-m-d');
    }

    public function setDateDebut(\DateTimeImmutable $DateDebut): static
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin($requiredDateTime = false): \DateTimeImmutable | string
    {
        if($requiredDateTime) return $this->DateFin ;
        return $this->DateFin->format('Y-m-d');
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

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(?string $Commentaire): static
    {
        $this->Commentaire = $Commentaire;

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

    public function getPack(): ?PackFormation
    {
        return $this->pack;
    }

    public function setPack(?PackFormation $pack): static
    {
        $this->pack = $pack;

        return $this;
    }

    public function isRelatedToPack(): ?bool
    {
        return $this->relatedToPack;
    }

    public function setRelatedToPack(?bool $relatedToPack): static
    {
        $this->relatedToPack = $relatedToPack;

        return $this;
    }
}
