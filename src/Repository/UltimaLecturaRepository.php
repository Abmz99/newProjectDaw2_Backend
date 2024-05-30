<?php
// src/Repository/UltimaLecturaRepository.php
namespace App\Repository;

use App\Entity\UltimaLectura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UltimaLectura|null find($id, $lockMode = null, $lockVersion = null)
 * @method UltimaLectura|null findOneBy(array $criteria, array $orderBy = null)
 * @method UltimaLectura[]    findAll()
 * @method UltimaLectura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UltimaLecturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UltimaLectura::class);
    }

    
}
