<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil/index', name: 'app_profil')]
    public function index(Request $request): Response
    {

        $this->addFlash(
            'success',
            'Vous êtes connecté avec succès , veuillez éditer votre profil et ajouter une adresse'
        );

        $profil = new Profil();
        //pour récupérer les information d'un formulaire classique 
        if ($request->request->count() > 0) {

            // if($request->request->get('phone'))
            //pour accéder au information d'un formulaire classique , il suffit juste d'uliser request->get('name')

            $numTel = $request->request->get('phone');
            if (strlen($numTel) === 10) {
                dd($request->request->get('age'),  $request->request->get('phone'), strlen($numTel));
            }
        }
        dump($request);








        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
