<?php

namespace App\Controller;

use App\Annotation\Get;
use App\Annotation\Post;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/messages", name: "message_")]
class MessageController extends AbstractController
{
    public function __construct(
        private readonly MessageService $messageService
    ) {
    }

    #[Get('', name: 'index')]
    public function index(): Response
    {
        $messages = $this->messageService->getAllMessages();

        return $this->json($messages, Response::HTTP_OK);
    }

    #[Post('', name: 'create')]
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        $content = $request->get('content', null);

        $message = $this->messageService->create($user, $content);

        return $this->json($message, Response::HTTP_CREATED);

    }
}
