<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           $this->addFlash('notice','Merci de nous avoir contacté. Notre équipe vas vous répondre dans les meillieurs delais. ');




            // Envoyer un email
            $mail = new Mail();

            $content = $form->get('lastname')->getData()." ".$form->get('firstname')->getData()."<br />".$form->get('email')->getData()."<br /> ".$form->get('content')->getData();

            $mail->send('fullcreating63@gmail.com','Atelier Yggdrasil', "demande de contact.", $content);

        }
        return $this->render('contact/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
