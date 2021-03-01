<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 * 
 *  collectionOperations={
 *  "post"={
 *              "denormalization_context" ={"groups" ={"agence:write"}},
 *              "normalization_context" ={"groups" ={"agence:read"}},
 *              "path"="/admin/agences"
 *          },
 * 
 * "get"={
 *              "normalization_context" ={"groups" ={"agence:read"}},
 *              "path"="/admin/agences"
 *          }
 *  
 * 
 * 
 * },
 * 
 * itemOperations={
 *   "agence_id"={
 *              "normalization_context" ={"groups" ={"agence:read"}},
 *              "path"="/admin/agences/{id}",
 *              "method"="GET"
 *          },
 * 
 *   "bloquer_user"={
 *              "path"="/admin/agence/{ida}/user/{idu}",
 *              "method"="PUT"
 *         },
 * 
 *  "get"={
 *              "normalization_context" ={"groups" ={"agence_user:read"}},
 *              "path"="agence/{id}/users"
 *          },
 *   "delete"=
 *      {
 *          "path"="admin/agences/{id}"
 *      }
 *  
 * }
 * 
 * )
 * 
 * )
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @UniqueEntity(
 *      fields={"telephone"},
 *      message="Ce libellé existe déjà"
 * )
 * @ApiFilter(SearchFilter::class, properties={"compte.id":"exact"})
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read","user:write","compte:write","compte:read","agence_user:read","agence:read","agence:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 9, max =9 , minMessage = "Numéro Incomplet", maxMessage = "Numéro Volumineux")
     * @Assert\Regex(pattern="/^(33|76|77|78|75)[0-9]*$/", message="Précédé tjrs de 33|76|77|78|75") 
     * @Groups({"user:read","compte:read","agence_user:read","agence:read","agence:write"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"user:read","compte:read","agence_user:read","agence:read","agence:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","compte:read","agence_user:read","agence:read","agence:write"})
     * @Assert\NotBlank()
     */
    private $lattitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","compte:read","agence_user:read","agence:read","agence:write"})
     * @Assert\NotBlank()
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateur::class, mappedBy="agence")
     * @Groups({"compte:read","agence_user:read"})
     */
    private $utilisateurs;

    /**
     * @ORM\OneToOne(targetEntity=Compte::class, mappedBy="agence", cascade={"persist", "remove"})
     * @Groups({"agence:write","agence:read"})
     */
    private $compte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:read"})
     */
    private $nomAgence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->statut=false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLattitude(): ?string
    {
        return $this->lattitude;
    }

    public function setLattitude(string $lattitude): self
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setAgence($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getAgence() === $this) {
                $utilisateur->setAgence(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(Compte $compte): self
    {
        // set the owning side of the relation if necessary
        if ($compte->getAgence() !== $this) {
            $compte->setAgence($this);
        }

        $this->compte = $compte;

        return $this;
    }

    

    public function getNomAgence(): ?string
    {
        return $this->nomAgence;
    }

    public function setNomAgence(string $nomAgence): self
    {
        $this->nomAgence = $nomAgence;

        return $this;
    }

    public function __toString()
    {
        return $this->nomAgence;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
