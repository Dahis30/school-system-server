<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\IndemnitesDeFormateuresRepository;

#[ORM\Entity(repositoryClass: IndemnitesDeFormateuresRepository::class)]
class IndemnitesDeFormateures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['indemnites-formateures' ])]  
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'indemnitesDeFormateures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['indemnites-formateures' ])]
    private ?Formateurs $Formateur = null;

    #[ORM\ManyToOne(inversedBy: 'indemnitesDeFormateures')]
    #[ORM\JoinColumn(nullable: false , onDelete: 'CASCADE')]
    private ?Abonnement $Abonnement = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentreDeFormation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Groups(['indemnites-formateures' ])]  
    private ?float $montantDeFormateur = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['indemnites-formateures' ])]
    private ?float $MontantPayee = null;

    /**
     * @var Collection<int, PaiementsDesFormateurs>
     */
    #[ORM\OneToMany(targetEntity: PaiementsDesFormateurs::class, mappedBy: 'IndemniteDeFormateur', orphanRemoval: true)]
    private Collection $paiementsDesFormateurs;

    public function __construct()
    {
        $this->paiementsDesFormateurs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, PaiementsDesFormateurs>
     */
    public function getPaiementsDesFormateurs(): Collection
    {
        return $this->paiementsDesFormateurs;
    }

    public function addPaiementsDesFormateur(PaiementsDesFormateurs $paiementsDesFormateur): static
    {
        if (!$this->paiementsDesFormateurs->contains($paiementsDesFormateur)) {
            $this->paiementsDesFormateurs->add($paiementsDesFormateur);
            $paiementsDesFormateur->setIndemniteDeFormateur($this);
        }

        return $this;
    }

    public function removePaiementsDesFormateur(PaiementsDesFormateurs $paiementsDesFormateur): static
    {
        if ($this->paiementsDesFormateurs->removeElement($paiementsDesFormateur)) {
            // set the owning side to null (unless already changed)
            if ($paiementsDesFormateur->getIndemniteDeFormateur() === $this) {
                $paiementsDesFormateur->setIndemniteDeFormateur(null);
            }
        }

        return $this;
    }
}
