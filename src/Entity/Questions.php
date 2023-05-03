<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quizes $quiz_id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    // #[ORM\OneToMany(mappedBy: 'quest_id', targetEntity: Answers::class, orphanRemoval: true)]
    // private Collection $answers;
//     #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answers::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
// private Collection $answers;
#[ORM\OneToMany(mappedBy: 'quest_id', targetEntity: Answers::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
private Collection $answers;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
            $answer->setQuestId($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestId() === $this) {
                $answer->setQuestId(null);
            }
        }

        return $this;
    }
}
