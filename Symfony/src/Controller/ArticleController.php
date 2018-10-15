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

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $sondage = new Sondage();
        $formSondage = $this->createForm(SondageType::class, $sondage);

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
     * @Route("/upload", name="upload", methods="GET|POST")
     */
    public function upload(Request $request, FileUploader $fileUploader) : Response
    {
        if ($request->isXmlHttpRequest()) {
            $file = $request->files->get('file');
            $fileName = $fileUploader->upload($file);
            return new Response($fileName);
        }
        throw new NotFoundHttpException('404 not found');
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
     * @Route("/{id}/like", name="add_like", methods="POST")
     */
    public function add_like(Request $request, Article $article) : Response
    {
        if ($request->isXmlHttpRequest()) {
            $diff = $request->request->get('diff');
            if ($diff == '1') {
                $article->addThumb($this->getUser());
            }
            else {
                $article->removeThumb($this->getUser());
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new Response();
        }
        throw new NotFoundHttpException('404 not found');
    }

    /**
     * @Route("/more", name="article_more", methods="GET|POST")
     */
    public function article_more(Request $request, ArticleRepository $articleRepository) : Response
    {
        if ($request->isXmlHttpRequest()) {
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $normalizer = new ObjectNormalizer($classMetadataFactory);
            $serializer = new Serializer([$normalizer], [new JsonEncode()]);

            $callback = function ($dateTime) {
                return $dateTime instanceof \DateTime
                    ? $dateTime->format('d/m/y')
                    : '';
            };
            $normalizer->setCallbacks(array(
                'date' => $callback,
            ));

            $offset = $request->request->get('offset');
            $max = $request->request->get('max');
            $org = $articleRepository->findByOffset($offset, $max);
            $data = $serializer->serialize($org, 'json', array('groups' => array('article')));

            $response = new JsonResponse($data);
            return $response;
        }
        throw new NotFoundHttpException('Erreur ajax');
    }
}
