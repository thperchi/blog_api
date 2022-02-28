<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Comment;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class CommentDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Comment;
    }

    public function persist($data, array $context = [])
    {
        $data->setUser($this->security->getUser());
        $data->setCreatedAt(new DateTimeImmutable('now'));
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}