<?php

namespace App\Controller;

use App\Entity\User;
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
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
        
       $user = new User();

       $user->setUsername('Gamaliel')
       ->setPassword($this->hasher->hashPassword($user, '1234'))
       ;
        /*
       $this->em->persist($user);

       $this->em->flush();
       */

       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername();

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
