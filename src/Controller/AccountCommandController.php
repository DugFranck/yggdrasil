<?php

namespace App\Controller;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountCommandController extends AbstractController
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/mes-commandes', name: 'account_command')]
    public function index(): Response
    {
        $commands = $this->entityManager->getRepository(Command::class)->findSuccessCommands($this->getUser());

        return $this->render('account/command.html.twig',[
            'commands'=>$commands
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'account_command_show')]
    public function show($reference): Response
    {
        $command = $this->entityManager->getRepository(Command::class)->findOneByReference($reference);
        if(!$command || $command->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('account_command');
        }
        return $this->render('account/command_show.html.twig',[
            'command'=>$command
        ]);
    }
}
