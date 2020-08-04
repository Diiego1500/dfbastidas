<?php

namespace App\Entity;

use App\Repository\PurchasedservicesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchasedservicesRepository::class)
 */
class Purchasedservices
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $podcast;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $purchased_date_podcast;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="purchasedservices", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Purchasedservices constructor.
     * @param $podcast
     * @param $purchased_date_podcast
     * @param $user
     */
    public function __construct($podcast, $purchased_date_podcast, $user)
    {
        $this->podcast = $podcast;
        $this->purchased_date_podcast = $purchased_date_podcast;
        $this->user = $user;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPodcast(): ?bool
    {
        return $this->podcast;
    }

    public function setPodcast(bool $podcast): self
    {
        $this->podcast = $podcast;

        return $this;
    }

    public function getPurchasedDatePodcast(): ?\DateTimeInterface
    {
        return $this->purchased_date_podcast;
    }

    public function setPurchasedDatePodcast(\DateTimeInterface $purchased_date_podcast): self
    {
        $this->purchased_date_podcast = $purchased_date_podcast;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
