<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index()
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }
        else{
            return $this->redirectToRoute('security_login');
        }
    }
}
