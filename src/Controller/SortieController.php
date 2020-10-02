<?php


namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulerSortieType;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SortieController extends AbstractController
{

    /**
     * @Route("/sortie/create", name="createsortie")
     */
    public function creerSortie(Request $req)
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $repoVille = $this->getDoctrine()->getRepository(Ville::class);
        $villes = $repoVille->findAll();
        $user = $this->getUser();


        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);
        $allLieu = $repoLieu->findAll();


        $lieu = $repoLieu->findBy(
            'ville'
        );
        $sortie->setOrganisateur($this->getUser());

        $sortieForm->handleRequest($req);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid() && $req->request->get('enregistrer')) {
            $em = $this->getDoctrine()->getManager();
            $sortie->setEtat('En création');
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Votre sortie a bien été enregistrée.');
            return $this->redirectToRoute('home');
        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid() && $req->request->get('publier')) {

            $currentDatetime = new \DateTime('now');


            if($sortie->getDateLimiteInscription() > $currentDatetime->modify('+ 12 hours') and $sortie->getDateLimiteInscription() < $sortie->getDateSortie())
            {
                $em = $this->getDoctrine()->getManager();
                $sortie->setEtat('Ouvert');
                $sortie->setSiteOrg($this->getUser()->getUserSite());
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'Votre sortie a bien été publiée.');
                return $this->redirectToRoute('home');
            }
            else
            {
                $this->addFlash('danger', "La date limite d'inscription doit être supérieure à ". $currentDatetime->format("Y-m-d H:i"). " et antérieure à la date de la sortie !");
            }

        }

        return $this->render('sortie/creer_sortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'villes' => $villes,
            'user' => $user,
            'lieux' => $allLieu,
        ]);
    }

    /**
     * @Route("/sorties", name="sortie_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);
        $sorties = $repo->findListSortiesWithCategories();

        return $this->render("sortie/list.html.twig", ["sorties" => $sorties]);
    }
    /**
     * @Route("/sortie/update/{id}",
     *     name="updateSortie",
     *     requirements={"id": "\d+"},
     *     methods={"GET", "POST"}
     *     )
     */
    public function modifierSortie(int $id, Request $request)
    {
        $SortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $SortieRepository->find($id);

        if($sortie->getOrganisateur() == $this->getUser() or $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )
        {
            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $repoVille = $this->getDoctrine()->getRepository(Ville::class);
            $villes = $repoVille->findAll();
            $user = $this->getUser();
            $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);
            $allLieu = $repoLieu->findAll();

            $sortieForm->handleRequest($request);


            if ($sortieForm->isSubmitted() && $sortieForm->isValid() && $request->request->get('enregistrer')) {
                $em = $this->getDoctrine()->getManager();
                $sortie->setEtat('En création');
                $sortie->setSiteOrg($this->getUser()->getUserSite());
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'Votre sortie a bien été enregistrée.');
                return $this->redirectToRoute('home');
            }
            // publication
            if ($sortieForm->isSubmitted() && $sortieForm->isValid() && $request->request->get('publier')) {
                $this->publierSortie($id);
                return $this->redirectToRoute('home');
            }
            // suppression
            if ($sortieForm->isSubmitted() && $request->request->get('supprimer')) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($sortie);
                $em->flush();
                $this->addFlash('success', 'Votre sortie a bien été supprimée.');
                return $this->redirectToRoute('home');
            }
        }
        else
        {
            $this->addFlash('danger', 'Cette sortie ne vous appartient pas !');
            return $this->redirectToRoute('home');
        }

        return $this->render('sortie/modifier_sortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie,
            'villes' => $villes,
            'lieux' => $allLieu,
        ]);}
    /**
     * @Route("/sorties/{id}", name="sortie_detail", requirements={"id": "\d+"})
     */
    public function detail(int $id, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo->find($id);

        return $this->render("sortie/detail.html.twig", ["sortie" => $sortie]);
    }

    /**
     * @Route("/sorties/add", name="sortie_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $sortie = new Sortie();

        $user = $this->getUser();
        $sortie->setName($user->getUsername());

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $sortie->setIsPublished(true);
            $sortie->setDateHeureDebut(new \DateTime());

            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "Sortie successfully saved!");
            return $this->redirectToRoute("sortie_detail", ["id" => $sortie->getId()]);
        }

        return $this->render("sortie/add.html.twig", ["sortieForm" => $sortieForm->createView()]);
    }

    /**
     * @Route("/sortie/afficher/{id}",
     *     name="afficher",
     *     requirements={"id": "\d+"},
     *     methods={"GET", "POST"}
     *     )
     */
    public function afficherSortie(int $id)
    {
        $SortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $SortieRepository->find($id);

        $participants = $sortie->getParticipants();

        if (!$sortie) {
            $this->addFlash('danger', "Cette sortie n'existe pas !");
            return $this->redirectToRoute('home');
        }

        return $this->render('sortie/detail.html.twig', [
            "sortie" => $sortie,
            "participants" => $participants
        ]);

    }

    /**
     * @Route("/sortie/inscrire/{id}",
     *      name="inscrire",
     *     requirements={"id": "\d+"},
     *     methods={"GET"})
     */
    public function inscrire(int $id)
    {
        $SortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $SortieRepository->find($id);


        if(count($sortie->getParticipants()) < $sortie->getNbPlace())
        {
            $sortie->addParticipant($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
        }
        else
        {
            $this->addFlash('danger', "Dommage il semblerait qu'il n'y est plus de place pour cette sortie ! :( ");
        }

        return $this->render('inscrire/index.html.twig');
    }

    /**
     * @Route("/sortie/publier/{id}",
     *     name="publier",
     *     requirements={"id": "\d+"},
     *     methods={"GET"}
     *     )
     */
    public function publierSortie(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $SortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $SortieRepository->find($id);

        $currentDatetime = new \DateTime('now');

        if($sortie->getOrganisateur() == $this->getUser() or $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            if($sortie->getDateLimiteInscription() > $currentDatetime->modify('+ 12 hours') and $sortie->getDateLimiteInscription() < $sortie->getDateSortie())
            {
                $sortie->setEtat("Ouvert");
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'Votre sortie a bien été publiée.');
                return $this->redirectToRoute("home");
            }
            else
            {
                $this->addFlash('danger', "La date limite d'inscription doit être supérieure à ". $currentDatetime->format("Y-m-d H:i"). ", soit 12h à compter de maintenant, 
                pour laisser le temps aux participants de s'informer !");
                return $this->redirectToRoute("home");
            }

        }
        else
        {
            $this->addFlash('danger', 'Cette sortie ne vous appartient pas !');
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/sorties/annuler/{id}", name="annuler_sortie")
     */
    public function annuler_sortie(Request $request, EntityManagerInterface $em, Sortie $sortie){

        $participant = $this->getUser();

        $form = $this->createForm(AnnulerSortieType::class, $sortie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $sortie->setInfosSortie($form['infosSortie']->getData());
            $sortie->setEtat("Annulée");

            $em->flush();
            $this->addFlash('success', 'La sortie a été annulée !');

            $this->sortiesListe = $em->getRepository(Sortie::class)->findAll();

            return $this->redirectToRoute('sorties');

        }



        return $this->render('sortie/annuler.html.twig', [
            'page_name' => 'Annuler Sortie',
            'sortie' => $sortie,
            'participants' => $participant,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sortie/desister/{id}",
     *     name="desister",
     *     requirements={"id": "\d+"},
     *     methods={"GET"}
     *     )
     */
    public function desister(int $id)
    {
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        if (!$sortie) {
            $this->addFlash('danger', "Cette sortie n'existe pas !");
            return $this->redirectToRoute('home');
        }

        // supprime l'utilisateur de la liste des participants
        $sortie->removeParticipant($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/serie/delete/{id}", name="serie_delete", requirements={"id": "\d+"})
     */
    public function delete($id, EntityManagerInterface $em)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if ($sortie->getOrganisateur() == $this->getUser() or $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em->remove($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie était sumprimée!');
        }

            return $this->redirectToRoute('home');

        }

}
