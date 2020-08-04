<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    const COMMENT_FOR_PODCAST = 'podcast';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $realization_date;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment_for;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Comments constructor.
     * @param $realization_date
     * @param $comment_for
     * @param $user
     */
    public function __construct($realization_date, $comment_for, $user)
    {
        $this->realization_date = $realization_date;
        $this->comment_for = $comment_for;
        $this->user = $user;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealizationDate(): ?\DateTimeInterface
    {
        return $this->realization_date;
    }

    public function setRealizationDate(\DateTimeInterface $realization_date): self
    {
        $this->realization_date = $realization_date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCommentFor(): ?string
    {
        return $this->comment_for;
    }

    public function setCommentFor(string $comment_for): self
    {
        $this->comment_for = $comment_for;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
