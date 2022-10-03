<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\Persistence\ManagerRegistry;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfilController extends AbstractController
{
    public function __construct(

        ManagerRegistry $doctrine,
        Security $security,
        SluggerInterface $slugger
    ) {
        $this->doctrine = $doctrine;
        $this->security = $security;
        $this->slugger = $slugger;
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
        $id_user = $this->security->getUser()->getId();
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
}
