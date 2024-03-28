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
class DevicesRepository extends ServiceEntityRepository
{
    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devices::class);
        $this->registry = $registry;
    }

    public function getDevice ($post): ?int
    {
        $Device = $this->findOneByUuid($post['uuid']);
        $userid = 0;
        if ($Device) {
            $em = $this->registry->getManager();
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);

            $Device->setUpdated();
            $em->persist($Device);
            $em->flush();
            $em->getConnection()->commit();

            $userid = $Device->getAuthid();
        }
        return $userid;
    }

    public function findOneByUuid($uuid): ?Devices
    {
        $monday = new DateTime("now");
        $monday->modify('-1 month');

        return $this->createQueryBuilder('d')
            ->andWhere('d.uuid = :uuid')
            ->andWhere('d.updated > :month')
            ->andWhere('d.blocked != :blocked')
            ->setParameter('blocked', 1)
            ->setParameter('month', $monday)
            ->setParameter('uuid', $uuid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    function login($url, $data): array
    {

        $data['login'] =  trim(strtolower($data['login']));
        if($data['login']===''){
            return array('error'=>'Mail field cannot be empty');
        }
        if(trim($data['pass'])===''){
            return array('error'=>'Password field cannot be empty');
        }

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'timeout' => 8,
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ),
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $context = stream_context_create($opts);
        $resp = json_decode(file_get_contents($url, false, $context),true);

        if(!empty($resp['success']) && $resp['success']){

            $em = $this->registry->getManager();
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);

            try {

                $NewDevice = new Devices();

                $NewDevice->setUpdated();
                $NewDevice->setUuid($data['uuid']);
                $NewDevice->setAuthid($resp['id']);
                $NewDevice->setBlocked(false);

                $em->persist($NewDevice);
                $em->flush();
                $em->getConnection()->commit();

                return $resp;
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=>'Unknown error when adding an authorization device');
            }

        }

        return array('error'=>'Password or mail incorrect');
    }

    function profile($url, $data): array
    {
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'timeout' => 8,
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ),
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $context = stream_context_create($opts);
        $resp = json_decode(file_get_contents($url, false, $context),true);

//        $dump = var_export($resp, true);
//        file_put_contents(__DIR__ . '/log.txt', $dump . PHP_EOL, FILE_APPEND);
        if(!empty($resp['success']) && $resp['success']){

            $em = $this->registry->getManager();
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);

            try {

                $NewDevice = new Devices();

                $NewDevice->setUpdated();
                $NewDevice->setUuid($data['uuid']);
                $NewDevice->setAuthid($resp['id']);
                $NewDevice->setBlocked(false);

                $em->persist($NewDevice);
                $em->flush();
                $em->getConnection()->commit();

                return $resp;
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=>'Unknown error when adding an registration device');
            }

        }
        return $resp;
    }

    function delete_profile($data): array
    {
        //$dump = var_export($data['uuid'], true);
        //file_put_contents(__DIR__ . '/log.txt', $dump . PHP_EOL, FILE_APPEND);

        $Device = $this->findOneByUuid($data['uuid']);
        if ($Device) {
            try {
                $em = $this->registry->getManager();
                $em->getConnection()->beginTransaction();
                $em->getConnection()->setAutoCommit(false);

                $em->remove($Device);
                $em->flush();
                $em->getConnection()->commit();
                return array(
                    'success'=>true
                );
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=>'Unknown error when remove an registration device');
            }
        }

        return array(
            'error'=>'device not found'
        );
    }
}
