<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Services\MailerServices;
use App\Repository\UserRepository;
use App\Form\PasswordResetChangeType;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        ManagerRegistry $doctrine,
        TokenGeneratorInterface $token,
        MailerServices $mailer,
        UserPasswordHasherInterface $hash,
        Security $security,
    ) {
        $this->doctrine = $doctrine;
        $this->token = $token;
        $this->mailer = $mailer;
        $this->hash = $hash;
        $this->security = $security;
    }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil');
        }
        $profilRepository = $this->doctrine->getRepository(Profil::class)->findOneBy(['id_User' => $this->security->getUser()->getId()]);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'profilRepository' => $profilRepository]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/forgotten/password', name: 'app_forgotten_password')]
    public function forgottenPassword(Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //on va chercher l'utilisateur par son email 
            $userRepository = $this->doctrine->getRepository(User::class);
            if ($userRepository->findOneBy(["email" => $form->get('email')->getData()])) {

                //on va générer un token de réinitialisation 
                $user = $userRepository->findOneBy(["email" => $form->get('email')->getData()]);
                $tokenGenerator = $this->token->generateToken();
                // dd($form->get('email')->getData(), $userRepository->findOneBy(["email" => $form->get('email')->getData()]), $tokenGenerator);
                $user->setResetToken($tokenGenerator);
                $entityManager->persist($user);
                $entityManager->flush();

                //on génére un lien de reinitialisation  du mot de passe 
                $url = $this->generateUrl('app_forgotten_password_tokenGenerator', ['token' =>  $tokenGenerator], UrlGeneratorInterface::ABSOLUTE_URL);
                // dd($url);
                $context = [
                    "url" => $url,
                    "user" => $user
                ];

                //Envoi de mail de récupération de mot de passe 
                $this->mailer->sendEmail(
                    'YannickCourbicTest@gmail.com',
                    $user->getEmail(),
                    'Réinitialisation du mot de passe ',
                    'passwordReset',
                    $context
                );
                $this->addFlash('success', 'Email envoyés avec succès');
                return $this->redirectToRoute('app_login');
            }
            $this->addFlash('danger', 'un problème est survenue');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_password_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forgotten/password/{token}', name: 'app_forgotten_password_tokenGenerator')]

    public function resetPassword(string $token, Request $request, UserRepository $userRepository): Response
    {
        //on vérifie si on a ce token dans la base de données 
        $entityManager = $this->doctrine->getManager();

        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if ($user) {
            $userEntity = new user;
            $form = $this->createForm(PasswordResetChangeType::class, $userEntity);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userform = $form->getData();
                $newPasswordConfirm = $form->get('newPasswordConfirm')->getData();

                if ($userform->getPassword() === $newPasswordConfirm) {

                    $hashedPassword = $this->hash->hashPassword(
                        $userEntity,
                        $userform->getPassword()
                    );

                    // dd($token, $hashedPassword);

                    if ($hashedPassword) {
                        $user->setPassword($hashedPassword);
                        $entityManager->flush();
                        $this->addFlash('success', 'mot de passe modifié avec succès ');
                        return $this->redirectToRoute('app_login');
                    }
                }
            }


            return $this->render('security/reset_password_change.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
}
