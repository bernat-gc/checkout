<?php

namespace BGC\Checkout\Shared\Infrastructure\Persistence\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractDoctrineRepository
{
    protected ObjectRepository $repository;

    public function __construct(
        protected readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository($this->className());
    }

    abstract public function className(): string;
}
