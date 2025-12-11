<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
   public function findByPhoneNumber($phoneNumber): ?User
   {    
         return $this->createQueryBuilder('u')
           ->andWhere('u.phoneNumber = :val')
           ->setParameter('val', $phoneNumber)
           ->getQuery()
           ->getOneOrNullResult();
   }

   public function findById($id): ?User
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.id = :val')
           ->setParameter('val', $id)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
