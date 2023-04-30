<?php

namespace App\Entity;

use App\Repository\QuizPtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizPtRepository::class)]
class QuizPt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Quizes::class, inversedBy: 'quizPts')]
    private Collection $quiz_id;

    #[ORM\ManyToOne(inversedBy: 'quizPts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user_id = null;

    #[ORM\Column]
    private ?int $nbr_tries = null;

    #[ORM\Column]
    private ?float $score = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $joined_at = null;

    public function __construct()
    {
        $this->quiz_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Quizes>
     */
    public function getQuizId(): Collection
    {
        return $this->quiz_id;
    }

    public function addQuizId(Quizes $quizId): self
    {
        if (!$this->quiz_id->contains($quizId)) {
            $this->quiz_id->add($quizId);
        }

        return $this;
    }

    public function removeQuizId(Quizes $quizId): self
    {
        $this->quiz_id->removeElement($quizId);

        return $this;
    }

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(?Users $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getNbrTries(): ?int
    {
        return $this->nbr_tries;
    }

    public function setNbrTries(int $nbr_tries): self
    {
        $this->nbr_tries = $nbr_tries;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joined_at;
    }

    public function setJoinedAt(\DateTimeImmutable $joined_at): self
    {
        $this->joined_at = $joined_at;

        return $this;
    }
}
