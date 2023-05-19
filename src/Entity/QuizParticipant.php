<?php

namespace App\Entity;

use App\Repository\QuizParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizParticipantRepository::class)]
class QuizParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\ManyToOne(inversedBy: 'quiz_id')]
    #[ORM\ManyToOne(inversedBy: 'quizParticipants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'quizParticipants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quizes $quiz_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbr_tries = null;

    #[ORM\Column(nullable: true)]
    private ?float $score = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $joined_at = null;

    #[ORM\ManyToMany(targetEntity: Answers::class, inversedBy: 'quizParticipants')]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuizId(): ?Quizes
    {
        return $this->quiz_id;
    }

    public function setQuizId(?Quizes $quiz_id): self
    {
        $this->quiz_id = $quiz_id;

        return $this;
    }

    public function getNbrTries(): ?int
    {
        return $this->nbr_tries;
    }

    public function setNbrTries(?int $nbr_tries): self
    {
        $this->nbr_tries = $nbr_tries;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joined_at;
    }

    public function setJoinedAt(?\DateTimeImmutable $joined_at): self
    {
        $this->joined_at = $joined_at;

        return $this;
    }

    /**
     * @return Collection<int, Answers>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        $this->answers->removeElement($answer);

        return $this;
    }
}
