<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Fonction de déconnexion
     *
     * Cette fonction permet à l'utilisateur de se déconnecter (géré par security.yaml, donc vide).
     *
     * @return void
     *
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){
    }

    /**
     * @Route("/login/redir", name="redir")
     */
    public function redirection()
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('admin_index');
        }
        elseif ($this->isGranted('ROLE_PROF'))
        {
            $user = $this->getUser();

            if($user->getJamaisCo() != false){
                return $this->redirectToRoute('prof_change');
            }
            else{
                return $this->redirectToRoute('prof_index');
            }
        }
        elseif($this->isGranted('ROLE_ETUDIANT'))
        {
            $user = $this->getUser();

            if($user->getJamaisCo() != false){
                return $this->redirectToRoute('etudiant_change');
            }
            else{
                return $this->redirectToRoute('etudiant_index');
            }
        }
        else{
            return $this->redirectToRoute('accueil');
        }

    }
}
