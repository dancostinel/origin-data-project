<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table(name: 'employees')]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Ignore]
    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[Ignore]
    #[ORM\ManyToMany(targetEntity: EmployeeProject::class, mappedBy: 'employee')]
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
            $employeeProject->addEmployee($this);
        }

        return $this;
    }

    public function removeEmployeeProject(EmployeeProject $employeeProject): self
    {
        if ($this->employeeProjects->removeElement($employeeProject)) {
            $employeeProject->removeEmployee($this);
        }

        return $this;
    }
}
