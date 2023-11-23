<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatiereController extends AbstractController
{
    #[Route('/matiere/list', name: 'app_matiere')]
    public function index(EntityManagerInterface $em): Response
    {
        $matiere = $em->getRepository(Matiere::class)->findAll();
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiere,
        ]);
    }

    #[Route('/matiere/edit/{id}', name: 'app_matiere_edit')]
    public function edit(EntityManagerInterface $em, Request $request, $id): Response
    {
        $matiere = $em->getRepository(Matiere::class)->find($id);
        if (!$matiere) {
            $this->addFlash('danger', 'La matière n\'existe pas.');
            return $this->redirectToRoute('app_matiere');
        }

        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Matière mise à jour !');
            return $this->redirectToRoute('app_matiere_edit', ['id' => $matiere->getId()]);
        }

        return $this->render('matiere/edit.html.twig', [
            'form' => $form->createView(),
            'matiere' => $matiere,
        ]);
    }

    #[Route('/matiere/add', name: 'app_matiere_add')]
    public function create(EntityManagerInterface $em, Request $request):Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($matiere);
            $em->flush();
            
            $this->addFlash('success', 'Matière ajoutée !');
            return $this->redirectToRoute('app_matiere');
        } else {
            return $this->render('matiere/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    #[Route('/matiere/delete/{id}', name: 'app_matiere_delete')]
    public function delete(EntityManagerInterface $em, Request $request): Response
    {
        $matiere = $em->getRepository(Matiere::class)->find($request->get('id'));
        if (!$matiere) {
            $this->addFlash('danger', 'La matière n\'existe pas.');
            return $this->redirectToRoute('app_matiere');
        }
        $em->remove($matiere);
        $em->flush();
        $this->addFlash('success', 'Matière supprimée !');
        return $this->redirectToRoute('app_matiere');
    }
}
