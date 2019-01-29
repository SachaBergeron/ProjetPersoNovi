<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use App\Repository\ProfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module")
 */
class ModuleController extends AbstractController
{
//    /**
//     * @Route("/", name="module_index", methods={"GET"})
//     */
//    public function index(ModuleRepository $moduleRepository): Response
//    {
//        return $this->render('module/index.html.twig', [
//            'modules' => $moduleRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="module_new", methods={"GET","POST"})
     */
    public function new(Request $request, ProfRepository $profRepository): Response
    {
        $module = new Module();
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/module/new.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="module_show", methods={"GET"})
//     */
//    public function show(Module $module): Response
//    {
//        return $this->render('module/show.html.twig', [
//            'module' => $module,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="module_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Module $module): Response
    {
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index', [
                'id' => $module->getId(),
            ]);
        }

        return $this->render('admin/module/edit.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="module_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Module $module): Response
    {
        if ($this->isCsrfTokenValid('delete'.$module->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($module);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }
}
