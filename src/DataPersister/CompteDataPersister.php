<?php


namespace App\DataPersister;


use App\Entity\Compte;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Services\TransactionServices;

class CompteDataPersister implements ContextAwareDataPersisterInterface
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
        return ($data instanceof Compte);
    }
    
    public function persist($data,array $contex=[])
    {
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        if ($contex["collection_operation_name"]=="post")
        {
            $data->setNumero($this->service->GenerateCode(($data->getId())));
        }
        $this->entityManager->flush();
    }
    
    public function remove($data,array $contex=[])
    {
        
        $data->getCompte()->setStatut(true);
        foreach ($data->getUtilisateurs() as  $user) {
            $user->setStatut(true);
        }
        $data->setStatut(true);//Mettre le statut à true pour montrer qu'on l'archive
        $this->entityManager->flush();
        return new JsonResponse($data);   
    }
    
}