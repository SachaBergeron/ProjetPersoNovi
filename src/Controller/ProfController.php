<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Module;
use App\Form\ChangeProfPasswordType;
use App\Form\ControleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            return $this->redirectToRoute("security_login");
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

    /**
     * @Route("/pwd/change", name="prof_change")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeProfPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // encode the plain password
            $password = $form->get('password')->getData();
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $password)
            );

            $user->setJamaisCo(false);

//            $user =$entityManager->merge($user);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            $message = (new \Swift_Message('Mot de Passe Novi changé'))
                ->setFrom('sacha45400@hotmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/mdpChange.html.twig', [
                        'prenom' => $user->getPrenom(),
                        'nom' => $user->getNom(),
                        'reponse_password' => $password,
                        'reponse_username' => $user->getUsername(),
                        'profil' => 'enseignant',
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            return $this->redirectToRoute('redir');
        }

        return $this->render('profRegistration/change.html.twig', [
            'changeForm' => $form->createView(),
        ]);
    }
}
