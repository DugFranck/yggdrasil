<?php

namespace App\Controller;


use App\Classe\Cart;
use App\Classe\Search;

use App\Entity\Product;

use App\Entity\ProductDimensionStock;
use App\Form\SearchType;
use App\Form\SelectDimensionType;


use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/nos-produits", name="products")
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        $search = new Search();
        $form = $this->createForm( SearchType::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $donnees = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            /* $search=$form->getData();*/

        }else{
            $donnees = $this->entityManager->getRepository(Product::class)->findAll();
        }
        $products = $paginator->paginate(
            $donnees,
            $request->query->get('page', 1),
            9
        );


        return $this->render('product/index.html.twig',[

            'products'=>$products,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function show(

        $slug,

        CartManager $cartManager,
        Request $request


    ): Response
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        if(!$product){
            return $this->redirectToRoute('products');
        }
        $form = $this->createForm(SelectDimensionType::class, null,['product'=>$product]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $productDimensionStock = $form->get('dimension')->getData();
           $stock = $productDimensionStock->getStock();

            $item = $form->getData();
            $quantity = $item->getQuantity();

            if($quantity > $stock){
                $this->addFlash('notice','Le produit que vous souhaité commander n\'est plus en stock ou le stock est inferieure a votre quantité demandée.
                Vous pouvez quand même poursuivre votre achat mais le déler de Livraison sera plus long');
            }
            $item->setProductDimensionStock($productDimensionStock);
            $cart = $cartManager->getCurrentCart();
            $cart->addItem($item);
            $cart->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Votre produit est bien rajouter à votre pannier')
            ;



            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }


        return $this->render('product/show.html.twig',[
            'product'=>$product,
            'form' => $form->createView(),
            'products'=>$products

        ]);


    }

}
