<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 160)]
    private ?string $title = null;

    #[ORM\Column(length: 162)]
    private ?string $title_slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $article_date_create = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $article_date_posted = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;

    /**
     * @var Collection<int, Section>
     */
    #[ORM\ManyToMany(targetEntity: Section::class, inversedBy: 'articles')]
    private Collection $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleSlug(): ?string
    {
        return $this->title_slug;
    }

    public function setTitleSlug(string $title_slug): static
    {
        $this->title_slug = $title_slug;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getArticleDateCreate(): ?\DateTimeInterface
    {
        return $this->article_date_create;
    }

    public function setArticleDateCreate(\DateTimeInterface $article_date_create): static
    {
        $this->article_date_create = $article_date_create;

        return $this;
    }

    public function getArticleDatePosted(): ?\DateTimeInterface
    {
        return $this->article_date_posted;
    }

    public function setArticleDatePosted(?\DateTimeInterface $article_date_posted): static
    {
        $this->article_date_posted = $article_date_posted;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
        }

        return $this;
    }

    public function removeSection(Section $section): static
    {
        $this->sections->removeElement($section);

        return $this;
    }

}
