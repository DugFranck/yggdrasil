<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Command;
use App\Entity\CommandDetails;
use App\Entity\PriceSending;
use App\Entity\Product;
use App\Form\CommandType;
use App\Manager\CartManager;
use ContainerDrBTFHx\getProductDimensionStockRepositoryService;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'command')]
    public function index(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentCart();

        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('account_address_add');
        }

        $form=$this->createForm(CommandType::class,null,[
            'user'=>$this->getUser()
        ]);

        return $this->render('command/index.html.twig',[
            'cart' => $cart,
            'form'=>$form->createView(),

        ]);
    }

    #[Route('/commande/recapitulatif', name: 'command_recap', methods: 'POST' )]
    public function add(CartManager $cartManager, Request $request): Response
    {

        $cart = $cartManager->getCurrentCart();

        $totalPoids = $cart->getTotalPoids();


        $form = $this->createForm(CommandType::class, null, [
            'user' => $this->getUser(),

        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();


            $delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
            $delivery_content .= '<br />' . $delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br />' . $delivery->getCompany();
            }

            if (!empty($delivery)) {
                $zone = $delivery->getCountry()->getZone();
                if(!empty($carriers)){

                        $priceSending = $this->entityManager->getRepository(PriceSending::class)->findByZoneAndPoidsAndCarrier($totalPoids,$zone,$carriers);

                }
            }

            $delivery_content .= '<br />' . $delivery->getAddress();
            $delivery_content .= '<br />' . $delivery->getPostal() . ' ' . $delivery->getCity();
            $delivery_content .= '<br />' . $delivery->getCountry();


            // Enregistrer ma commande Command()
            $command = new Command();
            $reference = $date->format('dmy').'-'.uniqid();
            $command->setReference($reference);
            $command->setUser($this->getUser());
            $command->setCreatedAt($date);
            $command->setCarrierName($carriers->getName());
            $command->setPriceSending($priceSending[0]->getPrice());

            $command->setDelivery($delivery_content);
            $command->setState(0);

            $this->entityManager->persist($command);


// Enregistrer mes produits CommandDetail()

            foreach ($cart->getItems() as $item) {

                $commandDetails = new CommandDetails();
                $commandDetails->setCommand($command);
                $commandDetails->setProduct($item->getProductDimensionStock()->getProduct()->getName());
                $commandDetails->setDimension($item->getProductDimensionStock()->getDimension());
                $commandDetails->setQuantity($item->getQuantity());
                $commandDetails->setPrice($item->getproductDimensionStock()->getProduct()->getPrice());
                $commandDetails->setTotal($item->getproductDimensionStock()->getProduct()->getPrice() * $item->getQuantity());
                $this->entityManager->persist($commandDetails);



            }

            $this->entityManager->flush();



            return $this->render('command/add.html.twig',[
                'cart' => $cart,
                'totalpoids'=>$totalPoids,
                'priceSending' =>$priceSending,
                'carrier'=>$carriers,
                'delivery'=>$delivery_content,
                'reference'=>$command->getReference(),

            ]);
        }

       return $this->redirectToRoute('cart');
    }
}
