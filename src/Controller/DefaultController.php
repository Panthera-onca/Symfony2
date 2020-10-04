<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render("default/home.html.twig");
    }

    /**
     * @Route("/my_profil", name="my_profil"),
     * ;
     */
    public function showMyProfil() {

        $userRepo = $this->getDoctrine()->getRepository(Participants::class);
        $currentUser =$userRepo->find($this->getUser());


        return $this->render('user/user_profil.html.twig', [
            'currentUser' => $currentUser
        ]);
    }




    /**
     * @Route("/update_my_profil", name="update_my_profil"),
     * ;
     */
    public function updateMyProfil(Request $request, UserPasswordEncoderInterface $passwordEncoder) {

        $userRepo = $this->getDoctrine()->getRepository(Participants::class);
        $currentUser =$userRepo->find($this->getUser());

        $currentUser->setPseudo($request->request->get('pseudo'));
        $currentUser->setPrenom($request->request->get('prenom'));
        $currentUser->setNom($request->request->get('nom'));
        $currentUser->setTelephone($request->request->get('tel'));
        $password = $request->request->get('password');
        if(!empty($password)){
            $passwordEncoded = $passwordEncoder->encodePassword($currentUser, $password);
            $currentUser->setPassword($passwordEncoded);
        }
        $currentUser->setVille($request->request->get('ville'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentUser);
        $em->flush();

        return $this->redirectToRoute('home', [
            'currentUser' => $currentUser
        ]);
    }


    /**
     * @Route("/etat", name="updateEtat")
     */
    public function etatUser (Request $request){

        $userRepo = $this->getDoctrine()->getRepository(Participants::class);
        $roles[] = 'ROLE_USER';
        //$users = $userRepo->findOneBySomeField( array('roles' => 'ROLE_USER'));
        $users = $userRepo->findAll();


        if ($request->request->get('activer')){

            // dd($request->request->get('id'));
            $userRepo = $this->getDoctrine()->getRepository(Participants::class);

            $currentUser = $userRepo->find($request->request->get('activer'));

            $currentUser->setEtat(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();

            return $this->redirectToRoute('updateEtat');

        }

        if ($request->request->get('désactiver')){

            // dd($request->request->get('id'));
            $userRepo = $this->getDoctrine()->getRepository(Participants::class);

            $currentUser = $userRepo->find($request->request->get('désactiver'));

            $currentUser->setEtat(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();

            return $this->redirectToRoute('updateEtat');

        }
        return $this->render('user/desactive.html.twig',

            [
                'users' => $users            ]);

    }

}
