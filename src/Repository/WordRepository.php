<?php
namespace App\Repository;

use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    public function findByWord($word)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.word = :word')
            ->setParameter('word', $word)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
?>