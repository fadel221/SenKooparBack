<?php


namespace App\DataPersister;


use App\Entity\Agence;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class AgenceDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data,array $contex=[]): bool
    {
        return ($data instanceof Agence);
    }
    
    public function persist($data,array $contex=[])
    {
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    
    public function remove($data,array $contex=[])
    {
        dd($contex);
        $data->getCompte()->setStatut(true);
        foreach ($data->getUtilisateurs() as  $user) {
            $user->setStatut(true);
        }
        $data->setStatut(true);//Mettre le statut à true pour montrer qu'on l'archive
        $this->entityManager->flush();
        return new JsonResponse($data);   
    }
    
}