<?php

namespace App\Security;

use App\Entity\User; // Ajuste si nécessaire
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractLoginFormAuthenticator // Ou AbstractAuthenticator si tu ne veux pas hériter de la logique form_login
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login'; // Le nom de ta route de login

    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Étape 1: Construire le "Passeport" d'authentification.
     * C'est ici qu'on récupère les credentials (username/password) et qu'on charge l'utilisateur.
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username', ''); // Nom du champ username dans ton formulaire
        $password = $request->request->get('_password', ''); // Nom du champ password
        $csrfToken = $request->request->get('_csrf_token'); // Nom du champ CSRF

        return new Passport(
            new UserBadge($username, function (string $userIdentifier) {
                return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $userIdentifier]);

            }),
            new PasswordCredentials($password), // Vérifiera le mot de passe
            [
                new CsrfTokenBadge('authenticate', $csrfToken), // Vérifiera le token CSRF
                new RememberMeBadge(), // Active la fonctionnalité RememberMe si configurée
                // Ajoute d'autres badges si nécessaire (ex: pour 2FA)
            ]
        );
    }

    /**
     * Étape 2: Que faire en cas de succès d'authentification.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Si l'URL demandée avant la connexion était stockée, redirige vers elle
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Sinon, redirige vers une page par défaut (ton 'default_target_path')
        return new RedirectResponse($this->urlGenerator->generate('app_home')); // Adapte la route si besoin
    }

   
    /**
     * Retourne l'URL de la page de login (utilisé par onAuthenticationFailure et l'EntryPoint).
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}