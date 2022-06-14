<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\CartType;
use App\Form\SelectDimensionType;
use App\Manager\CartManager;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
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
     * @Route("/cart", name="cart")
     */
    public function index(CartManager $cartManager, Request $request): Response
    {

        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $cart->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('cart');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,

            'form' => $form->createView(),
        ]);
    }

    #[Route('/cart/supprimer-un-product/{id}', name: 'cart_product_delete')]
    public function delete($id, CartManager $cartManager)
    {
        $orderItem=$this->entityManager->getRepository(OrderItem::class)->findOneById($id);
        $cart = $cartManager->getCurrentCart();

            $this->entityManager->remove($orderItem);
            $this->entityManager->flush();
            $cartManager->save($cart);

        return $this->redirectToRoute("cart");





    }

    #[Route('/cart/ajout-un-product/{id}', name: 'cart_product_add')]
    public function add($id, CartManager $cartManager)
    {
        $item=$this->entityManager->getRepository(OrderItem::class)->findOneById($id);
        $cart = $cartManager->getCurrentCart();

        $cart->moreItem($item);
        $this->entityManager->flush();
        $cartManager->save($cart);

        return $this->redirectToRoute("cart");





    }

    #[Route('/cart/retire-un-product/{id}', name: 'cart_product_less')]
    public function less($id, CartManager $cartManager)
    {
        $item=$this->entityManager->getRepository(OrderItem::class)->findOneById($id);
        $cart = $cartManager->getCurrentCart();

        $cart->lessItem($item);
        $this->entityManager->flush();
        $cartManager->save($cart);

        return $this->redirectToRoute("cart");





    }
    #[Route('/cart/supprimer', name: 'cart_delete')]
    public function remove( CartManager $cartManager)
    {
        $order=$this->entityManager->getRepository(Order::class)->findBy($this->getUser());
        $cart = $cartManager->getCurrentCart();

            $this->entityManager->remove($order);

            $this->entityManager->flush();
            $cartManager->remove($cart);
            $cartManager->save($cart);

        return $this->redirectToRoute("cart");


    }






}
