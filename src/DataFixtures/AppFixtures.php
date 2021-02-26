<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Repository\AgenceRepository;
use App\Repository\ProfilRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
   private $encoder;
   private $agence;
    public function __construct(UserPasswordEncoderInterface $encoder,AgenceRepository $agence)
    {
    
        $this->encoder=$encoder;
        $this->agence=$agence;
    }

    public function load(ObjectManager $manager)
    {
        $user=new Utilisateur();
        $user->setEmail('gueyefadel221@gmail.com');
        $user->setNom('GUEYE');
        $user->setPrenom('FADEL');
        $user->setPassword($this->encoder->encodePassword($user,'1234'));
        $user->setTelephone('776543211');
        $user->setAgence($this->agence->find(1));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
