<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\ManyToOne(inversedBy: 'answers')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Questions $quest_id = null;

    // #[ORM\ManyToOne(inversedBy: 'answers')]
    // #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Questions $quest_id = null;


    // #[ORM\JoinColumn(nullable: false)]
    // private ?int $quiz_id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column]
    private ?bool $is_correct = null;

    #[ORM\ManyToOne]
    private ?Quizes $quiz_id = null;

    #[ORM\ManyToMany(targetEntity: QuizParticipant::class, mappedBy: 'answers')]
    private Collection $quizParticipants;

    public function __construct()
    {
        $this->quizParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestId(): ?Questions
    {
        return $this->quest_id;
    }

    public function setQuestId(?Questions $quest_id): self
    {
        $this->quest_id = $quest_id;

        return $this;
    }

    // public function getQuizId(): ?Questions
    // {
    //     return $this->quiz_id;
    // }

    // public function setQuizId(?Questions $quest_id): self
    // {
    //     $this->quiz_id = $quest_id->getId();

    //     return $this;
    // }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function isIsCorrect(): ?bool
    {
        return $this->is_correct;
    }

    public function setIsCorrect(bool $is_correct): self
    {
        $this->is_correct = $is_correct;

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
            $quizParticipant->addAnswer($this);
        }

        return $this;
    }

    public function removeQuizParticipant(QuizParticipant $quizParticipant): self
    {
        if ($this->quizParticipants->removeElement($quizParticipant)) {
            $quizParticipant->removeAnswer($this);
        }

        return $this;
    }
}
