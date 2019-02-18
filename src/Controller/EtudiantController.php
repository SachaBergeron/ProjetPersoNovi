<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Module;
use App\Form\ControleType;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/etudiant")
 */
class EtudiantController extends AbstractController
{
    /**
     * @Route("/", name="etudiant_index", methods="GET")
     */
    public function index(ModuleRepository $moduleRepository)
    {
        if($this->isGranted('ROLE_ETUDIANT')) {
//            $user = $this->getUser();

            return $this->render('etudiant/index.html.twig', [
                'modules' => $moduleRepository->findAll(),
            ]);
        }
        else{
            return $this->redirectToRoute("securitylogin");
        }
    }

//    /**
//     * @Route("/{id}", name="etudiant_module_controles", methods="GET")
//     */
//    public function controles(Module $module): Response
//    {
//        $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);
//        $session->set('module', $module);
//        return $this->redirectToRoute('etudiant_controle_index');
//    }

    /**
     * @Route("/{id}", name="etudiant_module_controles", methods={"GET"})
     */
    public function indexControles(Module $module): Response
    {
//        $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);

        $user = $this->getUser();

//        if ($session->get('module')) {
//            $module = $session->get('module');
//            $em = $this->getDoctrine()->getManager();
//            $module = $em->merge($module);
//
//            return $this->render('etudiant/controle.html.twig', [
//                'controles' => $module->getControles(), 'module' => $module, 'notes' => $user->getNotes()
//            ]);
//        }

        if ($module) {

            return $this->render('etudiant/controle.html.twig', [
                'controles' => $module->getControles(), 'module' => $module, 'notes' => $user->getNotes()
            ]);
        }
        else {
            return $this->redirectToRoute("etudiant_index");
        }
    }
}
