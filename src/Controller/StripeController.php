<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\Product;
use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager,CartManager $cartManager,$reference): Response
    {
        $cart = $cartManager->getCurrentCart();
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $command = $entityManager->getRepository(Command::class)->findOneByReference($reference);
        if(!$command){
            new JsonResponse(['error'=>'command']);
            return $this->redirect('/command');
        }

        foreach ($command->getCommandDetails()->getValues() as $item) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($item->getProduct());
            $product_for_stripe[] = [
                'price_data'=>[
                    'currency'=>'eur',
                    'unit_amount'=> $item->getPrice(),
                    'product_data'=>[
                        'name'=> $item->getProduct(),
                        'images'=>[ $YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity'=>$item->getQuantity(),

            ];
        }


        $product_for_stripe[] = [
            'price_data'=>[
                'currency'=>'eur',
                'unit_amount'=> $command->getPriceSending(),
                'product_data'=>[
                    'name'=> $command->getCarrierName(),
                    'images'=>[ $YOUR_DOMAIN],
                ],
            ],
            'quantity'=> 1,

        ];


        Stripe::setApiKey('sk_test_51KmD06LzyCPTuNkJt1JitSYUfmWW5NM2zBs7nIqLGxLtaqlLte7v1MCzhwsavdZCj3Krl8lXfDSjYxYZO4tg1hgx00ZUqxN1Wf');



        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types'=>['card'],
            'line_items'=>[
                $product_for_stripe
            ],

            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $command->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        $reponce = new JsonResponse(['id' =>$checkout_session->url]);
        return $this->redirect($checkout_session->url);
    }
}
