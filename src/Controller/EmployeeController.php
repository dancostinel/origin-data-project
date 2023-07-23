<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\EmployeeProject;
use App\Entity\Project;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employees')]
class EmployeeController extends AbstractController
{
    #[Route('/', name: 'app_employee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->json($employeeRepository->findAll());
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You are not allowed to use this api')]
    #[Route('/', name: 'app_employee_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $request->get('name', '');
        $companyId = $request->get('company_id', 0);
        $projectId = $request->get('project_id', 0);
        if (empty($name) || empty($companyId)) {
            throw new \InvalidArgumentException('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $company = $entityManager->find(Company::class, $companyId);
        if (!$company) {
            throw new NotFoundHttpException('Company '.$companyId. ' was not found');
        }
        $project = $entityManager->find(Project::class, $projectId);
        if (!$project) {
            throw new NotFoundHttpException('Project '.$projectId. ' was not found');
        }
        $employee = (new Employee())
            ->setName($name)
            ->setCompany($company);
        $employeeProject = (new EmployeeProject())->addProject($project)->addEmployee($employee);
        $employee->addEmployeeProject($employeeProject);

        $entityManager->persist($employeeProject);
        $entityManager->persist($employee);
        $entityManager->flush();

        $responseData = ['message' => 'Employee '.$name.' was saved successfully'];

        return $this->json($responseData, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->json($employee);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You are not allowed to use this api')]
    #[Route('/{id}', name: 'app_employee_edit', methods: ['PATCH'])]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $name = $request->get('name', $employee->getName());
        $companyId = $request->get('company_id', 0);
        if (empty($name) || empty($companyId)) {
            throw new \InvalidArgumentException('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $company = $entityManager->find(Company::class, $companyId);
        if (!$company) {
            throw new NotFoundHttpException('Company '.$companyId. ' was not found');
        }
        $employee
            ->setName($name)
            ->setCompany($company);
        $entityManager->flush();
        $responseData = ['message' => 'Employee '.$name.' was edited successfully'];

        return $this->json($responseData);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You are not allowed to use this api')]
    #[Route('/{id}', name: 'app_employee_delete', methods: ['DELETE'])]
    public function delete(Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($employee);
        $responseData = ['message' => 'Employee '.$employee->getId().' was removed successfully'];
        $entityManager->flush();

        return $this->json($responseData);
    }
}
