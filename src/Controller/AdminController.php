<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Description of AdminController
 *
 * @author windows
 */
class AdminController extends AbstractController {
    //put your code here
    /**
     * @Route("/tableauDeBord",name="tableauDeBord")
     * @return Response
     */
    public function commandesController(EntityManagerInterface $em) {
        $sql = "SELECT count(*) as users from user";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $nbUser = $stmt->fetch()['users'];
        
        $sql = "SELECT count(*) as produits from produits";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $nbProduits = $stmt->fetch()['produits'];
        $sql = "SELECT count(*) as commandes from commandes";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $nbcommandes = $stmt->fetch()['commandes'];
        $sql = "SELECT YEAR(com_date) as anneeca, sum(contenu_quantite*contenu_prix) as ca "
                . "from contenu inner join commandes on commandes.com_id = contenu.com_id "
                . "group by YEAR(com_date)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $chiffreAffaire = $stmt->fetchAll();
        
        
        return $this->render('home/tableauDeBord.html.twig', [
                    'nbUser' => $nbUser,
                    'nbProduits' => $nbProduits,
                    'nbcommandes' => $nbcommandes,
                    'chiffreAffaire' => $chiffreAffaire
        ]);
    }
}