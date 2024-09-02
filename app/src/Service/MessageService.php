<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageService
{
    public function __construct(
        private readonly MessageRepository $messageRepository
    ) {
    }

    /**
     * @return Collection
     */
    public function getAllMessages(): Collection
    {
      return $this->messageRepository->getAll();
    }

    /**
     * @param UserInterface $user
     * @param string $content
     * @return Message
     */
    public function create(UserInterface $user, string $content): Message
    {
        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);

        $this->messageRepository->save($message, true);

        return $message;
    }
}