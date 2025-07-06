<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Activiter;
use App\Form\ActiviterType;
use App\Form\ActiviterType2;
use App\Entity\TypeActiviter;
use App\Repository\ActiviterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ActiviterController extends AbstractController
{


    #[Route('/activiter', name: 'app_activiter')]
    public function index(ActiviterRepository $activiterRepository): Response
    {
        $activiters = $activiterRepository->findAllByUser($this->getUser());

        return $this->render('activiter/index.html.twig', [
            'activiters' => $activiters,
        ]);
    }
    #[Route('/activiter/showActiver/{id}', name: 'app_activiter_show')]
    public function showActiviterUserId(User $user, ActiviterRepository $activiterRepository): Response
    {
        $activiters = $activiterRepository->findAllByUser($user);

        return $this->render('activiter/index.html.twig', [
            'activiters' => $activiters,
        ]);
    }


    #[Route('/activiter/create', name: 'app_activiter_create')]
    public function createActiviter( Request $request, EntityManagerInterface $em, #[CurrentUser] ?User $user): Response
    {

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour créer une activité.');
            return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion
       }

       $activiter = new Activiter();
       // Associer l'utilisateur connecté
       $activiter->setUser($user);
       // Tu peux pré-remplir created_at ici si tu ne le fais pas ailleurs
       // $activiter->setCreatedAt(new \DateTimeImmutable());

       $form = $this->createForm(ActiviterType::class, $activiter);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
            $activiter->setCreatedAt(new \DateTimeImmutable());
           $em->persist($activiter);
           $em->flush();

           $this->addFlash('success', 'Activité enregistrée avec succès !');

           // Redirige vers la page de l'activité créée ou la liste
           return $this->redirectToRoute('app_home');
       }

       return $this->render('activiter/create.html.twig', [
           'activiter' => $activiter,
           'form' => $form->createView(), // Utilise createView() pour le template
       ]);
    }

    #[Route('/activiter/create/{activiter}', name: 'app_activiter_create_final')]
    public function createActiviterFinal(Activiter $activiter): Response
    {
        return $this->render('activiter/create.html.twig', [
            'activiter' => $activiter,
        ]);
    }


    #[Route('/api/type-activiter/{id}/proprietes', name: 'api_type_activiter_proprietes', methods: ['GET'])]
    public function getProprietes(TypeActiviter $typeActiviter): JsonResponse
    {
        $proprietesData = [];

        // Supposons une relation $typeActiviter->getProprieterActiviters()
        // qui retourne une collection d'objets ProprieterActiviter
        // Adapte ceci selon le nom exact de ta relation !
        foreach ($typeActiviter->getProprieter() as $propriete) {
            $proprietesData[] = [
                'id' => $propriete->getId(),
                'nom' => $propriete->getNomProprieter(), // Adapte si le nom est différent
                'unit' => $propriete->getUnit(),
                // Ajoute d'autres infos si nécessaire (unit, data_type...)
            ];
        }

        // Si tu n'as pas de relation directe, tu devras injecter l'EntityManager
        // et faire une requête DQL ou QueryBuilder sur ta table pivot.

        return $this->json($proprietesData);
    }
}
