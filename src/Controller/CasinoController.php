<?php

namespace App\Controller;

use App\Entity\Casino;
use App\Form\CasinoType;
use App\Repository\CasinoRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/casino')]
class CasinoController extends AbstractController
{
    #[Route('/', name: 'casino_index', methods: ['GET'])]
    public function index(CasinoRepository $casinoRepository): Response
    {
        return $this->render('casino/index.html.twig', [
            'casinos' => $casinoRepository->findAll(),
        ]);
    }

    public function searchBar()
    {
        $form = $this->createFormBuilder(null)
        ->add('search', type:SubmitType::class)
        ->getForm();

        return $this->render('searchbar/searchbar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    #[Route('/new', name: 'casino_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $casino = new Casino();
        $form = $this->createForm(CasinoType::class, $casino);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($casino);
            $entityManager->flush();

            return $this->redirectToRoute('casino_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('casino/new.html.twig', [
            'casino' => $casino,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'casino_show', methods: ['GET'])]
    public function show(Casino $casino): Response
    {
        return $this->render('casino/show.html.twig', [
            'casino' => $casino,
        ]);
    }

    #[Route('/{id}/edit', name: 'casino_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Casino $casino): Response
    {
        $form = $this->createForm(CasinoType::class, $casino);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('casino_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('casino/edit.html.twig', [
            'casino' => $casino,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'casino_delete', methods: ['POST'])]
    public function delete(Request $request, Casino $casino): Response
    {
        if ($this->isCsrfTokenValid('delete'.$casino->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($casino);
            $entityManager->flush();
        }

        return $this->redirectToRoute('casino_index', [], Response::HTTP_SEE_OTHER);
    }
}
