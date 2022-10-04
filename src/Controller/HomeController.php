<?php

namespace App\Controller;

use App\Entity\Profil;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{

    public function __construct(
        ManagerRegistry $doctrine,
        Security $security,
    ) {
        $this->doctrine = $doctrine;
        $this->security = $security;
    }
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        if (!empty($this->security->getUser())) {
            $profilRepository = $this->doctrine->getRepository(Profil::class)->findOneBy(['id_User' => $this->security->getUser()->getId()]);
        } else {
            $profilRepository = null;
        }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'profilRepository' => $profilRepository
        ]);
    }
}
