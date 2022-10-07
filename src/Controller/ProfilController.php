<?php

namespace App\Controller;

use Error;
use App\Entity\User;
use App\Entity\Profil;
use App\Form\ProfilEditEmailType;
use App\Form\ProfilType;
use App\Form\ProfilEditType;
use App\Services\UserSecurity;
use App\Form\ProfilEditPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilController extends AbstractController
{
    public function __construct(

        ManagerRegistry $doctrine,
        Security $security,
        SluggerInterface $slugger,
        Filesystem $filesystem,
        UserSecurity $profilRepository,
        UserPasswordHasherInterface $hash
    ) {
        $this->doctrine = $doctrine;
        $this->security = $security;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
        $this->profilRepository = $profilRepository;
        $this->hash = $hash;
    }
    #[Route('/profil/index', name: 'app_profil')]
    public function index(Request $request): Response
    {
        $this->addFlash(
            'success',
            'Vous êtes connecté avec succès , veuillez éditer votre profil et ajouter une adresse'
        );

        $profil = new Profil();
        //pour récupérer les information d'un formulaire classique 
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);
        // $id_user = $this->security->getUser()->getId();
        $profilRepository = $this->doctrine->getRepository(Profil::class)->findOneBy(['id_User' => $this->security->getUser()->getId()]);
        $entityManager = $this->doctrine->getManager();
        // if($request->request->get('phone'))
        //pour accéder au information d'un formulaire classique , il suffit juste d'uliser request->get('name')
        if ($request->request->count() > 0) {


            if ($form->isSubmitted() && $form->isValid()) {
                $age = (int)$request->request->get('age');
                $profilForm = $form->getData();
                $picturefile = $form->get('pictureProfil')->getData();
                if ($picturefile) {
                    $originalFilename = pathinfo($picturefile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '_' . uniqid() . '.' . $picturefile->guessExtension();
                    $picturefile->move(
                        $this->getParameter('profils_directory'),
                        $newFilename
                    );
                }
                $profil
                    ->setAge($age)
                    ->setPictureProfil($newFilename)
                    ->setPhone($profilForm->getPhone())
                    ->setCountry('FR')
                    ->setIdUser($this->security->getUser());
                // dd($picturefile);
                $entityManager->persist($profil);
                $entityManager->flush();

                return $this->redirectToRoute('app_profil');
            }
        }
        // dump($request);


        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
            'form' => $form->createView(),
            'profilRepository' => $profilRepository,
        ]);
    }

    #[Route('/profil/edit/', name: 'app_profil_edit')]
    public function editProfil(Request $request)
    {
        $profil = new Profil();
        $entityManager = $this->doctrine->getManager();
        $profilRepository = $this->doctrine->getRepository(Profil::class)->findOneBy(['id_User' => $this->security->getUser()->getId()]);
        $form = $this->createForm(ProfilEditType::class, $profil);
        $form->handleRequest($request);

        // dd($profilRepository);
        if ($form->isSubmitted() && $form->isValid()) {
            $profilEditForm = $form->getData();
            $editPicture = $form->get("pictureProfil")->getData();

            $age = $request->request->get('age');
            // dd($profilEditForm->getPhone());
            // $profilEditForm->setPictureProfil($newFilename);
            // $profilEditForm->setAge($profilRepository->getAge());
            // $profilEditForm->setCountry($profilRepository->getCountry());
            // $entityManager->persist($profilEditForm);
            if (!empty($editPicture)) {

                $originalFilename = pathinfo($editPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '_' . uniqid() . '.' . $editPicture->guessExtension();
                // unlink("/public/uploads/profils/" . $profilRepository->getPictureProfil());
                unlink($this->getParameter('profils_directory') . '/' .  $profilRepository->getPictureProfil());
                $editPicture->move(
                    $this->getParameter('profils_directory'),
                    $newFilename
                );
                $profilRepository->setPictureProfil($newFilename);
            }
            if ($age != "Age :") {
                $profilRepository->setAge($age);
            }

            if ($profilEditForm->getPhone() !== $profilRepository->getPhone()) {
                $profilRepository->setPhone($profilEditForm->getPhone());
            }


            $entityManager->flush();

            // dd($profilEditForm, $editPicture, $age, $profilRepository->getPictureProfil());
        }

        return $this->render('profil/edit.html.twig', [
            'profilRepository' => $profilRepository,
            'form' => $form->createView(),

        ]);
    }

    #[Route('profil/edit/password', name: 'app_profil_edit_passsword')]
    public function editPassword(Request $request)
    {
        $entityManager = $this->doctrine->getManager();


        $user = new User();
        // $userRepository = $this->doctrine->getRepository(User::class)->findOneBy(['id' => $this->security->getUser()->getId()]);
        // dd($userRepository);
        $picture = $this->profilRepository->ProfilSecurity()->getProfil()->getPictureProfil();
        $username = $this->profilRepository->ProfilSecurity()->getUsername();
        // $user = $this->profilRepository->ProfilSecurity();
        $form = $this->createForm(ProfilEditPasswordType::class, $user);
        $oldHashPassword = $this->doctrine->getRepository(Profil::class)->findOneBy(['id_User' => $this->security->getUser()->getId()])->getIdUser()->getPassword();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userForm = $form->getData();
            $oldPassword = $form->get('oldPassword')->getData();
            $passwordConfirm = $form->get('passwordConfirm')->getData();
            if (password_verify($oldPassword, $oldHashPassword)) {
                if ($userForm->getPassword() === $passwordConfirm) {
                    $hashedPassword = $this->hash->hashPassword(
                        $user,
                        $userForm->getPassword()
                    );

                    if ($hashedPassword) {
                        $this->profilRepository->ProfilSecurity()->setPassword($hashedPassword);
                        // dd($oldHashPassword, $oldPassword, $passwordConfirm, $hashedPassword,);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_logout');
                    }
                }
            }
        }

        return $this->render('profil/Editpassword.html.twig', [
            'profilRepository' => $this->profilRepository->UserSecurity(),
            'picture' => $picture,
            'username' => $username,
            'form' => $form->createView(),
        ]);
    }

    #[Route('profil/edit/email', name: 'app_profil_edit_email')]

    public function editEmail(Request $request)
    {
        $user = new User;
        $entityManager = $this->doctrine->getManager();
        $form = $this->createForm(ProfilEditEmailType::class, $user);
        $form->handleRequest($request);




        return $this->render('profil/Editemail.html.twig', [
            'profilRepository' => $this->profilRepository->UserSecurity(),
        ]);
    }
}
