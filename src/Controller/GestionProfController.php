<?php

namespace App\Controller;

use App\Entity\Prof;
use App\Form\ProfType;
use App\Repository\ProfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/enseignant")
 */
class GestionProfController extends AbstractController
{
    /**
     * @Route("/", name="prof_affiche", methods={"GET"})
     */
    public function index(ProfRepository $profRepository): Response
    {
        return $this->render('admin/prof/index.html.twig', [
            'profs' => $profRepository->findAll(),
        ]);
    }

//    /**
//     * @Route("/new", name="prof_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $prof = new Prof();
//        $form = $this->createForm(ProfType::class, $prof);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($prof);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('prof_index');
//        }
//
//        return $this->render('prof/new.html.twig', [
//            'prof' => $prof,
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/{id}", name="prof_show", methods={"GET"})
//     */
//    public function show(Prof $prof): Response
//    {
//        return $this->render('prof/show.html.twig', [
//            'prof' => $prof,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="prof_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prof $prof): Response
    {
        $form = $this->createForm(ProfType::class, $prof);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prof_affiche', [
                'id' => $prof->getId(),
            ]);
        }

        return $this->render('admin/prof/edit.html.twig', [
            'prof' => $prof,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prof_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Prof $prof): Response
    {
        $id = $prof->getId();
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $modules = $prof->getModules();
            foreach ($modules as $module)
            {
                $module->setEnseignant(null);
            }

            $entityManager->remove($prof);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prof_affiche');
    }
}
