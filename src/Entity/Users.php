<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $user_name = null;


    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Quizes::class)]
    private Collection $quizes;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: QuizParticipant::class)]
    private Collection $quiz_participants;

    public function __construct()
    {
        $this->quiz_participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

/**
     * @return Collection<int, Quizes>
     */
    public function getQuizes(): Collection
    {
        return $this->quizes;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    // profile imagee
    public function getProfileImage()
    {
        return '/images/profile.png';
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
 
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, QuizParticipant>
     */
    public function getQuizParticipant(): Collection
    {
        return $this->quiz_participants;
    }

    public function addQuizParticipant(QuizParticipant $quiz_participants): self
    {
        if (!$this->quiz_participants->contains($quiz_participants)) {
            $this->quiz_participants->add($quiz_participants);
            $quiz_participants->setUserId($this);
        }

        return $this;
    }

    public function removeQuizParticipant(QuizParticipant $quiz_participants): self
    {
        if ($this->quiz_participants->removeElement($quiz_participants)) {
            // set the owning side to null (unless already changed)
            if ($quiz_participants->getUserId() === $this) {
                $quiz_participants->setUserId(null);
            }
        }

        return $this;
    }
}
