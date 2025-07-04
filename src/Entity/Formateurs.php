<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateursRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormateursRepository::class)]
class Formateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['formateurs' , 'abonnements'])]  
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['formateurs' , 'abonnements'])] 
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['formateurs' , 'abonnements'])] 
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formateurs' , 'abonnements'])] 
    private ?string $numeroTelephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formateurs'])] 
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formateurs'])] 
    private ?string $adresse = null;

    #[Groups(['formateurs'])]  
    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $sexe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentreDeFormation = null;


    #[ORM\Column]
    #[Groups(['formateurs'])]  
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['formateurs'])]  
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, FormateursFormation>
     */
    #[ORM\OneToMany(targetEntity: FormateursFormation::class, mappedBy: 'Formateurs', orphanRemoval: true)]
    private Collection $formateursFormations;

    public function __construct()
    {
        $this->formateursFormations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, FormateursFormation>
     */
    public function getFormateursFormations(): Collection
    {
        return $this->formateursFormations;
    }

    public function addFormateursFormation(FormateursFormation $formateursFormation): static
    {
        if (!$this->formateursFormations->contains($formateursFormation)) {
            $this->formateursFormations->add($formateursFormation);
            $formateursFormation->setFormateurs($this);
        }

        return $this;
    }

    public function removeFormateursFormation(FormateursFormation $formateursFormation): static
    {
        if ($this->formateursFormations->removeElement($formateursFormation)) {
            // set the owning side to null (unless already changed)
            if ($formateursFormation->getFormateurs() === $this) {
                $formateursFormation->setFormateurs(null);
            }
        }

        return $this;
    }

}
