<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Content required ")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name surname required ")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="comments")
     */
    private $blog;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="parent_id")
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="comment")
     */
    private $parent_id;

    public function __construct()
    {
        $this->parent_id = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->created_at = new \DateTime();
    }

    public function __toString()
    {
        return $this->content;
    }

    public function getComment(): ?self
    {
        return $this->comment;
    }

    public function setComment(?self $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParentId(): Collection
    {
        return $this->parent_id;
    }

    public function addParentId(self $parentId): self
    {
        if (!$this->parent_id->contains($parentId)) {
            $this->parent_id[] = $parentId;
            $parentId->setComment($this);
        }

        return $this;
    }

    public function removeParentId(self $parentId): self
    {
        if ($this->parent_id->removeElement($parentId)) {
            // set the owning side to null (unless already changed)
            if ($parentId->getComment() === $this) {
                $parentId->setComment(null);
            }
        }

        return $this;
    }

}
