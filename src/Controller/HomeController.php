<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('chat/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/conversation/{id}', name: 'app_chat', requirements: ['id' => '\d+'])]
    public function conversation($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('chat/chat.html.twig', [
            'conversation_id'  => $id
        ]);
    }

    #[Route('/conversation/new', name: 'app_new_chat')]
    public function createConversation(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('chat/createChat.html.twig');
    }

//    #[Route('/chat', name: 'app_chat', methods: ['POST'])]
//    public function chat(HubInterface $hub, Request $request): Response
//    {
//
//        $message = $request->get('mess');
//        $update = new Update(
//            "https://imessage.fr/chat",
//            json_encode(['data' => $message])
//        );
//        $hub->publish($update);
//
//        return $this->redirectToRoute('app_home');
//    }
}
