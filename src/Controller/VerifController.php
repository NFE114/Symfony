<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VerifController extends AbstractController
{
    /**
     * @Route("/verif", name="verif")
     */
    public function index(Request $request): Response
    {

        $uri = $request->getUri();
        if (($pos = strpos($uri, "?")) != FALSE) { 
            $token = substr($uri, $pos+1);
            $token = substr($token, 0, -1);
        }
        else{
            echo "<script>alert('Une erreur a eu lieu')</script>";
            echo "<script>window.location.href = './'<script>";
        }

        $server = 'localhost';
        $user = 'root';
        $password = '';
        $bdd = 'symfony';
        $conn = new \mysqli($server, $user, $password, $bdd);
        if (!$conn) {
            echo "<script>alert('Une erreur a eu lieu');</script>";
        }
        $res = $conn->query("SELECT DISTINCT `valid` FROM `user` WHERE `token` = '$token'");
        $row = $res->fetch_assoc();

        echo "<div class=\"header\">
        <div style=\"padding: 25px;\"><a href='./' style=\"color: black;\"><i class=\"fa fa-home\" aria-hidden=\"true\">&nbsp&nbsp Accueil</i></a></div>
        <h1>Vérification de l'adresse e-mail</h1>
        <div></div>
        </div>
        <hr>";

        if ($row != null && $row['valid'] == 1) {
            echo "<div style='display: flex; justify-content: center'><p>Vous avez déjà validé votre e-mail !</p></div>";
        }
        else {
            echo "<div style='display: flex; justify-content: center'><form action='' method='post'><input type='submit' class='btn btn-primary' name='verif' id='verif' value='Vérifier votre email'></form</div>";
            if (isset($_POST['verif'])) {
                $req = $conn->query("UPDATE `user` SET `valid` = 1 WHERE `token` = '$token'");
                echo "<script>alert('Votre e-mail a bien été vérifié');window.location.href = './';</script>";
                echo "<script>document.getElementById('verif').disable = true;</script>";
            }
        }

        return $this->render('verif/index.html.twig', [
            'controller_name' => 'VerifController',
        ]);
    }
}
