<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $season_name;

    /**
     * @ORM\Column(type="string")
     */
    private $image_name;

    /**
     * @ORM\OneToMany(targetEntity=Audio::class, mappedBy="season")
     */
    private $audios;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->audios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Audio[]
     */
    public function getAudios(): Collection
    {
        return $this->audios;
    }

    public function addAudio(Audio $audio): self
    {
        if (!$this->audios->contains($audio)) {
            $this->audios[] = $audio;
            $audio->setSeason($this);
        }

        return $this;
    }

    public function removeAudio(Audio $audio): self
    {
        if ($this->audios->contains($audio)) {
            $this->audios->removeElement($audio);
            // set the owning side to null (unless already changed)
            if ($audio->getSeason() === $this) {
                $audio->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeasonName()
    {
        return $this->season_name;
    }

    /**
     * @param mixed $season_name
     */
    public function setSeasonName($season_name): void
    {
        $this->season_name = $season_name;
    }

    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->image_name;
    }

    /**
     * @param mixed $image_name
     */
    public function setImageName($image_name): void
    {
        $this->image_name = $image_name;
    }




}
