<?php
namespace App\Repository;

use App\Entity\ObraGeneros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ObraGenerosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObraGeneros::class);
    }

    public function findGeneroByObraId(int $obraId): array
    {
        return $this->createQueryBuilder('og')
            ->select('g.id as id', 'g.nombre as nombre') // Seleccionar solo el ID y el nombre del género
            ->innerJoin('og.idGenero', 'g')
            ->where('og.id = :obraId')
            ->setParameter('obraId', $obraId)
            ->getQuery()
            ->getResult();
    }
    
}
