<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Command;
use App\Entity\CommandDetails;
use App\Entity\ProductDimensionStock;
use App\Manager\CartManager;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'command_success')]
    public function index(CartSessionStorage $cartSessionStorage,CartManager $cartManager,$stripeSessionId): Response
    {

        $command = $this->entityManager->getRepository(Command::class)->findOneByStripeSessionId($stripeSessionId);
        if(!$command || $command->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }


        if($command->getState() == 0){
            // vider la session cart

            $commandDetails = $this->entityManager->getRepository(CommandDetails::class)->findOneByCommand($command);

            $product = $commandDetails->getProduct();
            $dimension = $commandDetails->getDimension();

            $productDimensionStock = $this->entityManager->getRepository(ProductDimensionStock::class)->findByOneProductDimension($product, $dimension);

            $quantity = $commandDetails->getQuantity();
            $stock = $productDimensionStock[0]->getStock();
            $newStock = $stock - $quantity;
            if($newStock <= 2 ){
                //envoyer un mail a cedric pour prévenir fin de stock
                $mail = new Mail();
                $content = "Bonjour Cédric<h2 style='color:red'>ATTENTION</h2><p>Le produit <strong>".$product."</strong> en dimension : ".$dimension."</strong> et presque en fin de stock.
                    <h4>son stock est de :".$newStock."</h4>."
                ;
                $mail->send('fullcreating63@gmail.com','Atelier Yggdrasil', "Alert Stock.", $content);

            }
            // Modifier stock
            $productDimensionStock[0]->setStock($newStock);
            $cartSessionStorage->remove();

            // Modifier le status isPaid de notre commande en mettant 1
            $command->setState(1);
            // Modifier le stock du producDimansionStock


            $this->entityManager->flush();
            // Envoyer un email a notre client pour lui confirmer sa commande
            $mail = new Mail();
            $mail2 = new Mail();
            $content = "Bonjour ".$command->getUser()->getFirstname()."<p>Merci pour votre commande.<br /> L'Atelier Yggdrasil vous confirme que votre commande n°".$command->getReference()." est bien enregistrée.</p>
                        <p>Pour toutes questions relatives à votre commande, vous pouvez nous contacter au <strong>06.45.47.37.44</strong> ou écrivez-nous via notre <strong>page contact</strong>, ou encore directement par email à l'adresse suivente <strong>latelier.yggdrasil0gmail.com</strong></p>"
                        ;

            $mail->send($command->getUser()->getEmail(), $command->getUser()->getFirstname(), "Votre commande sur l'Atelier Yggdrasil est bien validée.", $content);
            $mail2->send("fullcreating63@gmail.com", $command->getUser()->getFirstname()." ".$command->getUser()->getEmail(), "Votre commande sur l'Atelier Yggdrasil est bien validée.", $content);

        }

        // Afficher les quelque information de la commande de l'utilisateur

        return $this->render('command_success/index.html.twig',[
            'command'=>$command
        ]);
    }
}
