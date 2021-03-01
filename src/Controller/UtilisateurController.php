<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\CompteRepository;
use App\Services\TransactionServices;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilisateurController extends AbstractController
{
    

    /**
     * @Route(
     *     path="/api/user/frais/montant",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UtilisateurController::FraisForMontant",
     *          "__api_resource_class"=Utilisateur::class,
     *          "__api_collection_operation_name"="frais_transaction",
     *          "normalization_context"={"groups"={"utilisateur_read","utilisateur_details_read"}}
     *     }
     * )
    */

    public function FraisForMontant(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,TransactionServices $service)
    {
        $Montant_tab = $serializer->decode($request->getContent(),"json");
        $frais=$service->calculeFraisTotal($Montant_tab['montant']);
        return $this -> json($frais, Response::HTTP_OK,);
    }

    /**
     * @Route(
     *     path="/api/caissier/{idcaissier}/compte/{idcompte}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\UtilisateurController::rechargerCompte",
     *          "__api_resource_class"=Utilisateur::class,
     *          "__api_collection_operation_name"="recharger_compte"
     *     }
     * )
    */

    public function rechargerCompte(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager,CompteRepository $compterep,$idcaissier,$idcompte)
    {
        $compte=$compterep->find($idcompte);
        $montant=$serializer->decode($request->getContent(),"json")['montant'];
        $compte->setSolde($montant+$compte->getSolde());
        $manager->flush();
        return $this -> json($compte, Response::HTTP_OK,);
    }
                   
}
