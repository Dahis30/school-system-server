<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['formation'])]  
    private ?string $titre = null;

    #[ORM\Column(length: 400, nullable: true)]
    #[Groups(['formation'])]  
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['formation'])]  
    private ?int $nombreDesMois = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNombreDesMois(): ?int
    {
        return $this->nombreDesMois;
    }

    public function setNombreDesMois(?int $nombreDesMois): static
    {
        $this->nombreDesMois = $nombreDesMois;

        return $this;
    }

    public function getUtilisateur(): ?Users
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Users $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
