<?php
// src/Controller/MessageController.php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MessageController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/message/edit/{id}', name: 'app_edit_message')]
    public function editMessage(Message $message, Request $request): Response
    {
        if ($message->getSender() !== $this->getUser()) {
            throw new AccessDeniedException('You cannot edit this message.');
        }

        $form = $this->createFormBuilder($message)
            ->add('content')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Message updated successfully.');

            return $this->redirectToRoute('app_view_conversation', ['conversation' => $message->getConversation()->getId()]);
        }

        return $this->render('message/edit.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

    #[Route('/message/delete/{id}', name: 'app_delete_message')]
    public function deleteMessage(Message $message): Response
    {
        if ($message->getSender() !== $this->getUser()) {
            throw new AccessDeniedException('You cannot delete this message.');
        }

        $this->entityManager->remove($message);
        $this->entityManager->flush();
        $this->addFlash('success', 'Message deleted successfully.');

        return $this->redirectToRoute('app_view_conversation', ['conversation' => $message->getConversation()->getId()]);
    }
}
