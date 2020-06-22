<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReviewRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 * @ApiResource(
 *          attributes={
 *                  "order":{"pubDate":"desc"}
 * },
 *      normalizationContext={
 *              "groups"={"reviews_read"}
 * }
 * )
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"reviews_read","users_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"reviews_read","users_read"})
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"reviews_read","users_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"reviews_read","users_read"})
     */
    private $pubDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reviews_read"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="concernReviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reviews_read"})
     */
    private $concern;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPubDate(): ?\DateTimeInterface
    {
        return $this->pubDate;
    }

    public function setPubDate(\DateTimeInterface $pubDate): self
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getConcern(): ?User
    {
        return $this->concern;
    }

    public function setConcern(?User $concern): self
    {
        $this->concern = $concern;

        return $this;
    }
}
