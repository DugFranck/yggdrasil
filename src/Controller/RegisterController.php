<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    public function index(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $user = $form->getData();

            $search_email = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email){
                $password = $encoder->hashPassword($user,$user->getPassword());
                $user->setPassword($password);

                $entityManager->persist($user);

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."<p>Bienvenue sur l'Atelier Yggdrasil.<br /> Une Boutique de lithothérapie en ligne à taille humaine.
                            créee par Fabre Cédric un passionné qui shoutaite partager ses connaissances, sa bienveillance, des pierres naturelles et de la lithothérapie</p> 
                            <p>Tu découvriras sur ce site de merveilleux bijoux et trouveras, je l'espère une douce résonance en l'un d'eux. Concentre-toi sur ton bien-être et celui de tes proches.</p>";
                $mail->send($user->getEmail(), $user->getFirstname(), "Bienvenue sur l'Atelier Yggdrasil", $content);

                $entityManager->flush();
                $notification ="Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";
            } else {
                $notification = "L'email que vous avez renseigné existe déjà. ";
            }



            //return $this->redirectToRoute('app_login');

        }
        return $this->render('register/index.html.twig',[
            'form' =>$form->createView(),
            'notification' =>$notification
        ]);
    }
}
