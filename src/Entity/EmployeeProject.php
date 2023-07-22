<?php

namespace App\Entity;

use App\Repository\EmployeeProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeProjectRepository::class)]
#[ORM\Table(name: 'employees_projects')]
class EmployeeProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'employeeProjects')]
    private Collection $employee;

    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'employeeProjects')]
    private Collection $project;

    public function __construct()
    {
        $this->employee = new ArrayCollection();
        $this->project = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): Collection
    {
        return $this->employee;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employee->contains($employee)) {
            $this->employee->add($employee);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        $this->employee->removeElement($employee);

        return $this;
    }

    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Project $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        $this->project->removeElement($project);

        return $this;
    }
}
