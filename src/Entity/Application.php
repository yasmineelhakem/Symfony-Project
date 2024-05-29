<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?job $job = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?user $jobseeker = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?job
    {
        return $this->job;
    }

    public function setJob(?job $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getJobseeker(): ?user
    {
        return $this->jobseeker;
    }

    public function setJobseeker(?user $jobseeker): static
    {
        $this->jobseeker = $jobseeker;

        return $this;
    }
}
