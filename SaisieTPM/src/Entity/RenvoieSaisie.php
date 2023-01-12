<?php

namespace App\Entity;

use App\Repository\RenvoieSaisieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RenvoieSaisieRepository::class)]
class RenvoieSaisie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $saisie = [];

    #[ORM\ManyToOne(inversedBy: 'renvoieSaisies')]
    private ?Formulaire $fomulaire_id = null;

    #[ORM\ManyToOne(inversedBy: 'renvoieSaisies')]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaisie(): array
    {
        return $this->saisie;
    }

    public function setSaisie(array $saisie): self
    {
        $this->saisie = $saisie;

        return $this;
    }

    public function getFomulaireId(): ?Formulaire
    {
        return $this->fomulaire_id;
    }

    public function setFomulaireId(?Formulaire $fomulaire_id): self
    {
        $this->fomulaire_id = $fomulaire_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
