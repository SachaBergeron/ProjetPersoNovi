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
    public function newControle(Request $request, EtudiantRepository $etudiantRepository,  \Swift_Mailer $mailer): Response
    {
        $controle = new Controle();

        $etudiants = $etudiantRepository->findAll();

        $form = $this->createForm(ControleType::class, $controle, array('etudiants' => $etudiants));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);
            $module = $session->get('module');
            $entityManager = $this->getDoctrine()->getManager();
            $module =$entityManager->merge($module);

            $controle->setModule($module);

            foreach($etudiants as $etudiant)
            {
                $note = new Note();

                $note->setNote($form->get('note' . $etudiant->getId())->getData());

                $note->setControle($controle);
                $note->setEtudiant($etudiant);
//                $controle->addNote($note);
//                $etudiant->addNote($note);

                $entityManager->persist($note);

                $message = (new \Swift_Message('Note ajoutée'))
                    ->setFrom('sacha45400@hotmail.com')
                    ->setTo($etudiant->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/ajoutNote.html.twig', [
                            'prenom' => $etudiant->getPrenom(),
                            'nom' => $etudiant->getNom(),
                            'module' => $module->getNom(),
//                            'prenomProf' => $module->getEnseignant()->getPrenom(),
//                            'nomProf' => $module->getEnseignant()->getNom(),
                            'note' => $note->getNote(),
                            'max' => $form->get('noteMax')->getData(),
                        ]),
                        'text/html'
                    );
                $mailer->send($message);
            }

            $entityManager->persist($controle);
            $entityManager->persist($module);
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
