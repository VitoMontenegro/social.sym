<?php

namespace App\Repository;

use App\Entity\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Email>
 *
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepository
{
    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
        $this->registry = $registry;
    }

    public function confirmEmail( $email , $code, $userid ): array
    {
        $em = $this->registry->getManager();
        $IsEmail = $this->getMail($email);

        if(!empty($IsEmail)){
            if($IsEmail->getAuthid() !== 0){
                return array('error'=>'Email "'.$email.'" is already in use');
            }else{
                try {
                    if($IsEmail->getCod() !== (int)$code){
                        return array('error'=>'Code validation failed');
                    }
                    $em->getConnection()->beginTransaction();
                    $em->getConnection()->setAutoCommit(false);
                    $IsEmail->setAuthid($userid);
                    $em->persist($IsEmail);
                    $em->flush();
                    $em->getConnection()->commit();
                    return array('success'=>true);
                } catch (\Exception $e) {
                    $em->getConnection()->rollBack();
                    return array('error'=> $e->getMessage());
                }
            }
        }else{
            return array('error'=>'Email "'.$email.'" not found');
        }
    }

    public function addEmail( $email ): array
    {
        $em = $this->registry->getManager();
        $IsEmail = $this->getMail($email);

        if(!empty($IsEmail) && $IsEmail->getAuthid() !== 0){
            return array('error'=>'Email "'.$email.'" is already in use');
        }else{
            try {
                $code = $this->RequestMail($email);
                if($code){
                    $em->getConnection()->beginTransaction();
                    $em->getConnection()->setAutoCommit(false);
                    if(empty($IsEmail)){
                        $IsEmail = new Email();
                    }
                    $IsEmail->setEmail($email);
                    $IsEmail->setCod($code);
                }else{
                    return array('error'=>'Send mail is not possible');
                }
                $IsEmail->setAuthid(0);
                $em->persist($IsEmail);
                $em->flush();
                $em->getConnection()->commit();
                return array('success'=>true);
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=> $e->getMessage(),'p'=>$email);
            }
        }
    }

    public function addResetEmail( $email ): array
    {
        $em = $this->registry->getManager();
        $IsEmail = $this->getMail($email);

        if(empty($IsEmail) || $IsEmail->getAuthid() === 0){
            return array(
                'error'=>'Email "'.$email.'" is not registered',
                'code' => 'wrong_email'
            );
        }else{
            try {
                $code = $this->RequestMail($email);
                if($code){
                    $em->getConnection()->beginTransaction();
                    $em->getConnection()->setAutoCommit(false);
                    $IsEmail->setCod($code);
                }else{
                    return array('error'=>'Send mail is not possible');
                }
                $em->persist($IsEmail);
                $em->flush();
                $em->getConnection()->commit();
                return array('success'=>true);
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return array('error'=> $e->getMessage(),'p'=>$email);
            }
        }
    }

    public function isCode( $code, $email): array
    {
        $IsEmail = $this->getMail($email);
        if(!empty($IsEmail)){
            if( $IsEmail->getAuthid() !== 0){
                return array('error'=> 'The email is already used');
            }
            if( $IsEmail->getCod().'' !== $code.''){
                return array('error'=> 'Incorrect code');
            }
        }
        if(empty($IsEmail)){
            return array('error'=> 'bad email');
        }
        return array('success'=>true);
    }

    public function isResetCode( $code, $email): array
    {
        $IsEmail = $this->getMail($email);
        if(!empty($IsEmail)){
            if( $IsEmail->getAuthid() === 0){
                return array('error'=> 'The email is not registered');
            }
            if( $IsEmail->getCod().'' !== $code.''){
                return array('error'=> 'Incorrect code');
            }
        }
        if(empty($IsEmail)){
            return array('error'=> 'bad email');
        }
        return array('success'=>true);
    }

    public function checkMailReset( $code, $email): array
    {
        $IsEmail = $this->getMail($email);

        if (!empty($IsEmail) &&  $IsEmail->getAuthid() !== 0 &&  (int)$IsEmail->getCod() === (int)$code ) {
            return array(
                'success'=>true,
                'authid'=>$IsEmail->getAuthid()
            );
        }
        return array(
            'error'=>'Email is not registered'
        );

    }

    public function RequestMail( $email): ?int
    {
        $code = $this->random_number();
        $param = array('email'=>$email,'code'=>$code);
        $opts = array('http' => array( 'method'  => 'GET', 'timeout' => 8  ,
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => json_encode($param, JSON_UNESCAPED_UNICODE)), "ssl"=>array( "verify_peer"=>false, "verify_peer_name"=>false ),);
        $context = stream_context_create($opts);
        $call = json_decode(file_get_contents('https://auth.smssystems.ru/email', false, $context),true);
        if($call['success']){
            return (int)$code;
        }
        return 0;
    }

    public function random_number($length = 4): string
    {
        $arr = array(
            '1', '2', '3', '4', '5', '6', '7', '8', '9'
        );

        $res = '';
        for ($i = 0; $i < $length; $i++) {
            $res .= $arr[random_int(0, count($arr) - 1)];
        }
        return $res;
    }

    public function getMail( $email): ?Email
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
