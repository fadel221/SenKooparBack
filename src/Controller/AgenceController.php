<?php

namespace App\Controller;

use App\Repository\AgenceRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AgenceController extends AbstractController
{
    
    /**
     * @Route(
     *     path="api/admin/agence/{ida}/user/{idu}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\AgenceController::addTransaction",
     *          "__api_resource_class"=Agence::class,
     *          "__api_collection_operation_name"="bloquer_user",
     *          
     *     }
     * )
     * 
    */

    public function BloqueUserAgence(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager,$idu,$ida,AgenceRepository $agencerep,UtilisateurRepository $userrep)
    {
        $agence=$agencerep->find($ida);
        $User=$userrep->find($idu);
        foreach ($agence->getUtilisateurs() as  $user) {
            if ($User===$user)
            {
                $User->setStatut(true);
            }
        }

        $manager->flush();
        return $this -> json($User, Response::HTTP_OK);
        
    }
}