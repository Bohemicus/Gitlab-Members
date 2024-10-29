<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class GitUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $gitGroup = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $gitProject = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGitGroup(): ?string
    {
        return $this->gitGroup;
    }

    public function setGitGroup(string $gitGroup): static
    {
        $this->gitGroup = $gitGroup;

        return $this;
    }

    public function getGitProject(): ?string
    {
        return $this->gitProject;
    }

    public function setGitProject(string $gitProject): static
    {
        $this->gitProject = $gitProject;

        return $this;
    }
}
