<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

class UserSecurity
{
    public function __construct(

        ManagerRegistry $doctrine,
        Security $security,


    ) {
        $this->doctrine = $doctrine;
        $this->security = $security;
    }

    public function UserSecurity()
    {
        $profilRepository = $this->doctrine->getRepository(Profil::class)->findOneBy(['id_User' => $this->security->getUser()->getId()]);
        return $profilRepository;
    }

    public function ProfilSecurity()
    {
        $userRepository = $this->doctrine->getRepository(User::class)->findOneBy(['id' => $this->security->getUser()->getId()]);
        // dd($userRepository);
        return $userRepository;
    }
}
