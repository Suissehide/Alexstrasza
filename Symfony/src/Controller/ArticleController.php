<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Sondage;
use App\Form\ArticleType;
use App\Form\SondageType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\FileUploader;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/add", name="article_add", methods="GET|POST")
     */
    public function add(Request $request) : Response
    {
        $formSondage = $this->createForm(SondageType::class, new Sondage());

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDate(new \DateTime(null, new \DateTimeZone('Europe/Paris')));
            $article->setUtilisateur($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('article/add.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'formSondage' => $formSondage->createView(),
        ]);
    }

    /**
     * @Route("/", name="article_index", methods="GET")
     */
    public function index(ArticleRepository $articleRepository) : Response
    {
        return $this->render('article/index.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="article_new", methods="GET|POST")
     */
    public function new(Request $request) : Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDate(new \DateTime(null, new \DateTimeZone('Europe/Paris')));
            $article->setUtilisateur($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods="GET")
     */
    public function show(Article $article) : Response
    {
        return $this->render('article/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods="GET|POST")
     */
    public function edit(Request $request, Article $article) : Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{id}/like", name="add_like", methods="GET|POST")
     */
    public function add_like(Request $request) : Response
    {
    }

    /**
     * @Route("/more/{offset}", name="article_more", methods="GET")
     */
    public function article_more(Request $request, ArticleRepository $articleRepository, $offset) : Response
    {
        if ($request->isXmlHttpRequest()) {
            $max = 2;
            $articles = $articleRepository->findByOffset($offset, $max);

            $rows = array();
            foreach ($articles as $article) {
                $row = [
                    "id" => $article->getId(),
                ];
                array_push($rows, $row);
            }

            return new JsonResponse($rows);
        }
        throw new NotFoundHttpException('Erreur ajax');
    }
}
