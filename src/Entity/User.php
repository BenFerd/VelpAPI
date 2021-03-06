<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *      normalizationContext={
 *              "groups"={"users_read"}
 * },
 *      collectionOperations={"get","post"={
 *              "controller"=App\Controller\UserCreateController::class}
 * }
 * )
 * @ApiFilter(SearchFilter::class)
 * @UniqueEntity("email", message="Cette adresse email est déjà utilisée pour un autre utilisateur.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"users_read","topics_read","reviews_read","messages_read","catrgories_read","adverts_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users_read"})
     * @Assert\NotBlank(message="L'email doit être renseigné.")
     * @Assert\Email(message="L'adresse email n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Mot de passe obligatoire.")
     * @Assert\Length(min=3, minMessage="Le mot de passe doit faire au moins 3 caractères.")
     * @Groups({"users_read"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read","topics_read","reviews_read","messages_read","catrgories_read","adverts_read"})
     * @Assert\NotBlank(message="Ce champs est obligatoire.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read","topics_read","reviews_read","messages_read","catrgories_read","adverts_read"})
     * @Assert\NotBlank(message="Ce champs est obligatoire.")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read"})
     * @Assert\NotBlank(message="Ce champs est obligatoire.")
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"users_read"})
     */
    private $registerDate;

    /**
     * @ORM\OneToMany(targetEntity=Advert::class, mappedBy="user")
     * @Groups({"users_read"})
     * @ApiSubresource
     */
    private $adverts;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="author")
     * @ApiSubresource
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="concern")
     * @Groups({"users_read"})
     * @ApiSubresource
     */
    private $concernReviews;

    /**
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="author")
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="author")
     */
    private $messages;

    public function __construct()
    {
        $this->adverts = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->concernReviews = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->messages = new ArrayCollection();
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * @return Collection|Advert[]
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): self
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts[] = $advert;
            $advert->setUser($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): self
    {
        if ($this->adverts->contains($advert)) {
            $this->adverts->removeElement($advert);
            // set the owning side to null (unless already changed)
            if ($advert->getUser() === $this) {
                $advert->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getConcernReviews(): Collection
    {
        return $this->concernReviews;
    }

    public function addConcernReview(Review $concernReview): self
    {
        if (!$this->concernReviews->contains($concernReview)) {
            $this->concernReviews[] = $concernReview;
            $concernReview->setConcern($this);
        }

        return $this;
    }

    public function removeConcernReview(Review $concernReview): self
    {
        if ($this->concernReviews->contains($concernReview)) {
            $this->concernReviews->removeElement($concernReview);
            // set the owning side to null (unless already changed)
            if ($concernReview->getConcern() === $this) {
                $concernReview->setConcern(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setAuthor($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getAuthor() === $this) {
                $topic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }
}
