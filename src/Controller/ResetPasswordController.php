<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{

    private $entityManager;

    public function  __construct(EntityManagerInterface $entityManager)
    {
         $this->entityManager = $entityManager;
    }
    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if($this->getUser())
        {
            return $this->redirectToRoute('home');
        }

        if($request->get('email'))
        {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if($user)
            {
                // 1 : enregistrer en base la demande de rest-password avec user, token, createdAt.
                $reset_passeword = new ResetPassword();
                $reset_passeword->setUser($user);
                $reset_passeword->setToken(uniqid());
                $reset_passeword->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_passeword);
                $this->entityManager->flush();

                // 2 : envoyer un email à l'utilisateur avec un lien lui permettant de mettre a jour son mot de passe.
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_passeword->getToken()
                ]);
                $content ="Bonjour ".$user->getFirstname()."<br />Vous avez demandé à réinitialiser votre mot de passe sur Atelier Yggdrasil.<br /><br />";
                $content .="Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe.</a>";


                 $mail = new Mail();
                 $mail->send($user->getEmail(),$user->getFirstname().' '.$user->getLastname(),'Réinitialiser votre mot de passe sur l\'Atelier Yggdrasil', $content);

                $this->addFlash('notice','Vous allez recevoir dans quelques seconde un mail avec la procédure pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice','Cette adresse email est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update($token,Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if(!$reset_password){
            return $this->redirectToRoute('reset_password');
        }
        // vérifier si le createdAt = new -3h
        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour'))
        {
            $this->addFlash('notice','votre demande de mot de passe a expiré. Merci de la renouveller.');
            return  $this->redirectToRoute('reset_password');
        }

        //rendre une vue avec mot de passe et confirmez votre mot de passe.
        $form =$this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $new_pwd = $form->get('new_password')->getData();

            //Encodage des mots de passe
            $password = $encoder->hashPassword($reset_password->getUser(),$new_pwd);
            $reset_password->getUser()->setPassword($password);


            //Flush en base

            $this->entityManager->flush();
            //rediction de l'utilisateur vers la page de connecxion
            $this->addFlash('notice','Votre mot de passe à bien été mis à jour.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig',[
            'form'=> $form->createView()
        ]);


    }
}