<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use App\Form\NoteType;
use App\Services\NoteServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    #[Route('/note/list', name: 'app_note')]
    public function index(EntityManagerInterface $em): Response
    {
        $notes = $em->getRepository(Note::class)->findAll();
        $matieres = $em->getRepository(Matiere::class)->findAll();
        $coefficients = [];
        $noteService = new NoteServices();
        $moyennesParMatiere = [];
        foreach ($matieres as $matiere) {
            $coefficients[$matiere->getId()] = $matiere->getCoefficient();
            $moyenneMatiere = $noteService->calculateAverage($matiere->getNotes());
            $moyennesParMatiere[$matiere->getId()] = $moyenneMatiere;
        }
        
        $moyenne = $noteService->calculateAverageByCoefficient($notes, $coefficients);
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
            'matieres' => $matieres,
            'moyenne' => $moyenne,
            'moyennesParMatiere' => $moyennesParMatiere,
        ]);
    }

    #[Route('/note/edit/{id}', name: 'app_note_edit')]
    public function edit(EntityManagerInterface $em, Request $request, $id): Response
    {
        $note = $em->getRepository(Note::class)->find($id);
        if (!$note) {
            $this->addFlash('error', 'La note n\'existe pas.');
            return $this->redirectToRoute('app_note');
        }

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Note mise à jour !');
            return $this->redirectToRoute('app_note_edit', ['id' => $note->getId()]);
        }

        return $this->render('note/edit.html.twig', [
            'form' => $form->createView(),
            'note' => $note,
        ]);
    }

    #[Route('/note/create', name: 'app_note_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($note);
            $em->flush();

            $this->addFlash('success', 'Note ajoutée !');
            return $this->redirectToRoute('app_note');
        }

        return $this->render('note/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
