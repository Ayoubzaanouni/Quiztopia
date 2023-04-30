<?php

namespace App\Entity;

use App\Repository\QuizesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizesRepository::class)]
class Quizes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quizes')]
    private ?users $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 5)]
    private ?string $code = null;

    #[ORM\Column]
    private ?bool $is_public = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $max_tries = null;

    #[ORM\ManyToMany(targetEntity: QuizPt::class, mappedBy: 'quiz_id')]
    private Collection $quizPts;

    #[ORM\OneToMany(mappedBy: 'quiz_id', targetEntity: Questions::class, orphanRemoval: true)]
    private Collection $questions;

    public function __construct()
    {
        $this->quizPts = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?users
    {
        return $this->user_id;
    }

    public function setUserId(?users $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setIsPublic(bool $is_public): self
    {
        $this->is_public = $is_public;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getMaxTries(): ?int
    {
        return $this->max_tries;
    }

    public function setMaxTries(?int $max_tries): self
    {
        $this->max_tries = $max_tries;

        return $this;
    }

    /**
     * @return Collection<int, QuizPt>
     */
    public function getQuizPts(): Collection
    {
        return $this->quizPts;
    }

    public function addQuizPt(QuizPt $quizPt): self
    {
        if (!$this->quizPts->contains($quizPt)) {
            $this->quizPts->add($quizPt);
            $quizPt->addQuizId($this);
        }

        return $this;
    }

    public function removeQuizPt(QuizPt $quizPt): self
    {
        if ($this->quizPts->removeElement($quizPt)) {
            $quizPt->removeQuizId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questions $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuizId($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuizId() === $this) {
                $question->setQuizId(null);
            }
        }

        return $this;
    }
}
