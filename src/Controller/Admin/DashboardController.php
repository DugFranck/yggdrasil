<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\CategoryNews;
use App\Entity\Command;
use App\Entity\CommandDetails;
use App\Entity\Country;
use App\Entity\Dimension;


use App\Entity\Header;
use App\Entity\News;
use App\Entity\PriceSending;
use App\Entity\Product;

use App\Entity\ProductDimensionStock;

use App\Entity\User;
use App\Entity\Zone;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);


        return $this->redirect($adminUrlGenerator->setController(CommandCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Yggdrasil');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur <br /><br /><hr>', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-shopping-cart', Command::class);

        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);
        yield MenuItem::linkToCrud('Dimensions', 'fas fa-ruler', Dimension::class);

        yield MenuItem::linkToCrud('ProduitDimensionStock', 'fas fa-arrows-alt', ProductDimensionStock::class);
        yield MenuItem::linkToCrud('Transporteur', 'fa fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Zone', 'fa fa-globe', Zone::class);
        yield MenuItem::linkToCrud('Pays', 'fa fa-flag', Country::class);
        yield MenuItem::linkToCrud('Tarif Envois <br /><br /><hr>', 'fa fa-dolly', PriceSending::class);

        yield MenuItem::linkToCrud('Headers', 'fa fa-desktop', Header::class);
        yield MenuItem::linkToCrud('CategoryNews', 'fa fa-table-list', CategoryNews::class);
        yield MenuItem::linkToCrud('News', 'fa fa-radio', News::class);

    }
}
