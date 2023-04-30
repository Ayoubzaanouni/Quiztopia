<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $user_name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Quizes::class)]
    private Collection $quizes;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: QuizPt::class)]
    private Collection $quizPts;

    public function __construct()
    {
        $this->quizes = new ArrayCollection();
        $this->quizPts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    /**
     * @return Collection<int, Quizes>
     */
    public function getQuizes(): Collection
    {
        return $this->quizes;
    }

    public function addQuize(Quizes $quize): self
    {
        if (!$this->quizes->contains($quize)) {
            $this->quizes->add($quize);
            $quize->setUserId($this);
        }

        return $this;
    }

    public function removeQuize(Quizes $quize): self
    {
        if ($this->quizes->removeElement($quize)) {
            // set the owning side to null (unless already changed)
            if ($quize->getUserId() === $this) {
                $quize->setUserId(null);
            }
        }

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
            $quizPt->setUserId($this);
        }

        return $this;
    }

    public function removeQuizPt(QuizPt $quizPt): self
    {
        if ($this->quizPts->removeElement($quizPt)) {
            // set the owning side to null (unless already changed)
            if ($quizPt->getUserId() === $this) {
                $quizPt->setUserId(null);
            }
        }

        return $this;
    }
}
