<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(ModuleRepository $moduleRepository) : Response
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController', 'modules' => $moduleRepository->findAll(),
            ]);
        }
        else{
            return $this->redirectToRoute('security_login');
        }
    }


}
