<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Note;
use App\Form\ControleType;
use App\Repository\ControleRepository;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prof/controle")
 */
class ControleController extends AbstractController
{
    /**
     * @Route("/", name="controle_index", methods={"GET"})
     */
    public function index(ControleRepository $controleRepository): Response
    {
        $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);
        if ($session->get('module')) {
            $module = $session->get('module');
            $em = $this->getDoctrine()->getManager();
            $module = $em->merge($module);

            return $this->render('prof/controle/index.html.twig', ['controles' => $module->getControles(), 'module' => $module]);
        }
        else {
            return $this->redirectToRoute("suivi");
        }
    }

    /**
     * @Route("/new", name="controle_new", methods={"GET","POST"})
     */
    public function newControle(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        $controle = new Controle();

        $i = 0;
        $etudiants = $etudiantRepository->findAll();
        foreach($etudiants as $etudiant)
        {
            $note = new Note();
            $etudiant->addNote($note);
            $controle->addNote($note);
        }

        $form = $this->createForm(ControleType::class, $controle, ['notes' => $controle->getNotes(),]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);
            $module = $session->get('module');

            $controle->setModule($module);

            foreach($etudiants as $etudiant)
            {
                $etudiant->
            }

            $entityManager->persist($controle);
            $entityManager->flush();

            return $this->redirectToRoute('controle_index');
        }

        return $this->render('prof/controle/new.html.twig', [
            'controle' => $controle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="controle_show", methods={"GET"})
     */
    public function show(Controle $controle): Response
    {
        return $this->render('controle/show.html.twig', [
            'controle' => $controle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="controle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Controle $controle): Response
    {
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('controle_index', [
                'id' => $controle->getId(),
            ]);
        }

        return $this->render('controle/edit.html.twig', [
            'controle' => $controle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="controle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Controle $controle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$controle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($controle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('controle_index');
    }
}
