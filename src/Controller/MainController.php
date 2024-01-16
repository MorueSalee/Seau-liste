<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



#[Route('/', 'app_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {


        return $this->render('main/index.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        $teamData = json_decode(file_get_contents('../data/team.json'), true);

        return $this->render('main/about.html.twig', compact('teamData'));
    }
}
