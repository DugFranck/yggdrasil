<?php

namespace App\Controller;

use App\Classe\SearchNews;
use App\Entity\News;
use App\Form\SearchNewsType;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }

    #[Route('/mes-nouvelles', name: 'news')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $searchNews = new SearchNews();
        $form = $this->createForm(SearchNewsType::class, $searchNews);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $donnees = $this->entitymanager->getRepository(News::class)->findWithSearch($searchNews);
            /* $search=$form->getData();*/

        }else{
            $donnees = $this->entitymanager->getRepository(News::class)->findAll();
        }
        $news = $paginator->paginate(
            $donnees,
            $request->query->get('page', 1),
            9
        );
        return $this->render('news/index.html.twig',[
            'news' => $news,
            'form' => $form->createView()
       ]);
    }

    #[Route('/ma-nouvelle/{slug}', name: 'new')]
    public function show($slug): Response
    {
        $searchNews = new SearchNews();
        $form = $this->createForm(SearchNewsType::class, $searchNews);
        $news = $this->entitymanager->getRepository(News::class)->findOneBySlug($slug);
        if(!$news){
            return $this->redirectToRoute('news');
        }
        return $this->render('news/show.html.twig',[
            'news' => $news,
            'form' => $form->createView()
        ]);
    }
}
