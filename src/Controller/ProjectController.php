<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\EmployeeProject;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->json($projectRepository->findAll());
    }

    #[Route('/', name: 'app_project_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $request->get('name', '');
        $companyId = $request->get('company_id', 0);
        $employeeId = $request->get('employee_id', 0);
        if (empty($name) || empty($companyId)) {
            throw new \InvalidArgumentException('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $company = $entityManager->find(Company::class, $companyId);
        if (!$company) {
            throw new NotFoundHttpException('Company '.$companyId. ' was not found');
        }
        $employee = $entityManager->find(Employee::class, $employeeId);
        if (!$employee) {
            throw new NotFoundHttpException('Employee '.$employeeId. ' was not found');
        }
        $project = (new Project())
            ->setName($name)
            ->setCompany($company);
        $employeeProject = (new EmployeeProject())->addProject($project)->addEmployee($employee);

        $entityManager->persist($employeeProject);
        $entityManager->persist($project);
        $entityManager->flush();

        $responseData = ['message' => 'Project '.$name.' was saved successfully'];

        return $this->json($responseData, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->json($project);
    }

    #[Route('/{id}', name: 'app_project_edit', methods: ['PATCH'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $name = $request->get('name', $project->getName());
        $companyId = $request->get('company_id', 0);
        if (empty($name) || empty($companyId)) {
            throw new \InvalidArgumentException('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $company = $entityManager->find(Company::class, $companyId);
        if (!$company) {
            throw new NotFoundHttpException('Company '.$companyId. ' was not found');
        }
        $project
            ->setName($name)
            ->setCompany($company);
        $entityManager->flush();
        $responseData = ['message' => 'Project '.$name.' was edited successfully'];

        return $this->json($responseData);
    }

    #[Route('/{id}', name: 'app_project_delete', methods: ['DELETE'])]
    public function delete(Project $project, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($project);
        $responseData = ['message' => 'Project '.$project->getId().' was removed successfully'];
        $entityManager->flush();

        return $this->json($responseData);
    }
}
