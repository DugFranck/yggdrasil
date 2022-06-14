<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Command;
use App\Entity\CommandDetails;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class CommandCrudController extends AbstractCrudController
{
    private $entityManager;
    private $adminUrlGenerator;
    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return Command::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours','fas fa-box-open')
            ->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours','fas fa-truck')
            ->linkToCrudAction('updatedelivery');
        return $actions
            ->remove('index','edit')
            ->remove('index','delete')
            ->remove('index', 'new')
            ->add('index','detail')
            ->add('detail',$updatePreparation)
            ->add('detail',$updateDelivery)
            ;
    }

    public function updatePreparation(AdminContext $context)
    {
        $command = $context->getEntity()->getInstance();
        $command->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice',"<span style='color:green;'><strong>La commande ".$command->getReference()." est bien <u>en cours de préparation</u>. </strong></span>");

        $url = $this->adminUrlGenerator
            ->setController(CommandCrudController::class)
            ->setAction('index')
            ->generateUrl();

        $mail = new Mail();
        $content = "Bonjour ".$command->getUser()->getFirstname()."<p>Votre commande.<br /> L'Atelier Yggdrasil vous confirme que votre commande n°".$command->getReference()." est en cours de préparation.</p>
                        <p>Pour toutes questions relatives à votre commande, vous pouvez nous contacter au <strong>06.45.47.37.44</strong> ou écrivez-nous via notre <strong>page contact</strong>, ou encore directement par email à l'adresse suivente <strong>latelier.yggdrasil0gmail.com</strong></p>"
        ;
        $mail->send($command->getUser()->getEmail(), $command->getUser()->getFirstname(), "Votre commande sur l'Atelier Yggdrasil est en cours de préparation.", $content);

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $command = $context->getEntity()->getInstance();
        $command->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice',"<span style='color:orange;'><strong>La commande ".$command->getReference()." est bien <u>en cours de livraison</u>. </strong></span>");

        $url = $this->adminUrlGenerator
            ->setController(CommandCrudController::class)
            ->setAction('index')
            ->generateUrl();
        $mail = new Mail();

        $content = "Bonjour ".$command->getUser()->getFirstname()."<p>Votre commande.<br /> L'Atelier Yggdrasil vous confirme que votre commande n°".$command->getReference()." est en cours de livraison.</p>
                        <p>Pour toutes questions relatives à votre commande, vous pouvez nous contacter au <strong>06.45.47.37.44</strong> ou écrivez-nous via notre <strong>page contact</strong>, ou encore directement par email à l'adresse suivente <strong>latelier.yggdrasil0gmail.com</strong></p>"
        ;
        $mail->send($command->getUser()->getEmail(), $command->getUser()->getFirstname(), "Votre commande sur l'Atelier Yggdrasil est en cours de livraison", $content);

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id'=>'DESC'])

            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt','Passée le'),
            TextField::new('user.getFullName','Utilisateur'),
            TextareaField::new('delivery', 'Adresse de livraison')->renderAsHtml()->onlyOnDetail(),
            ArrayField::new('commandDetails','Produits')->hideOnIndex(),
            MoneyField::new('total','Total des produits')->setCurrency('EUR'),
            MoneyField::new('priceSending','Frais d\'envois')->setCurrency('EUR'),

            ChoiceField::new('state')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' =>2,
                'Livraison en cours' =>3
                ]

            ),
            TextField::new('reference','reference')
        ];
    }
    
}
