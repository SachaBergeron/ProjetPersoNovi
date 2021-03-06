<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantRegistrationFormType;
use App\Repository\EtudiantRepository;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EtudiantRegistrationController extends AbstractController
{
    /**
     * @Route("/admin/etudiant", name="etudiant_register")
     */
    public function register(EtudiantRepository $etudiantRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer): Response
    {
        $generator = new ComputerPasswordGenerator();

        $generator
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
            ->setLength(10)
        ;

        $password = $generator->generatePassword();

        $user = new Etudiant();
        $form = $this->createForm(EtudiantRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $password)
            );

            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setEmail($form->get('email')->getData());

            $prenomSplit = str_split(lcfirst($user->getPrenom()));
            $username = $prenomSplit[0] . lcfirst($user->getNom());

            $user->setUsername($username);

            $etudiants = $etudiantRepository->findAll();

            $i = 1;
            foreach ($etudiants as $etudiant){
                if($etudiant->getUsername() == $user -> getUsername()){
                    $user->setUsername($username . $i);
                    $i++;
                }
            }

            $user->setJamaisCo(true);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            $message = (new \Swift_Message('Compte Novi créé'))
                ->setFrom('administration@novi.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig', [
                        'prenom' => $user->getPrenom(),
                        'nom' => $user->getNom(),
                        'reponse_password' => $password,
                        'reponse_username' => $user->getUsername(),
                        'profil' => 'étudiant',
                    ]),
                    'text/html'
                );
            $mailer->send($message);

//            return $this->render('admin/compte_cree.html.twig', [
//                'reponse_password' => $password,
//                'reponse_username' => $user->getUsername(),
//                'profil' => 'étudiant',
//            ]);

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('etudiantRegistration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
