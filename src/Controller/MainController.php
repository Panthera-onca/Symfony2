<?php


namespace App\Controller;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\User\User1;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();


        $archiveRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $archive= $archiveRepository->findSortiesNonArchivees(new \Symfony\Component\Validator\Constraints\DateTime('now'));
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepository->findSortiesPlusRecentes();
        $siteRepository = $this->getDoctrine()->getRepository(Site::class);
        $sites = $siteRepository->findAll();
        $currentDateTime = new \DateTime('now');

        foreach($sorties as $sortie)
        {
            if($sortie->getDateLimiteInscription() < $currentDateTime and $sortie->getDateSortie() > $currentDateTime)
            {
                $sortie->setEtat("Clôturée");
                $em->persist($sortie);
            }
            if($sortie->getDateSortie() < $currentDateTime and $sortie->getEtat() != "En création")
            {
                $sortie->setEtat("Passée");
                $em->persist($sortie);
            }
            if($sortie->getDateSortie() == $currentDateTime)
            {
                $sortie->setEtat("En cours");
                $em->persist($sortie);
            }

        }
        $em->flush();

        if($request->request->get("site-select")){
            $siteSelect = $siteRepository->find($request->request->get("site-select"));

            $sorties = $sortieRepository->findBy(
                ['siteOrg' => $siteSelect],
                ['dateSortie' => 'DESC']
            );
        }
        if($request->request->get("search-bar")){
            $sorties = $sortieRepository->findSortiesContenant($request->request->get("search-bar"));
        }
        if($request->request->get("dateEntre") && $request->request->get("dateEt")){


            $dateEntre = new \DateTime($request->request->get("dateEntre"));
            $dateEt = new \DateTime($request->request->get("dateEt"));

            $sorties = $sortieRepository->findSortiesEntreDates($dateEntre, $dateEt);
        }

        if($request->request->get("sortOrg")){
            $sorties = $sortieRepository->findBy(
                ['organisateur' => $this->getUser()],
                ['dateSortie' => 'DESC']
            );
        }
        if($request->request->get("sortInsc")){

            $sorties = $this->getUser()->getSorties();
        }
        if($request->request->get("sortPass")){
            $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
            $sorties = $sortieRepository->findSortiesPass();
        }

        return $this->render('default/home.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => $user,
            'sorties' => $sorties,
            'sites' => $sites
        ]);
    }

    /**
     * @Route("/my_profil", name="my_profil"),
     * ;
     */
    public function showMyProfil() {

        $userRepo = $this->getDoctrine()->getRepository(User1::class);
        $currentUser =$userRepo->find($this->getUser());


        return $this->render('user/user_profil.html.twig', [
            'currentUser' => $currentUser
        ]);
    }


    /**
     * @Route("/photo", name="photo")
     */
    public function photo(Request $request){
        $file = $this->getUser()->getPhotoPath($request->request->get('photo'));
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move(
            $this->getParameter('image_directory'),$fileName
        );

        $this->getUser()->setPhotoPath($fileName);
        $em = $this->getDoctrine()->getManager();
        $em->persist($this->getUser());
        $em->flush();

        return $this->render('ville/ville.html.twig');

    }

    /**
     * @Route("/update_my_profil", name="update_my_profil"),
     *
     */
    public function updateMyProfil(Request $request, UserPasswordEncoderInterface $passwordEncoder) {

        $userRepo = $this->getDoctrine()->getRepository(User1::class);
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

        $userRepo = $this->getDoctrine()->getRepository(User\User1::class);
        $roles[] = 'ROLE_USER';
        $users = $userRepo->findAll();


        if ($request->request->get('activer')){
            $userRepo = $this->getDoctrine()->getRepository(User\User1::class);

            $currentUser = $userRepo->find($request->request->get('activer'));

            $currentUser->setEtat(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();

            return $this->redirectToRoute('updateEtat');

        }

        if ($request->request->get('désactiver')){
            $userRepo = $this->getDoctrine()->getRepository(User\User1::class);

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
