<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();

        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $fichier = $form->get('photoUpload')->getData();

            if($fichier){
                $nomFichier = uniqid() . '.' . $fichier->guessExtension();

                try{
                    $fichier->move(
                    $this->getParameter('upload_dir'),
                    $nomFichier
                    );
                }
                catch(FileException $e){
                    $this->addFlash('error', "Impossible d'uploader l'image");
                    return $this->redirecttoRoute('produit');
                }

                $produit->setPhoto($nomFichier);
            }


            
            $pdo->persist($produit);
            $pdo->flush();
        }

        $produits = $pdo->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form_ajout' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/{id}", name="edit_produit")
     */

    public function produit(Produit $produit=null, Request $request){

        if($produit != null){
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($produit);
            $pdo->flush();
        }

        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
            'form_edit' => $form->createView(),
            
        ]);

        }
        else{
                return $this->redirectToRoute('produit');

        }
    }

    /**
     * @Route ("produit/delete/{id}", name="delete_produit")
     */

    public function delete(Produit $produit=null){

        if($produit !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($produit);
            $pdo->flush();
        }
        return $this->redirectToRoute('paniers');
    }

}
