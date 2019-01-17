<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $produitsRepository = $em->getRepository(Produit::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allProduitsQuery = $produitsRepository->createQueryBuilder('p')
            ->where('p.status != :status')
            ->setParameter('status', 'canceled')
            ->getQuery();

        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');

        // Paginate the results of the query
        $produits = $paginator->paginate(
        // Doctrine Query, not results
            $allProduitsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        // Render the twig view
        return $this->render('boutique/index.html.twig', [
            'produits' => $produits
        ]);
    }
}