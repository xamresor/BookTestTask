<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllAuthors(): array
    {
        $result = $this->createQueryBuilder('u1')
            ->select('
                u1.name as name,
                (SELECT count(1) FROM App\Entity\Book b WHERE b.user_id = u1.id) as book_count,
                u2.name as inviter_name
            ')
            ->leftJoin(User::class, 'u2', Join::WITH, 'u1.invited_by_user_id = u2.id')
            ->orderBy('u1.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findAllInviters(): array
    {
        $result = $this->createQueryBuilder('u1')
            ->select('
                u2.id as id, 
                u2.name name, 
                u1.id as author_id, 
                u1.name as author_name, 
                b.id as book_id, 
                b.rating as rating
            ')
            ->leftJoin(User::class, 'u2', Join::WITH, 'u1.invited_by_user_id = u2.id')
            ->leftJoin(Book::class, 'b', Join::WITH, 'b.user_id = u1.id')
            ->where('u1.id', 'ASC')
            ->where("u2.id IS NOT NULL")
            ->getQuery()
            ->getResult();

        return self::countAndMergeInviterRawData($result);
    }

    private static function countAndMergeInviterRawData($rawData) {
        $mergeData = [];

        //Rows merge
        foreach ($rawData as $inviterRecord) {
            $data = $mergeData[$inviterRecord['id']] ?? [];
            $data['name'] = $inviterRecord['name'];

            //Data Merge
            $data['authors'][$inviterRecord['author_id']] = $inviterRecord['author_name'];
            $data['books'][$inviterRecord['book_id']] = $inviterRecord['author_id'];
            $data['rating'][$inviterRecord['book_id']] = $inviterRecord['rating'];

            $mergeData[$inviterRecord['id']] = $data;
        }

        //Data count
        $result = array_values(array_map(function ($inviterRecord) {
            return [
               'name' => $inviterRecord['name'],
               'author_count' => count($inviterRecord['authors']),
               'book_count' => count($inviterRecord['books']),
               'rating' => array_sum($inviterRecord['rating']),
            ];
        }, $mergeData));

        usort($result, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });

        return $result;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
