<?php

namespace App\Controller;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/commande/erreur/{stripeSessionId}', name: 'command_cancel')]
    public function index($stripeSessionId): Response
    {
        $command = $this->entityManager->getRepository(Command::class)->findOneByStripeSessionId($stripeSessionId);
        if(!$command || $command->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        //Envoyer un email à notre utilisateur pour lui indiquer l'echec de paiemant
        return $this->render('command_cancel/index.html.twig',[
            'command' => $command
            ]);
    }
}
