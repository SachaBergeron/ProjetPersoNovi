<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prof/controle/note")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="note_index", methods={"GET"})
     */
    public function index(): Response
    {
        $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);
        if ($session->get('controle')) {
            $controle = $session->get('controle');
            $em = $this->getDoctrine()->getManager();
            $controle = $em->merge($controle);

            return $this->render('prof/controle/note/index.html.twig', ['notes' => $controle->getNotes()]);
        }
        else {
            return $this->redirectToRoute("controle_index");
        }
    }

    /**
     * @Route("/{id}/edit", name="note_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Note $note): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('note_index');
        }

        return $this->render('prof/controle/note/edit.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="note_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, Note $note): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($note);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('note_index');
//    }
}
