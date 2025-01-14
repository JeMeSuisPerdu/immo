<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Entity\Message;
use App\Form\SearchUserType;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConversationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/conversations', name: 'app_conversations')]
    public function index(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository): Response
    {
        $searchForm = $this->createForm(SearchUserType::class);
        $searchForm->handleRequest($request);

        $users = [];
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $username = $searchForm->get('username')->getData();
            if (!empty($username)) {
                $users = $userRepository->findByUsername($username);
            }
        }

        $currentUser = $this->getUser();
        $conversations = $conversationRepository->findByUser($currentUser);

        $conversation = null;
        $conversationId = $request->query->get('conversation');
        if ($conversationId) {
            $conversation = $conversationRepository->find($conversationId);
            if (!$conversation || !$conversation->getUsers()->contains($currentUser)) {
                throw $this->createAccessDeniedException('You are not allowed to view this conversation.');
            }
        }

        return $this->render('conversation/view.html.twig', [
            'conversations' => $conversations,
            'conversation' => $conversation,
            'users' => $users,
            'search_form' => $searchForm->createView(),
        ]);
    }

    #[Route('/conversations/start/{user}', name: 'app_start_conversation')]
    public function startConversation(User $user, ConversationRepository $conversationRepository): Response
    {
        $currentUser = $this->getUser();
        $existingConversation = $conversationRepository->findOneByUsers([$currentUser, $user]);

        if (!$existingConversation) {
            $conversation = new Conversation();
            $conversation->addUser($currentUser);
            $conversation->addUser($user);

            $this->entityManager->persist($conversation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Conversation started with ' . $user->getUsername());
        }

        return $this->redirectToRoute('app_conversations', ['conversation' => $existingConversation ? $existingConversation->getId() : $conversation->getId()]);
    }

    #[Route('/conversations/send/{conversation}', name: 'app_send_message', methods: ['POST'])]
    public function sendMessage(Request $request, Conversation $conversation): Response
    {
        $content = $request->request->get('message');
        if (empty($content)) {
            $this->addFlash('error', 'Message cannot be empty.');
            return $this->redirectToRoute('app_conversations', ['conversation' => $conversation->getId()]);
        }

        $user = $this->getUser();
        $message = new Message();
        $message->setContent($content);
        $message->setSender($user);
        $message->setConversation($conversation);
        $message->setCreatedAt(new \DateTime());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $this->addFlash('success', 'Message sent successfully.');
        return $this->redirectToRoute('app_conversations', ['conversation' => $conversation->getId()]);
    }

    #[Route('/conversations/delete/{conversation}', name: 'app_delete_conversation', methods: ['POST'])]
    public function deleteConversation(Conversation $conversation): Response
    {
        if (!$conversation->getUsers()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('You are not allowed to delete this conversation.');
        }

        $this->entityManager->remove($conversation);
        $this->entityManager->flush();

        $this->addFlash('success', 'Conversation deleted successfully.');
        return $this->redirectToRoute('app_conversations');
    }
}
