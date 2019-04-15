<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Etudiant;
use App\Entity\Module;
use App\Form\ChangeEtudiantPasswordType;
use App\Form\ChangeProfPasswordType;
use App\Form\ControleType;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
                'modules' => $moduleRepository->findAll()
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

    /**
     * @Route("/moyennes/{id}", name="etudiant_moyennes", methods="GET")
     * @param ModuleRepository $moduleRepository
     * @return Response
     */
    public function moyennes(ModuleRepository $moduleRepository, Etudiant $etudiant)
    {
//        $user = $this->getUser();

        $modules = $moduleRepository->findAll();

//        $em = $this->getDoctrine()->getManager();
//        $modules = $em->merge($modules);

//        $lesModules = [];
//
//        foreach ($modules as $module)
//        {
//            $em = $this->getDoctrine()->getManager();
//            $module = $em->merge($module);
//            array_push($lesModules, $module);
//        }

//        return $this->render('etudiant/moyennes.html.twig', [
//            'modules' => $moduleRepository->findAll(), 'notes' => $user->getNotes()
//        ]);

        return $this->render('etudiant/moyennes.html.twig', [
            'modules' => $modules, 'notes' => $etudiant->getNotes(),'test'=>'test'
        ]);
    }

    /**
     * @Route("/pwd/change", name="etudiant_change")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeEtudiantPasswordType::class, $user);
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
                ->setFrom('administration@novi.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/mdpChange.html.twig', [
                        'prenom' => $user->getPrenom(),
                        'nom' => $user->getNom(),
                        'reponse_password' => $password,
                        'reponse_username' => $user->getUsername(),
                        'profil' => 'étudiant',
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            return $this->redirectToRoute('redir');
        }

        return $this->render('etudiantRegistration/change.html.twig', [
            'changeForm' => $form->createView(),
        ]);
    }
}
