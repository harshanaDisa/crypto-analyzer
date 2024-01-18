<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table(name="transaction", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="address_date_asset_version_network_unique", columns={"asset","address","version","network", "confirmed_date"})
 * })
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $asset;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $network;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="datetime")
     */
    private $confirmedDate;

    public function __construct() {
        $this->network = 'main';
        $this->version = 'v1';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsset(): ?string
    {
        return $this->asset;
    }
    public function setAsset(?string $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getConfirmedDate(): ?\DateTimeInterface
    {
        return $this->confirmedDate;
    }

    public function setConfirmedDate(\DateTimeInterface $confirmedDate): self
    {
        $this->confirmedDate = $confirmedDate;

        return $this;
    }

    /**
     * Get the value of network
     */ 
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * Set the value of network
     *
     * @return  self
     */ 
    public function setNetwork($network)
    {
        $this->network = $network;

        return $this;
    }

    /**
     * Get the value of version
     */ 
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the value of version
     *
     * @return  self
     */ 
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
}