<?php

namespace App\Repository;

use App\Entity\Devices;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devices>
 *
 * @method Devices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devices[]    findAll()
 * @method Devices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devices::class);
        $this->registry = $registry;
    }

}
