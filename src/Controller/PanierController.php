<?php

namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier")
     */
    public function index()
    {
        $pdo = $this->getDoctrine()->getManager();
        $paniers = $pdo->getRepository(Panier::class)->findAll();
        $size = count($pani);

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'size' => $size,
        ]);
    
}

    /**
     * @Route ("panier/delete/{id}", name="delete_panier")
     */
  
    public function delete(Panier $panier=null){

        if($panier !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($panier);
            $pdo->flush();

        }
        return $this->redirectToRoute('panier');
    }


}
