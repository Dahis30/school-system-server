<?php

namespace App\Entity;

use App\Repository\DemandeInscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DemandeInscriptionRepository::class)]
class DemandeInscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['demandesInscription'])] 
    private ?int $id = null;

    #[Groups(['demandesInscription'])]  
    #[ORM\Column(length: 255)] 
    private ?string $nom = null;

    #[Groups(['demandesInscription'])]  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[Groups(['demandesInscription'])]  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[Groups(['demandesInscription'])]  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motDePass = null;


    #[Groups(['demandesInscription'])]  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroTelephone = null;

    #[ORM\OneToOne(mappedBy: 'demandeInscription')]
    private ?Users $users = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validated = null;

    #[Groups(['demandesInscription'])]  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sexe = null;

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

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getMotDePass(): ?string
    {
        return $this->motDePass;
    }

    public function setMotDePass(?string $motDePass): static
    {
        $this->motDePass = $motDePass;

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

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setDemandeInscription(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getDemandeInscription() !== $this) {
            $users->setDemandeInscription($this);
        }

        $this->users = $users;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(?bool $validated): static
    {
        $this->validated = $validated;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }
}
