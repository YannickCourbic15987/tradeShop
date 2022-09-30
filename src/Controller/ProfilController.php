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
     $profil = new Profil();
  //pour récupérer les information d'un formulaire classique 
     if($request->request->count() > 0 ){
        //pour accéder au information d'un formulaire classique , il suffit juste d'uliser request->get('name')
        dd($request->request->get('age'));
     }
     dump($request);
 
    


      
     

        
        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
          

        ]);
    }
}
