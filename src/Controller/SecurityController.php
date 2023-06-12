<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DiplomeRepository;
use App\Repository\EtatCivilRepository;
use App\Repository\FonctionRepository;
use App\Repository\UserRepository;
use App\Service\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $hasher, private EntityManagerInterface $em) {}

    #[Route('/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils, Configuration $config): Response
    {
        // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();

        $config->createIfNoDiplome();
        $config->createIfNoFonction();
        $config->createIfNoMaritalStatus();
        $config->createIfNoUser();

       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername();

       if($lastUsername) {
          return $this->redirectToRoute('home_index');
       }

        return $this->render('auth/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error
            ]
        );
    }

    #[Route('/logout', name: 'security_logout')]
    public function logout() {

    }

}
