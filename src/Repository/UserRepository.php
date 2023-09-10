<?php

namespace App\Repository;

use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    public function getAllActiveUsersCreatedInLastWeek(): array
    {
        $now = Carbon::now();
        $oneWeekAgo = $now->copy()->subWeek();

        $qb = $this->createQueryBuilder('u');

        $qb->where('u.createdAt > :weekAgo')
            ->andWhere('u.active = :isActive')
            ->setParameter('weekAgo', $oneWeekAgo)
            ->setParameter('isActive', true);

        return $qb->getQuery()->getResult();
    }
}
