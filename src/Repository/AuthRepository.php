<?php

namespace App\Repository;

use App\Entity\Devices;
use App\Entity\Auth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auth>
 *
 * @method Auth|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auth|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auth[]    findAll()
 * @method Auth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthRepository extends ServiceEntityRepository
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auth::class);
        $this->registry = $registry;
    }

    public function addAuth( $AuthData , $uuid ): array
    {
            $em = $this->registry->getManager();
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);

            try {
                $NewAuth = new Auth();
                $NewAuth->setSalt($this->getSalt(trim($AuthData['pass'])));
                $NewAuth->setBlocked(false);

                $em->persist($NewAuth);
                $em->flush();

                $NewDevice = new Devices();
                $NewDevice->setUpdated();
                $NewDevice->setUuid($uuid);
                $NewDevice->setAuthid($NewAuth->getId());
                $NewDevice->setBlocked(false);
                $em->persist($NewDevice);
                $em->flush();

                $em->getConnection()->commit();

                return array(
                    'success'=>true,
                    'id'=>$NewAuth->getId()
                );
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=>'To register a new user, you need to log out of your current account.');
            }
    }

    public function resetAuth( $AuthData , $password ): array
    {
            $id = $AuthData['authid'];
            $em = $this->registry->getManager();
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);

            try {

                $NewAuth =  $this->getAuthByID($id);
                $NewAuth->setSalt($this->getSalt(trim($password)));

                $em->persist($NewAuth);
                $em->flush();

                $em->getConnection()->commit();

                return array(
                    'success'=>true,
                    'id'=>$NewAuth->getId()
                );
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=>'To register a new password id is not possible.');
            }
    }

    public function getAuthByID( $id): ?Auth
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.id = :id')
            ->andWhere('d.blocked != :blocked')
            ->setParameter('blocked', 1)
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function checkAuth( $isAuthMode,$password,$uuid): array
    {
        if(empty($isAuthMode)){
            return array('error'=>'Password or login incorrect');
        }
        $Auth =  $this->getAuthByID($isAuthMode->getAuthid());
        if(!empty($Auth)){
            if( $this->checkPass($password,$Auth->getSalt())){
                $em = $this->registry->getManager();
                $em->getConnection()->beginTransaction();
                $em->getConnection()->setAutoCommit(false);
                $NewDevice = new Devices();
                $NewDevice->setUpdated();
                $NewDevice->setUuid($uuid);
                $NewDevice->setAuthid($isAuthMode->getAuthid());
                $NewDevice->setBlocked(false);
                $em->persist($NewDevice);
                $em->flush();
                $em->getConnection()->commit();
                return array(
                    'success'=>true,
                    'id'=>$Auth->getId()
                );
            }else{
                return array('error'=>'Password or mail incorrect');
            }
        }else{
            return array('error'=>'Account not found');
        }
    }

    public function getSalt($password): string
    {
        return password_hash(trim($password), PASSWORD_BCRYPT);
    }

    public function checkPass($pass,$salt): string
    {
        return hash_equals($salt, crypt(trim($pass), $salt));
    }


}
