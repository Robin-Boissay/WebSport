<?php

namespace App\Controller;

use App\Entity\TypeActiviter;
use App\Form\TypeActiviterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TypeActiviterController extends AbstractController
{
    #[Route('/type-activiter', name: 'app_type_activiter')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $typeActiviters = $em->getRepository(TypeActiviter::class)->findAll();

        return $this->render('type_activiter/index.html.twig', [
            'typeActiviters' => $typeActiviters,
        ]);
    }

    #[Route('/type-activiter/create', name: 'app_type_activiter_create')]
    public function createTypeActiviter(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(TypeActiviterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typeActiviter = $form->getData();
            $em->persist($typeActiviter);
            $em->flush();
            return $this->redirectToRoute('app_activiter');
        }

        return $this->render('type_activiter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/type-activiter/{id}/edit', name: 'app_type_activiter_edit')]
    public function editTypeActiviter(TypeActiviter $typeActiviter, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(TypeActiviterType::class, $typeActiviter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typeActiviter = $form->getData();
            $em->persist($typeActiviter);
            $em->flush();
            return $this->redirectToRoute('app_activiter');
        }

        return $this->render('type_activiter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
