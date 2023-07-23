<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/companies')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->json($companyRepository->findAll());
    }

    #[Route('/', name: 'app_company_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $request->get('name', '');
        if (empty($name)) {
            throw new \InvalidArgumentException('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $company = (new Company())->setName($name);
        $entityManager->persist($company);
        $entityManager->flush();
        $responseData = ['message' => 'Company '.$name.' was saved successfully'];

        return $this->json($responseData, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->json($company);
    }

    #[Route('/{id}', name: 'app_company_edit', methods: ['PATCH'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $name = $request->get('name', '');
        if (empty($name)) {
            throw new \InvalidArgumentException('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $company->setName($name);
        $entityManager->flush();

        $responseData = ['message' => 'Company '.$name.' was edited successfully'];

        return $this->json($responseData);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['DELETE'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($company);
        $entityManager->flush();

        $responseData = ['message' => 'Company '.$company->getId().' was removed successfully'];

        return $this->json($responseData);
    }
}
