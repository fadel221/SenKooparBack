<?php

namespace App\Entity;

use App\Entity\Agence;
use App\Entity\Compte;
use App\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 *  
 * 
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *      fields={"telephone"},
 *      message="Ce libellé existe déjà"
 * )
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"user_read","user_transaction:read"},"enable_max_depth"=true}
 *      },
 *  
 *    collectionOperations={
 * 
 *         "post"={
 *              "normalization_context" ={"groups" ={"user:read"}},
 *              "denormalization_context" ={"groups" ={"user:write"}},
 *              "path"="/admin/users"
 *          },
 *         "get"={
 *             "normalization_context" ={"groups" ={"user:read"},"enable_max_depth"=true},
 *              "path"="/admin/users",
 *          },
 * 
 *          
 * 
 *          
 * 
 *          "montant_transaction"={
 *             "normalization_context" ={"groups" ={"user:read"},"enable_max_depth"=true},
 *              "path"="/user/frais/montant",
 *              "method"="GET"
 *          }
 *          
 *       },
 *      itemOperations={
 *          "get"={
 *              "normalization_context" ={"groups" ={"user:read"}},      
 *              "path"="/admin/users/{id}",
 *              "defaults"={"id"=null}
 *          },
 * 
 *          "recharger_compte"=
 *              {
 *                  "method"="PUT",
 *                  "path"="/caissier/{idcaissier}/compte/{idcompte}"
 *              },
 *      
 *          "delete"=
 *              {
 *                 "path"="/admin/users/{id}" 
 *              },
 * 
 *              "get"={
 *             "normalization_context" ={"groups" ={"user_transaction:read"},"enable_max_depth"=true},
 *              "path"="/user/{id}/transactions",
 *          },
 *          
 *    }
 * 
 * )
 * @ApiFilter(DateFilter::class, properties={"transactions.dateRetrait","transaction.dateRetrait"})
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction:write"})
     * @Groups({"compte:write","compte:read"})
     * @Groups({"agence_user:read","user_transaction:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Groups({"user:write","transaction:write","transaction:read"})
     * @Groups({"agence_user:read","user:read","user_transaction:read"})
     */
    private $email;

    
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"user:write","agence_user:read","user:read","user_transaction:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"user:write","agence_user:read","user:read","user_transaction:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = 9, max =9 , minMessage = "Numéro Incomplet", maxMessage = "Numéro Volumineux")
     * @Assert\Regex(pattern="/^(76|77|78|75)[0-9]*$/", message="number_only") 
     * @Groups({"transaction:write","transaction:read"}) 
     * @Groups({"user:write","agence_user:read","user:read","user_transaction:read"})
    */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"agence_user:read","user:read","user_transaction:read"})
     */
    private $statut;

    
    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"user:write","transaction:write","transaction:read","user:read"})
     */
    private $agence;

    /**
     * @ORM\ManyToMany(targetEntity=Compte::class, inversedBy="utilisateurs")
     */
    private $compte;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="userRetrait")
     * @Groups({"user_transaction:read"})
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="userDepot")
     * @Groups({"user_transaction:read"})
     */
    private $transactions;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="utilisateurs")
     */
    private $role;

    public function __construct()
    {
        $this->compte = new ArrayCollection();
        $this->transaction = new ArrayCollection();
        $this->statut=false;
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getCompte(): Collection
    {
        return $this->compte;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->compte->contains($compte)) {
            $this->compte[] = $compte;
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        $this->compte->removeElement($compte);

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
            $transaction->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUtilisateur() === $this) {
                $transaction->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->prenom." ".$this->nom;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
     
}
