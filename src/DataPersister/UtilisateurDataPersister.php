<?php


namespace App\DataPersister;


use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Services\TransactionServices;

class UtilisateurDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $service;

    public function __construct(EntityManagerInterface $entityManager,TransactionServices $service)
    {
        $this->entityManager = $entityManager;
        $this->service=$service;
    }
    
    public function supports($data,array $contex=[]): bool
    {
        return ($data instanceof Utilisateur);
    }
    
    public function persist($data ,array $contex=[])
    {
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
       
    }
    
    public function remove($data,array $contex=[])
    {
        $data->setStatut(true);//Mettre le statut à true pour montrer qu'on l'archive
        $this->entityManager->flush();
        return new JsonResponse($data);   
    }
    
}