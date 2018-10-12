<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController'
        ]);
    }
}
