<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
     public function admin(){
         $this->denyAccessUnlessGranted("ROLE_ADMIN");

         if(!$this->isGranted("ROLE_ADMIN")){
            throw new AccessDeniedException("interdit aux simples utilisateurs");
         }
     }
    /**
     * @Route("", name="admin_home")
     */
    public function home(){
        return new Response("ok!");
    }

    /**
     * @Route("/test", name="admin_test")
     */
    public function test(){
        return new Response("ok!");
    }
}
