<?php

// src/Controller/RegisterController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Util\StringUtil;  // Assurez-vous d'importer le bon namespace
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('Form is submitted and valid.');

            // Convertir le username en camelCase
            $username = $user->getUsername();
            $camelCaseUsername = StringUtil::toCamelCase($username);
            $user->setUsername($camelCaseUsername);

            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            $user->setPrivacyAccepted(true);

            // Assigner la photo de profil par défaut
            $user->setPfp('default_profile.png');

            $entityManager->persist($user);
            $logger->info('User persisted.');
            $entityManager->flush();
            $logger->info('User flushed.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('register/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}


