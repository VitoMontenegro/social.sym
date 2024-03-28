<?php

namespace App\Repository;

use App\Entity\UserProfiles;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProfiles>
 *
 * @method UserProfiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfiles[]    findAll()
 * @method UserProfiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfilesRepository extends ServiceEntityRepository
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfiles::class);
        $this->registry = $registry;
    }


    public function getProfile( $userid ): array
    {
        $IsProfile = $this->getProfileByUserID($userid);
        if(!empty($IsProfile)){
            return array(
                'success'=>true,
                'name'=>$IsProfile->getName(),
                'surname'=>$IsProfile->getSurname(),
                'patronymic'=>$IsProfile->getPatronymic(),
                'gender'=>$IsProfile->getGender(),
                'birthday'=>$IsProfile->getBirthday()
            );
        }else{
            return array('error'=>'Profile not found');
        }
    }

    public function getProfileByUserID( $userid): ?UserProfiles
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.authid = :authid')
            ->setParameter('authid', $userid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * @throws \Exception
     */
    function addProfile($param, $userid): array
    {
        $name =  isset($param['name']) ? trim(strtolower($param['name'])) : '';
        $surname =  isset($param['surname']) ? trim(strtolower($param['surname'])) : '';
        $patronymic =  isset($param['patronymic']) ? trim(strtolower($param['patronymic'])) : '';
        $gender =  isset($param['gender']) ? trim(strtolower($param['gender'])) : '';
        $birthday =  isset($param['birthday']) ? trim(strtolower($param['birthday'])) : '';
        $phone =  isset($param['phone']) ? trim(strtolower($param['phone'])) : '';

        //$dump = var_export($param, true);
        //file_put_contents(__DIR__ . '/log.txt', $dump . PHP_EOL, FILE_APPEND);
        if(!$name)
            return array('error'=>'Name field cannot be empty');

        if(!$surname)
            return array('error'=>'surname field cannot be empty');

        if(!$gender)
            return array('error'=>'gender field cannot be empty');

        if(!$birthday) {
            return array('error'=>'birthday field cannot be empty');
        } else {
            $date = new DateTime($param['birthday']);
            $birthday = $date->getTimestamp();
        }




        $em = $this->registry->getManager();
        try {
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);

            $NewProfile= new UserProfiles();

            $NewProfile->setAuthid($userid);
            $NewProfile->setName($name);
            $NewProfile->setSurname($surname);
            $NewProfile->setPatronymic($patronymic);
            $NewProfile->setGender($gender);
            $NewProfile->setBirthday($birthday);
            $NewProfile->setPhone($phone);

            $em->persist($NewProfile);

            $em->flush();

            $em->getConnection()->commit();


            return array('success'=>true);
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            return array('error'=>'Unknown error when adding an user profile');
        }

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
