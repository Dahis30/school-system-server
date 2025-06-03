<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['formation'])]  
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['formation' , 'relationFormationFormateur'])]  
    private ?string $titre = null;

    #[ORM\Column(length: 400, nullable: true)]
    #[Groups(['formation' , 'relationFormationFormateur'])]  
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CentresDeFormation $CentreDeFormation = null;

    #[ORM\Column]
    #[Groups(['formation'])]
    private ?\DateTimeImmutable $createdAt = null;

    
    #[ORM\Column(nullable: true)]
    #[Groups(['formation'])]  
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, FormateursFormation>
     */
    #[ORM\OneToMany(targetEntity: FormateursFormation::class, mappedBy: 'Formation', orphanRemoval: true)]
    private Collection $formateursFormations;

    public function __construct()
    {
        $this->formateursFormations = new ArrayCollection();
    }



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
            $formateursFormation->setFormation($this);
        }

        return $this;
    }

    public function removeFormateursFormation(FormateursFormation $formateursFormation): static
    {
        if ($this->formateursFormations->removeElement($formateursFormation)) {
            // set the owning side to null (unless already changed)
            if ($formateursFormation->getFormation() === $this) {
                $formateursFormation->setFormation(null);
            }
        }

        return $this;
    }
}
