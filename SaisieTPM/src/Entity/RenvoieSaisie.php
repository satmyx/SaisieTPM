<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\RenvoieSaisieRepository;

#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')", openapiContext: ["summary" => "Renvoie la liste de toutes les entités"]),
        new Post(security: "is_granted('ROLE_USER')", openapiContext: ["summary" => "Permet de créer une entité (ROLE ADMIN)"]),
        new Get(security: "is_granted('ROLE_USER')", openapiContext: ["summary" => "Renvoie l'entité selon l'id"]),
        new Patch(security: "is_granted('ROLE_USER') or object.owner == user", openapiContext: ["summary" => "Modifie un élément de l'entité (ROLE ADMIN ou Propriétaire)"]),
        new Delete(security: "is_granted('ROLE_USER')", openapiContext: ["summary" => "Supprime l'entité (ROLE ADMIN)"]),
    ]
)]

#[ORM\Entity(repositoryClass: RenvoieSaisieRepository::class)]
class RenvoieSaisie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Json]
    #[ORM\Column]
    private array $saisie = [];

    #[ORM\ManyToOne(inversedBy: 'renvoieSaisies')]
    private ?Formulaire $fomulaire_id = null;

    #[ORM\ManyToOne(inversedBy: 'renvoieSaisies')]
    private ?User $user_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $piecejointe = null;

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

    public function getPiecejointe(): ?string
    {
        return $this->piecejointe;
    }

    public function setPiecejointe(?string $piecejointe): self
    {
        $this->piecejointe = $piecejointe;

        return $this;
    }

    public function getName() {
        return $this->nom;
    }
}