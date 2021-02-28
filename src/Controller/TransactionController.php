<?php

namespace App\Controller;

use DateTime;
use App\Repository\CompteRepository;
use App\Services\TransactionServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

     /**
     * @Route(
     *     path="/api/transactions",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\TransactionController::addTransaction",
     *          "__api_resource_class"=Transaction::class,
     *          "__api_collection_operation_name"="add_transaction",
     *          "normalization_context"={"groups"={"transaction_read","transaction_details_read"}}
     *     }
     * )
    */
    public function addTransaction(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,CompteRepository $compte,TransactionServices $service)
    {
        $Transaction_tab = $serializer->decode($request->getContent(),"json");
        $CompteDepot=$compte->find($Transaction_tab['compteDepot']['id']);
        unset($Transaction_tab['compteDepot']);        
        $clientDepot=$serializer->denormalize($Transaction_tab['clientDepot'],'App\Entity\Client');
        unset($Transaction_tab['clientDepot']);
        $clientRetrait=$serializer->denormalize($Transaction_tab['clientRetrait'],'App\Entity\Client');
        unset($Transaction_tab['clientRetrait']);
        $Transaction=$serializer->denormalize($Transaction_tab,'App\Entity\Transaction');
        $CompteDepot->setSolde($CompteDepot->getSolde()-$Transaction->getMontant());
        $Transaction->setClientDepot($clientDepot);
        $Transaction->setClientRetrait($clientRetrait);
        $Transaction->setCompteDepot($CompteDepot);
        $Transaction->setUserDepot($this->get('security.token_storage')->getToken()->getUser());
        $frais=$service->calculeFraisTotal($Transaction->getMontant());
        $Transaction->setFrais($frais);
        $Transaction->setFraisDepot($frais*0.1);
        $Transaction->setFraisRetrait($frais*0.2);
        $Transaction->setFraisEtat($frais*0.4);
        $Transaction->setFraisSysteme($frais*0.3);
        $manager->persist($Transaction);
        $manager->flush();
        $Transaction->setCodeTransfert($service->GenerateCode($Transaction->getId()));
        $manager->flush();
        return $this -> json($Transaction, Response::HTTP_CREATED,);
    }
    
    /**
     * @Route(
     *     path="/api/transactions/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\TransactionController::addTransaction",
     *          "__api_resource_class"=Transaction::class,
     *          "__api_collection_operation_name"="update_transaction",
     *          "normalization_context"={"groups"={"transaction_read","transaction_details_read"}}
     *     }
     * )
    */
    public function UpdateTransaction(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,CompteRepository $compte,TransactionRepository $trans,$id)
    {
        $Transaction_tab = $serializer->decode($request->getContent(),"json");
        $Transaction=$trans->find($id);
        $CompteRetrait=$compte->find($Transaction_tab['compteRetrait']['id']);
        $Transaction->setDateRetrait(new \DateTime());
        $clientRetrait=$Transaction->getClientRetrait()->setnumCIN($Transaction_tab['clientRetrait']['numCIN']);
        $Transaction->setCompteRetrait($CompteRetrait);
        $Transaction->setClientRetrait($clientRetrait);
        $CompteRetrait->setSolde($CompteRetrait->getSolde()+$Transaction->getMontant());
        $Transaction->setUserRetrait($this->get('security.token_storage')->getToken()->getUser());
        $manager->persist($Transaction);
        $manager->flush();
        return $this -> json($Transaction, Response::HTTP_CREATED,);
    }

    
    
}
