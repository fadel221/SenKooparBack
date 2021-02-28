<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ApiResource(
 * collectionOperations={
 *  "post"={
 *              
 *              "path"="/clients"
 *          },
 *         "get"={
 *             "normalization_context" ={"groups" ={"client:read"}},
 *              "path"="/clients",
 *               }
 *      },
 * 
 *  itemOperations={
 *          "get"={
 *              "normalization_context" ={"groups" ={"user:read"}},      
 *              "path"="/clients/{id}",
 *              "defaults"={"id"=null}
 *          }
 * }
 * 
 * )
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(
 *      fields={"numCIN"},
 *      message="Ce libellé existe déjà"
 * )
 * @ApiFilter(SearchFilter::class, properties={"numCIN":"exact"})
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction:write","client:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:write","transaction:read","client:write","client:read"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"client:write"})
     * @Groups({"transaction:write","transaction:read","client:write","client:read"})
     */
    private $numCIN;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientDepot")
     * 
     */
    private $transaction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:write","transaction:read","client:write","client:read"})
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientRetrait")
     * 
     */
    private $transactions;

    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getNumCIN(): ?string
    {
        return $this->numCIN;
    }

    public function setNumCIN(string $numCIN): self
    {
        $this->numCIN = $numCIN;

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
            $transaction->setClient($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getClient() === $this) {
                $transaction->setClient(null);
            }
        }

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

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }
}
