<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\ORM\Query;
use App\Entity\Categorie;
use App\Entity\PaymentOrder;
use App\Entity\CommandeOrder;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\DefaultController;



class BoutiqueController extends Controller
{

    public function index(Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->findBy(array(), array('id' => 'DESC'), 3);

        // Retrieve the entity manager of Doctrine

        
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'Boutique',
            'produits' => $produits,
        ]);

    }


    public function produit(Request $request, $idcate = null){

        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();


        if(!empty($idcate)){
            // TODO charger produits de la categorie selectionnée

            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(array('id' => $idcate));


            $em = $this->getDoctrine()->getManager();

            // Get some repository of data, in our case we have an Appointments entity
            $appointmentsRepository = $em->getRepository(Produit::class);

            // Find all the data on the Appointments table, filter your query as you need
            $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
                ->where('p.categorieProduit = :categorie')
                ->setParameter('categorie', $categorie)
                ->getQuery();
        } else {
            //TODO charger tous les produits

            $em = $this->getDoctrine()->getManager();

            // Get some repository of data, in our case we have an Appointments entity
            $appointmentsRepository = $em->getRepository(Produit::class);

            // Find all the data on the Appointments table, filter your query as you need
            $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
                ->getQuery();
        }



        /* @var $paginator \Knp\Component\Pager\Paginator */
        $produits  = $this->get('knp_paginator');

        // Paginate the results of the query
        $produits = $produits->paginate(
        // Doctrine Query, not results
            $allAppointmentsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

        return $this->render('boutique/produit.html.twig',[
            'controller_name'=> 'Les articles',
            'produits' => $produits,
            'categories'=>$categories,
        ]);
    }


    public function article(Produit $article, Request $request, UserInterface $user = null, ObjectManager $manager){

        //IF AJAX ADD PANIER
        if($request->isXmlHttpRequest()){
            //SAVOIR SI L'ARTICLE EXISTE DEJA DANS LA COLLECTION DU PANIER DE L'USER
            $thisPanier = $user->getPanier();
            $thisPanier->addArticle($article);
            $manager->persist($thisPanier);
            $manager->flush();
            $newCountPanier = $thisPanier->getArticles()->count();
            
            $response = new JsonResponse(array("response" => 1, "newCountPanier" => $newCountPanier));
            return $response;
        }

        return $this->render('boutique/article.html.twig',[
            'controller_name'=> 'Un article',
            'article' => $article,
            ]);
    }


    public function panier(UserInterface $user = null, Request $request, ObjectManager $manager)
    {
        if($user == null){
            return $this->redirectToRoute('fos_user_security_login');
        }

        $thisPanier = $this->getDoctrine()
                           ->getRepository(Panier::class)
                           ->createQueryBuilder('c')
                           ->where('c.user = :user')
                           ->setParameter('user', $user)
                           ->setMaxResults(1)
                           ->getQuery()
                           ->getSingleResult();

        //IF AJAX REMOVE ARTICLE PANIER
        if($request->isXmlHttpRequest()){
            $idArticle = $request->request->get('id');
            $article = $this->getDoctrine()
                            ->getRepository(Produit::class)
                            ->find($idArticle);
            $thisPanier->removeArticle($article);
            $manager->persist($thisPanier);
            $manager->flush();

            $response = new JsonResponse("1");
            return $response;
        }

        $thisPanier = $thisPanier->getArticles();
        
        return $this->render('boutique/panier.html.twig', [
            'controller_name' => 'Mon panier',
            'articlesPanier' => $thisPanier,
            ]);          
    }


    public function historique(UserInterface $user, Request $request, ObjectManager $manager)
    {
        
        $rawSql =   "SELECT * FROM commande_order WHERE user_id = :utilisateur";
        $stmt = $manager->getConnection()->prepare($rawSql);
        $utilisateur = $user->getId();
        $stmt->bindValue('utilisateur', $utilisateur);
        $stmt->execute();
        $commande = $stmt->fetchAll();
        
        return $this->render('boutique/historique.html.twig', [
            'controller_name' => 'Mes achats',
            'commande' => $commande,
            ]);          
    }

}
