<?php

namespace App\Controller;

use App\Entity\Prof;
use App\Form\ProfRegistrationFormType;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfRegistrationController extends AbstractController
{
    /**
     * @Route("/admin/prof", name="prof_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer): Response
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

        $user = new Prof();
        $form = $this->createForm(ProfRegistrationFormType::class, $user);
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

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            $message = (new \Swift_Message('Compte Novi créé'))
                ->setFrom('sacha45400@hotmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig', [
                            'prenom' => $user->getPrenom(),
                            'nom' => $user->getNom(),
                            'reponse_password' => $password,
                            'reponse_username' => $user->getUsername(),
                            'profil' => 'enseignant',
                            ]),
                    'text/html'
                );
            $mailer->send($message);

            return $this->render('admin/compte_cree.html.twig', [
                'reponse_password' => $password,
                'reponse_username' => $user->getUsername(),
                'profil' => 'enseignant',
            ]);
        }

        return $this->render('profRegistration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
