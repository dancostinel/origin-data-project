<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'projects')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Company $company = null;

    #[ORM\ManyToMany(targetEntity: EmployeeProject::class, mappedBy: 'project')]
    private Collection $employeeProjects;

    public function __construct()
    {
        $this->employeeProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getEmployeeProjects(): Collection
    {
        return $this->employeeProjects;
    }

    public function addEmployeeProject(EmployeeProject $employeeProject): self
    {
        if (!$this->employeeProjects->contains($employeeProject)) {
            $this->employeeProjects->add($employeeProject);
            $employeeProject->addProject($this);
        }

        return $this;
    }

    public function removeEmployeeProject(EmployeeProject $employeeProject): self
    {
        if ($this->employeeProjects->removeElement($employeeProject)) {
            $employeeProject->removeProject($this);
        }

        return $this;
    }
}
