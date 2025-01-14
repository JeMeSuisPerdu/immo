<?php

namespace App\Controller;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditProfilType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\SecurityBundle\Security;

class ParametersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('parameters/', name: 'app_user_parameters')]
    public function userParameters(User $user): Response   
    {
        return $this->render('parameters/user_profile.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/parameters/delete-account', name: 'app_user_delete_account')]
    public function deleteAccount(EntityManagerInterface $em, Security $security): RedirectResponse
    {
        /** @var User $user */
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour supprimer votre compte.');
        }

        $currentDate = new \DateTime();
        $deletionDate = (clone $currentDate)->modify('+1 month');

        // Marquer le compte comme désactivé et enregistrer la date de suppression
        $user->setIsActive(false);
        $user->setAccountDeletionDate($deletionDate);

        $em->persist($user);
        $em->flush();

        // Rediriger l'utilisateur vers une page de confirmation
        return $this->redirectToRoute('app_home');
    }


    #[Route('parameters/profil/edit', name: 'app_profil_edit')]
    public function editAccount(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        // Créer un formulaire pour les informations de profil
        $form = $this->createForm(EditProfilType::class, $user);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la page de profil après enregistrement
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('parameters/my_profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('parameters/profil', name: 'app_profil')]
    public function showProfil(): Response
    {
        return $this->render('profil/profil.html.twig');
    }

    #[Route('parameters/profil/edit-pfp', name: 'app_profil_edit_pfp', methods: ['POST'])]
    public function editPfp(Request $request, SluggerInterface $slugger): Response
    {
        // On force Symfony à considérer l'utilisateur comme une instance de App\Entity\User
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            // Si l'utilisateur n'est pas de type User, on retourne une erreur
            return new Response('Utilisateur non reconnu', 403);
        }

        // Vérifier si un fichier est uploadé
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('pfp');

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                // Déplacer l'image uploadée vers le dossier public
                $imageFile->move(
                    $this->getParameter('users_pfp'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer les erreurs
                return new Response('Erreur lors de l\'upload du fichier', 500);
            }

            // Mettre à jour l'utilisateur avec la nouvelle image
            $user->setPfp($newFilename);
            $this->entityManager->persist($user);  // Persiste l'utilisateur
            $this->entityManager->flush();  // Sauvegarde les changements
        }

        return $this->redirectToRoute('app_profil');  // Redirige vers la page de profil après mise à jour
    }

    #[Route('parameters/profil/{id}', name: 'app_user_profile')]
    public function userProfile(User $user): Response   
    {
        return $this->render('profil/user_profile.html.twig', [
            'user' => $user,
        ]);
    }
}
