<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wish', 'app_wish_')]
class WishController extends AbstractController
{
    #[Route('/list/{page}', name: 'list', requirements:['page'=>'\d+'])]
    public function list(WishRepository $repository, Request $request, int $page = 1): Response
    {
        $search = $request->query->get("wishSearch");

        $lastPage = ceil($repository->count([])/10);

        if($search){

            $conditions['title'] = $request->query->get("title")==="on";
            $conditions['author'] = $request->query->get("author")==="on";
            $conditions['category'] = $request->query->get("category")==="on";

            $data = $repository->search($search,$conditions);
        }elseif ($page && $page <= $lastPage){
            $data = $repository->pagination($page);
        }else{
            $data = $repository->findBy(['isPublished' => true],['dateCreated' => 'DESC']);
        }

        return $this->render('wish/list.html.twig', compact('data', 'lastPage', 'page'));
    }
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime('now'));

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Votre souhait a bien été ajouté !');

            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/add.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id'=>'\d+'])]
    public function edit(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

            $em->flush();

            $this->addFlash('success', 'Votre souhait a bien été modifié !');

            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/edit.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }
    #[Route('/detail/{id}', name: 'detail', requirements: ['id'=>'\d+'])]
    public function detail(Wish $wish): Response
    {
        if (!$wish->isIsPublished()){
            throw $this->createNotFoundException('Pas trouvé 😢');
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }
    #[Route('/delete', name: 'delete')]
    public function delete(EntityManagerInterface $em, Request $request): Response
    {
        $idWhish = $request->request->get('deleteWish');
        $csrfToken = $request->request->get('csrfToken');

        if ($request->isMethod('POST') && !empty($idWhish) && $this->isCsrfTokenValid('deleteWish'.$idWhish, $csrfToken)) {
            $wish = $em->find(Wish::class, $idWhish);

            $em->remove($wish);
            $em->flush();

            $this->addFlash('success', 'Votre souhait a bien été supprimé !');
        }


        return $this->redirectToRoute('app_wish_list');
    }


}
