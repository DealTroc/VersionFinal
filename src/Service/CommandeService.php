<?php

namespace App\Service;

use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    private $commandeRepository;
    private $entityManager;

    public function __construct(CommandeRepository $commandeRepository, EntityManagerInterface $entityManager)
    {
        $this->commandeRepository = $commandeRepository;
        $this->entityManager = $entityManager;
    }

    public function countCommandesByRole(string $role): int
    {
        return $this->commandeRepository->countCommandesByRole($role);
    }

    public function countCommandesByStatus(string $status): int
    {
        return $this->commandeRepository->countCommandesByStatus($status);
    }

    // public function getNombreCommandesStatus0(): int
    // {
    //     $query = $this->entityManager->createQuery('SELECT COUNT(commande.idCommande) FROM App\Entity\Commande commande WHERE commande.status = 0');
    //     return $query->getSingleScalarResult();
    // }

}
