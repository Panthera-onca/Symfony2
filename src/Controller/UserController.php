<?php


namespace App\Controller;


use App\Entity\User\User1;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request,
                             EntityManagerInterface $em,
                             PasswordEncoderInterface $encoder){
        $user = new User1();
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
        $user = new User1();
        $form = $this->createForm(RegisterType::class);
        $form->remove('id')
            ->remove('pseudo')
            ->remove('nom')
            ->remove('prenom')
            ->remove('telephone')
            ->remove('mail')
            ->remove('campus')
            ->remove('photo');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $userModif = $em->getRepository(User1::class)->findOneByMail($email);

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
}
