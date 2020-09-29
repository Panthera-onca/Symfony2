<?php


namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SortieController extends AbstractController
{
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
     * @Route("/serie/delete/{id}", name="serie_delete", requirements={"id": "\d+"})
     */
    public function delete($id, EntityManagerInterface $em){
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        $em->remove($sortie);
        $em->flush();

        $this->addFlash('success', 'Sortie était sumprimée!');

        return $this->redirectToRoute('home');

    }
}
