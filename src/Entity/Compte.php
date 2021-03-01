<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 * collectionOperations={
 *  "post"={
 *              "denormalization_context" ={"groups" ={"compte:write"}},
 *              "normalization_context" ={"groups" ={"compte:read"}},
 *              "path"="/admin/comptes"
 *          },
 *  "get"={
 *              "normalization_context" ={"groups" ={"compte:read"}},
 *              "path"="/admin/comptes"
 *          }
 * 
 * },
 * 
 * itemOperations={
 *   "get"={
 *              "normalization_context" ={"groups" ={"compte:read"}},
 *              "path"="/admin/comptes/{id}/transactions"
 *          },
 *  
 * }
 * 
 * )
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @UniqueEntity(
 *      fields={"numero"},
 *      message="Ce libellé existe déjà"
 * )
 * @ApiFilter(DateFilter::class, properties={"transactions.dateDepot","transactions.dateRetrait","transaction.dateDepot","transaction.dateRetrait","dateCreation"})
 * @ApiFilter(SearchFilter::class, properties={"utilisateurs.id"="exact"})
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","client:write","compte:read","agence:write","agence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\Length(min = 9, max =9 , minMessage = "Numéro Incomplet", maxMessage = "Numéro Volumineux")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="number_only") 
     * @Groups({"transaction:read","client:write","compte:read","agence:read"})
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Groups({"transaction:read","client:write","compte:write","compte:read","agence:read"})
     */
    private $solde;

    /**
     * @ORM\Column(type="date")
     * @Groups({"transaction:read","client:write","compte:read","agence:read"})
     */
    private $dateCreation;

    

    /**
     * @ORM\ManyToMany(targetEntity=Utilisateur::class, mappedBy="compte")
     * @Groups({"compte:write","compte:read"})
     */
    private $utilisateurs;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compteDepot")
     *@Groups({"compte:read"})
     */
    private $transaction;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, inversedBy="compte", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"compte:write","compte:read"})
     */
    private $agence;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"transaction:read","client:write","compte:read"})
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compteRetrait")
     * @Groups({"compte:read"})
     */
    private $transactions;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->transaction = new ArrayCollection();
        $this->statut=false;
        $this->dateCreation=new \DateTime();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
            $utilisateur->addCompte($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeCompte($this);
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
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

    public function __toString()
    {
        return $this->solde;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }
}
