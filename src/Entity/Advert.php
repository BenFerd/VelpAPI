<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdvertRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 * @ApiResource(
 *          attributes={
 *                  "order":{"pubDate":"desc"}
 * },
 *  
 *      normalizationContext={
 *              "groups"={"adverts_read"}
 * },
 *      collectionOperations={"get","put","delete","post"={
 *              "controller"=App\Controller\AdvertCreateController::class}
 * )
 * @ApiFilter(SearchFilter::class, properties={"title"})
 */
class Advert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"adverts_read","users_read","catrgories_read"})
     * @Assert\NotBlank(message="L'annonçe doit comporter un titre.")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"adverts_read","users_read"})
     * @Assert\NotBlank(message="L'annonçe doit comporter une description.")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"adverts_read"})
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"adverts_read","users_read","catrgories_read"})
     */
    private $pubDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adverts")
     * @Groups({"adverts_read","catrgories_read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="adverts")
     * @Groups({"adverts_read","users_read"})
     */
    private $category;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     */
    private $fileUrl;

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * @param string $fileUrl
     * @return Advert
     */
    public function setFileUrl( $fileUrl): Advert
    {
        $this->fileUrl = $fileUrl;
        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return Advert
     */
    public function setFile($file): Advert
    {
        $this->file = $file;
        return $this;
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
