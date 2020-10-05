<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;

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
     * @Route("/register", name="register")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function register(Request $request,
                             EntityManagerInterface $em,
                             UserPasswordEncoderInterface $encoder ){
        $user = new Participants();
        $user->setDateCreated(new \DateTime());
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

        }

        return $this->render("user/register.html.twig", [
            "registerForm"=>$registerForm->createView()

        ]);
    }

    /**
     * @Route("/reset_password/{email}", name="reset_password_email")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, $email){
        $user = new Participants();
        $form = $this->createForm(RegisterType::class);
        $form->remove('id')
            ->remove('pseudo')
            ->remove('nom')
            ->remove('role')
            ->remove('telephone')
            ->remove('mail')
            ->remove('campus');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $userModif = $em->getRepository(Participants::class)->findOneByMail($email);

            $userModif->setPassword('');
            $password = $passwordEncoder->encodePassword($user, $form->getData()->getPassword());
            $userModif->setPassword($password);

            $em->persist($userModif);
            $em->flush();

            $this->addFlash('success','Votre mot de passe à été modifié !');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/reset_password.html.twig',[
            'page_name' => 'Réinitialiser le mot de passe',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils){
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }



    /**
     * @Route("/user_profil", name="update_my_profil"),
     * ;
     */
    public function updateMyProfil(Request $request, UserPasswordEncoderInterface $passwordEncoder) {

        $userRepo = $this->getDoctrine()->getRepository(Participants::class);
        $currentUser =$userRepo->find($this->getUser());

        $currentUser->setPseudo($request->request->get('pseudo'));
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
