<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SpaceController extends AbstractController
{
    /**
     * @Route("/space", name="space")
     */
    public function index(ManagerRegistry $doctrine, Connection $conn, Request $request): Response
    {
        \ob_start();
        $session = $this->requestStack->getSession();
        $server = 'localhost';
        $user = 'root';
        $password = '';
        $bdd = 'symfony';
        $le = $session->get('user');
        $connection = new \mysqli($server, $user, $password, $bdd);
        if (!$connection) {
            echo "<script>alert('Une erreur a eu lieu');</script>";
        }
        $query = mysqli_query($connection, "SELECT * FROM `user` WHERE `email`='$le'");
        $repository = $doctrine->getRepository(User::class);
        $reg_m = $repository->findBy(['email' => $le]);
        $num_row = count($reg_m);
        $row = $query->fetch_assoc();
        $nom = $row['nom'];
        $prenom = $row['prenom'];
        $fullname = $prenom." ".$nom;
        if ($num_row == 0) {
            echo "<script>window.location.href = './'";
            header('location:./');
            exit;
        }

        $out = $request->get('logout');
        if (isset($out)) {
            $session->set('user', 'slkdkdbvqdkfbghgfkivlfidhvkbdnlvfdiuskhvdlinub');
            header('location:./');
            echo "<script>window.location.href = './'</script>";
            echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
        }

        $filtre = $request->get('filtre');
        $vendeur = $request->get('vendeur');
        $region = $request->get('region');
        $min = $request->get('min_montant');
        $max = $request->get('max_montant');
        $date = $request->get('date');

        if (isset($filtre)) {
            $server = 'localhost';
            $user = 'root';
            $password = '';
            $bdd = 'symfony';
            $connection = new \mysqli($server, $user, $password, $bdd);
            if (!$connection) {
                echo "<script>alert('Une erreur a eu lieu');</script>";
            }
            
            $res = $connection->query("SELECT * FROM `rapports` WHERE `vendeur` LIKE '%".$vendeur."%' AND `region` LIKE '%".$region."%' AND `montant` BETWEEN '$min' AND '$max' AND `date` LIKE '%".$date."% 00:00:00'");
            echo "<div style='margin-left: 40%; margin-top: 570px; position: absolute'><br>";
            echo "Date - Région - Vendeur - Montant<br><br>";
            $filename = 'liste.csv';
            if (file_exists($filename)) {
                unlink($filename);
            }
            $fp = fopen($filename, 'a');
            while($row = $res->fetch_assoc()){
                echo $row['date']." - ".$row['region']." - ".$row['vendeur']." - ".$row['montant']."€<br>";
                $somecontent = $row['date']." - ".$row['region']." - ".$row['vendeur']." - ".$row['montant']."€\r\n";
                if (fwrite($fp, $somecontent) === FALSE) {
                    echo "Erreur";
                    exit;
                }
            }
            fclose($fp);
            $fc = file_get_contents($filename);
            if ($fc != "") {
                echo "<br><a style='margin-left: 30% !important;' href=\"$filename\" name=\"export\"><button>Exporter les données</button></a>";
            }
            else{
                echo "Aucune donnée";
            }
            echo "</div>";
        }
        if (isset($_POST['reinit'])) {
            echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
        }

        $liste = $request->get('liste');
        if (isset($liste)) {
            $res = $connection->query("SELECT * FROM `rapports`");
            echo "<div style='margin-left: 40%; margin-top: 570px; position: absolute'><br>";
            echo "Date - Région - Vendeur - Montant<br><br>";
            $filename = 'liste.csv';
            if (file_exists($filename)) {
                unlink($filename);
            }
            $fp = fopen($filename, 'a');
            while($row = $res->fetch_assoc()){
                echo $row['date']." - ".$row['region']." - ".$row['vendeur']." - ".$row['montant']."€<br>";
                $somecontent = $row['date']." - ".$row['region']." - ".$row['vendeur']." - ".$row['montant']."€\r\n";
                if (fwrite($fp, $somecontent) === FALSE) {
                    echo "Erreur";
                    exit;
                }
            }
            fclose($fp);
            echo "<a style='margin-left: 30% !important;' href=\"$filename\" name=\"export\"><button>Exporter les données</button></a>";
            echo "</div>";
        }
        echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";

        $rapports = $conn->fetchAllAssociative('SELECT * FROM rapports');
        $vendeurs = $conn->fetchAllAssociative('SELECT * FROM vendeurs');
        $regions = $conn->fetchAllAssociative('SELECT * FROM regions');
        return $this->render('space/index.html.twig', [
            'controller_name' => 'SpaceController',
            'rapports' => $rapports,
            'vendeurs' => $vendeurs,
            'regions' => $regions,
            'user' => $fullname,
        ]);
    }

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
