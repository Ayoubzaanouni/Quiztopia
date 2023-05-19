<?php

namespace App\Entity;

use App\Repository\QuizesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;

#[ORM\Entity(repositoryClass: QuizesRepository::class)]
class Quizes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quizes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user_id = null;

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


    #[ORM\OneToMany(mappedBy: 'quiz_id', targetEntity: Questions::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'quiz_id', targetEntity: QuizParticipant::class, orphanRemoval: true)]
    private Collection $quizParticipants;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->quizParticipants = new ArrayCollection();
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

    public function getCreatedAt1(): ?String
    {
        $date = $this->created_at;
        $dateString = $date->format('Y-m-d H:i:s');

        return $dateString;
    }

    public function setCreatedAt(): self
{
    $this->created_at = new \DateTimeImmutable();
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

    /**
     * @return Collection<int, QuizParticipant>
     */
    public function getQuizParticipants(): Collection
    {
        return $this->quizParticipants;
    }

    public function addQuizParticipant(QuizParticipant $quizParticipant): self
    {
        if (!$this->quizParticipants->contains($quizParticipant)) {
            $this->quizParticipants->add($quizParticipant);
            $quizParticipant->setQuizId($this);
        }

        return $this;
    }

    public function removeQuizParticipant(QuizParticipant $quizParticipant): self
    {
        if ($this->quizParticipants->removeElement($quizParticipant)) {
            // set the owning side to null (unless already changed)
            if ($quizParticipant->getQuizId() === $this) {
                $quizParticipant->setQuizId(null);
            }
        }

        return $this;
    }
}
