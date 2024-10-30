<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    # appel du gestionnaire de Section
    public function index(SectionRepository $sections, ArticleRepository $articles): Response
    {
        return $this->render(
            'index/index.html.twig', [
                'title' => 'Homepage',
                'homepage_text'=> "Nous somme le ".date('d/m/Y \à H:i'
                ),
                # on met dans une variable pour twig toutes les sections récupérées
                'sections' => $sections->findAll(),
                'articles' => $articles->findAll(),

            ]
        );
    }
 
   

    // création de l'url pour le détail d'une section
    #[Route(
        # chemin vers la section avec son id
        path: '/section/{id}',
        # nom du chemin
        name: 'section',
        # accepte l'id au format int positif uniquement
        requirements: ['id' => '\d+'],
        # si absent, donne 1 comme valeur par défaut
        defaults: ['id'=>1])]

    public function section(SectionRepository $sections, int $id): Response
    {
        // récupération de la section
        $section = $sections->find($id);
        return $this->render('index/section.html.twig', [
            'title' => 'Section '.$section->getSectionTitle(),
            'homepage_text'=> $section->getSectionSlug(),
            'section' => $section,
            'sections' => $sections->findAll(),
        ]);
    }

    #[Route(
        # chemin vers la section avec son id
        path: '/article/{id}',
        # nom du chemin
        name: 'article',
        # accepte l'id au format int positif uniquement
        requirements: ['id' => '\d+'],
        # si absent, donne 1 comme valeur par défaut
        defaults: ['id'=>1])]

    public function article(SectionRepository $articles, int $id): Response
    {
        // récupération de la section
        $article = $articles->find($id);
        return $this->render('index/index.html.twig', [
            'title' => 'Section '.$article->getArticlTitle(),
            'homepage_text'=> $article->getArticleDescription(),
            'article' => $articles,
            'articles' => $articles->findAll(),
        ]);
    }
}