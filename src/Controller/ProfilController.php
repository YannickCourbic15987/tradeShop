<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfilController extends AbstractController
{
    public function __construct(

        ManagerRegistry $doctrine,
        Security $security
    ) {
        $this->doctrine = $doctrine;
        $this->security = $security;
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
        if ($request->request
            ->count() > 0
        ) {

            // if($request->request->get('phone'))
            //pour accéder au information d'un formulaire classique , il suffit juste d'uliser request->get('name')

            $numTel = $request->request->get('phone');
            $age = (int)$request->request->get('age');
            $picture = $request->request->get('file_upload_input');
            $id_user = $this->security->getUser()->getId();



            if (!empty($numTel)) {
                if (strlen($numTel) === 10) {
                    $profil
                        ->setAge($age)
                        ->setCountry('FR')
                        ->setPhone($numTel)
                        ->setPictureProfil($request->files->get('file_upload_input')->getClientOriginalName());

                    dd(
                        $request->request->get('age'),
                        $request->request->get('phone'),
                        strlen($numTel),
                        $request->request,
                        $this->security->getUser()->getId(),
                        $profil,
                        $request->files->get('file_upload_input')->getClientOriginalName(),

                    );
                }
            } else {
            }
        }
        dump($request);



        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
