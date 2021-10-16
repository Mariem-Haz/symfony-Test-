<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    protected $em;
    function __construct(EntityManagerInterface $em){
        $this->em = $em;
    } 


    /**
     * @Route("/admin", name="show")
     */
    public function show(): Response
    {
        $articles =  $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('admin/show.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
        ]);
    }

     /**
     * @Route("/admin/{id}", name="show_article",requirements={"id"="\d+"})
     */
    public function showArticle($id): Response
    {
        $article =  $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('admin/article.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * @Route("/admin/update/{id}", name="update_article",requirements={"id"="\d+"})
     */
    public function update($id,Request $request,SerializerInterface $serializer): Response
    {
        
        if($request->request->count() >0) { 
            try{
                if ($content = $serializer->serialize($request->request,'json')) {
                    $params = json_decode($content, true);
                }

                $article =  $this->getDoctrine()->getRepository(Article::class)->find($id);
                
                foreach ($params as $key => $value) {
                    $method = "set" . ucfirst($key);
                    if (method_exists(Article::class, $method)) {
                        if (isset($value)) {
                             $article->$method($value);
                        }
                    }
                }

                $this->em->persist($article);
                $this->em->flush();
                return $this->render('admin/show.html.twig', [
                    'controller_name' => 'ArticleController',
                ]);
                
            }catch (\Exception $e) {
                return new JsonResponse([
                    "status" => 500,
                "message" =>$e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return $this->render('admin/update.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
        

    }

    /**
     * @Route("/admin/create", name="create_article")
     */
    public function create(Request $request,SerializerInterface $serializer): Response
    {
        
        if($request->request->count() >0) { 
            try{
                if ($content = $serializer->serialize($request->request,'json')) {
                    $params = json_decode($content, true);
                }

                $article =new Article;
                
                foreach ($params as $key => $value) {
                    $method = "set" . ucfirst($key);
                    if (method_exists(Article::class, $method)) {
                        if (isset($value)) {
                             $article->$method($value);
                        }
                    }
                }
                $this->em->persist($article);
                $this->em->flush();
    
                
            }catch (\Exception $e) {
                return new JsonResponse([
                    "status" => 500,
                "message" =>$e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return $this->render('admin/create.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
        

    }
}
