<?php

namespace App\Entity;

use App\Entity\Client;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;
use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\TermFilter;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @UniqueEntity(
 *      fields={"codeTransfert"},
 *      message="Ce libellé existe déjà"
 * )
 * @ApiResource(
 *  collectionOperations={
 * 
 *         "add_transaction"={
 *              "method"="POST",
 *              "normalization_context" ={"groups" ={"transaction:read"}},
 *              "path"="/transactions"
 *          },
 * 
 *         "get"={
 *              "path"="/transactions",
 *              "normalization_context" ={"groups" ={"transaction:read"}},
 *          }
 *          
 *       },
 *      itemOperations={
 *          "get"={
 *              "normalization_context" ={"groups" ={"transaction:read"}},      
 *              "path"="/transactions/{id}",
 *              "defaults"={"id"=null}
 *               },
 * 
 *          "update_transaction"={
 *                  "denormalization_context" ={"groups" ={"transaction:write"}},      
 *                  "path"="/transactions/{id}",
 *                  "method"="PUT",
 *                  "normalization_context" ={"groups" ={"transaction:read"}},
 *              }
 *    }
 * )
 * 
 *@ApiFilter(SearchFilter::class, properties={"codeTransfert":"exact", "montant":"exact","userDepot.id":"exact","userRetrait.id":"exact"})
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:write","transaction:read"})
     * @Assert\NotBlank()
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $montant;

    /**
     * @ORM\Column(type="date",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"compte:read"})
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"user_transaction:read"})
     */
    private $codeTransfert;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $frais;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $fraisDepot;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $fraisRetrait;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $fraisEtat;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"compte:read"})
     * @Groups({"user_transaction:read"})
     */
    private $fraisSysteme;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="transaction",cascade={"persist"}))
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     */
    private $userRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transaction",cascade={"persist"}))
     * @Groups({"transaction:write","transaction:read"})
     */
    private $clientDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transaction")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     */
    private $compteDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"transaction:write","transaction:read"})
     */
    private $userDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"user_transaction:read"})
     */
    private $compteRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions",cascade={"persist"}))
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"transaction:write","transaction:read"})
     * @Groups({"user_transaction:read"})
     */
    private $clientRetrait;

    public function __construct()
    {
        $this->dateDepot= new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;
        return $this;
    }

    public function getCodeTransfert(): ?string
    {
        return $this->codeTransfert;
    }

    public function setCodeTransfert(string $codeTransfert): self
    {
        $this->codeTransfert = $codeTransfert;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getFraisDepot(): ?int
    {
        return $this->fraisDepot;
    }

    public function setFraisDepot(int $fraisDepot): self
    {
        $this->fraisDepot = $fraisDepot;

        return $this;
    }

    public function getFraisRetrait(): ?int
    {
        return $this->fraisRetrait;
    }

    public function setFraisRetrait(int $fraisRetrait): self
    {
        $this->fraisRetrait = $fraisRetrait;

        return $this;
    }

    public function getFraisEtat(): ?int
    {
        return $this->fraisEtat;
    }

    public function setFraisEtat(int $fraisEtat): self
    {
        $this->fraisEtat = $fraisEtat;

        return $this;
    }

    public function getFraisSysteme(): ?int
    {
        return $this->fraisSysteme;
    }

    public function setFraisSysteme(int $fraisSysteme): self
    {
        $this->fraisSysteme = $fraisSysteme;
        return $this;
    }

    public function getUserRetrait(): ?Utilisateur
    {
        return $this->userRetrait;
    }

    public function setUserRetrait(?Utilisateur $userRetrait): self
    {
        $this->userRetrait = $userRetrait;
        return $this;
    }

    public function getClientDepot(): ?Client
    {
        return $this->clientDepot;
    }

    public function setClientDepot(?Client $client): self
    {
        $this->clientDepot = $client;

        return $this;
    }

    public function getCompteDepot(): ?Compte
    {
        return $this->compteDepot;
    }

    public function setCompteDepot(?Compte $compte): self
    {
        $this->compteDepot = $compte;
        return $this;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    public function getUserDepot(): ?Utilisateur
    {
        return $this->userDepot;
    }

    public function setUserDepot(?Utilisateur $userDepot): self
    {
        $this->userDepot = $userDepot;

        return $this;
    }

    public function getCompteRetrait(): ?Compte
    {
        return $this->compteRetrait;
    }

    public function setCompteRetrait(?Compte $compteRetrait): self
    {
        $this->compteRetrait = $compteRetrait;

        return $this;
    }

    public function getClientRetrait(): ?Client
    {
        return $this->clientRetrait;
    }

    public function setClientRetrait(?Client $clientRetrait): self
    {
        $this->clientRetrait = $clientRetrait;

        return $this;
    }
}
