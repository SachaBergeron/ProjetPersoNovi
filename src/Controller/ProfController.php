<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Module;
use App\Form\ControleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/prof")
 */
class ProfController extends AbstractController
{
    /**
     * @Route("/", name="prof_index", methods="GET")
     */
    public function index()
    {
        if($this->isGranted('ROLE_PROF')) {
            $user = $this->getUser();

            return $this->render('prof/index.html.twig', [
                'modules' => $user->getModules(),
            ]);
        }
        else{
            return $this->redirectToRoute("login");
        }
    }

    /**
     * @Route("/{id}", name="module_controles", methods="GET")
     */
    public function controles(Module $module): Response
    {
        $session = new Session(/*new NativeSessionStorage(), new AttributeBag()*/);
        $session->set('module', $module);
        return $this->redirectToRoute('controle_index');
    }
}
