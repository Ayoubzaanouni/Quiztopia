<?php

namespace App\Repository;

use App\Entity\Quizes;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizes>
 *
 * @method Quizes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizes[]    findAll()
 * @method Quizes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizes::class);
    }

    public function save(Quizes $entity,Users $user, bool $flush = false): void
    {
        $code = bin2hex(random_bytes(4));
        $code = substr($code, 0, 5);
        $entity->setCode($code);
        $entity->setCreatedAt();
        $entity->setUserId($user);
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quizes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Quizes[] Returns an array of Quizes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quizes
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
