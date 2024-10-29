<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SectionController extends AbstractController
{
    #[Route('/section', name: 'app_section')]
    public function index(): Response
    {
        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController',
        ]);
    }
    #[Route('/sections', name: 'sections')]
    public function index2(SectionRepository $SectionRepository): Response
    {
        $user = $this->getUser();
        $sections = $SectionRepository->findAll();
        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
            'sections' => $sections,
        ]);
    }

    #[Route('/section/{slug}', name: 'section')]
    public function section(string $slug, SectionRepository $SectionRepository, ArticleRepository $ArticleRepository): Response
    {
        $user = $this->getUser();
        $section = $SectionRepository->getSectionBySlug($slug);
        $articles = $ArticleRepository->findAllPublished();
        $articles = array_filter($articles, fn($article) => in_array($section, $article->getSections()->toArray()));
        $sections = $SectionRepository->findAll();
        return $this->render('section/section.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
            'section' => $section,
            'sections' => $sections,
            'articles' => $articles,
        ]);
    }
}
