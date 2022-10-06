<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Services\JsonWebTokenServices;
use App\Services\MailerServices;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class RegisterController extends AbstractController
{

    public function __construct(
        ManagerRegistry $doctrine,
        Security $security,
        MailerServices $mailer,
        JsonWebTokenServices $jsonToken,

    ) {
        $this->doctrine = $doctrine;
        $this->security = $security;
        $this->mailer = $mailer;
        $this->jsonToken = $jsonToken;
    }
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hash): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->doctrine->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $userForm = $form->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            if ($userForm->getPassword() === $plainPassword) {
                $hashedPassword = $hash->hashPassword($user, $userForm->getPassword());
                $userForm->setPassword($hashedPassword);
                // $this->security->getUser()->setPassword($hashedPassword);
                $userForm->setCreatedAt(new DateTimeImmutable());
                $userForm->setResetToken(0);
                // $userForm->setRoles(['ROLE_USER']);
                $entityManager->persist($userForm);
                $entityManager->flush();

                //on génére le jsonwebtoken de l'utilisateur 

                //on génére le header 
                $header = [
                    "typ" => 'JWT',
                    "alg" => 'HS256'
                ];
                //on crée le payload
                $payload = [
                    'user_id' => $userForm->getId(),
                ];

                $token = $this->jsonToken->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                // dd($token);
                // envoie du mail
                $this->mailer->sendEmail(
                    'YannickCourbicTest@gmail.com',
                    $userForm->getEmail(),
                    'Activation de votre compte sur le site e-commerce',
                    'register',
                    ['user' => $userForm, 'token' => $token]


                );

                $this->addFlash(
                    'success',
                    'Votre compte a été enregistrer avec succès !'
                );
                return $this->redirectToRoute('app_login');
            }
            // dd($userForm, $plainPassword, $hashedPassword);
        }


        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView(),
        ]);
    }
}
