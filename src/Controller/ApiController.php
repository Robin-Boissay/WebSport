<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ApiController extends AbstractController
{

    public function __construct(private HttpClientInterface $client){}

    #[Route('/api/getLikes/{id}', name: 'app_api')]
    public function index(int $placeId): Response
    {
        try {
            // Étape 1: Obtenir l'Universe ID à partir du Place ID
            $universeResponse = $this->client->request('GET', "https://apis.roblox.com/universes/v1/places/{$placeId}/universe");

            if ($universeResponse->getStatusCode() !== 200) {
                return new JsonResponse(['error' => 'Could not find universe for the given placeId.'], 404);
            }

            $universeData = $universeResponse->toArray();
            $universeId = $universeData['universeId'];

            // Étape 2: Obtenir les votes en utilisant l'Universe ID
            $votesResponse = $this->client->request('GET', "https://games.roblox.com/v1/games/votes?universeIds={$universeId}");

            if ($votesResponse->getStatusCode() !== 200) {
                return new JsonResponse(['error' => 'Could not fetch votes data.'], 500);
            }

            // Étape 3: Retourner directement la réponse de l'API des votes
            $votesData = $votesResponse->toArray();
            
            // La réponse contient un tableau "data", on retourne le premier élément
            return new JsonResponse($votesData['data'][0] ?? []);

        } catch (\Exception $e) {
            // Gérer les erreurs (API indisponible, format invalide, etc.)
            return new JsonResponse(['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }
}
