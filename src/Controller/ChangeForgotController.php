<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChangeForgotController extends AbstractController
{
    /**
     * @Route("/change/forgot", name="change_forgot")
     */
    public function index(Request $request): Response
    {

        $new_pass = md5($request->get('new_pass'));
        $cnew_pass = md5($request->get('cnew_pass'));
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
        $change_forgot = $request->get('change_forgot');
        if (isset($change_forgot)) {
            if ((str_contains($new_pass, "&") || str_contains($new_pass, "\"") || str_contains($new_pass, "'") || str_contains($new_pass, "(") || str_contains($new_pass, "-") || str_contains($new_pass, "_") || str_contains($new_pass, ")") || str_contains($new_pass, "=") || str_contains($new_pass, "$") || str_contains($new_pass, "*") || str_contains($new_pass, "ù") || str_contains($new_pass, "!") || str_contains($new_pass, ":") || str_contains($new_pass, ";") || str_contains($new_pass, ",") || str_contains($new_pass, "~") || str_contains($new_pass, "#") || str_contains($new_pass, "{") || str_contains($new_pass, "[") || str_contains($new_pass, "|") || str_contains($new_pass, "`") || str_contains($new_pass, "\\") || str_contains($new_pass, "^") || str_contains($new_pass, "@") || str_contains($new_pass, "]") || str_contains($new_pass, "}") || str_contains($new_pass, "°") || str_contains($new_pass, "+")|| str_contains($new_pass, "¨") || str_contains($new_pass, "£") || str_contains($new_pass, "¤") || str_contains($new_pass, "µ") || str_contains($new_pass, "%") || str_contains($new_pass, "§") || str_contains($new_pass, "/") || str_contains($new_pass, ".") || str_contains($new_pass, "?") || str_contains($new_pass, "<") || str_contains($new_pass, ">") || str_contains($new_pass, "²")) || (str_contains($cnew_pass, "&") || str_contains($cnew_pass, "\"") || str_contains($cnew_pass, "'") || str_contains($cnew_pass, "(") || str_contains($cnew_pass, "-") || str_contains($cnew_pass, "_") || str_contains($cnew_pass, ")") || str_contains($cnew_pass, "=") || str_contains($cnew_pass, "$") || str_contains($cnew_pass, "*") || str_contains($cnew_pass, "ù") || str_contains($cnew_pass, "!") || str_contains($cnew_pass, ":") || str_contains($cnew_pass, ";") || str_contains($cnew_pass, ",") || str_contains($cnew_pass, "~") || str_contains($cnew_pass, "#") || str_contains($cnew_pass, "{") || str_contains($cnew_pass, "[") || str_contains($cnew_pass, "|") || str_contains($cnew_pass, "`") || str_contains($cnew_pass, "\\") || str_contains($cnew_pass, "^") || str_contains($cnew_pass, "@") || str_contains($cnew_pass, "]") || str_contains($cnew_pass, "}") || str_contains($cnew_pass, "°") || str_contains($cnew_pass, "+")|| str_contains($cnew_pass, "¨") || str_contains($cnew_pass, "£") || str_contains($cnew_pass, "¤") || str_contains($cnew_pass, "µ") || str_contains($cnew_pass, "%") || str_contains($cnew_pass, "§") || str_contains($cnew_pass, "/") || str_contains($cnew_pass, ".") || str_contains($cnew_pass, "?") || str_contains($cnew_pass, "<") || str_contains($cnew_pass, ">") || str_contains($cnew_pass, "²"))) {
                echo "<script>alert('Des champs contiennent des caractères interdits. Pour rappel, on ne peut utiliser que des lettres, ou également des chiffres dans le cas du mail et du mot de passe.')</script>";
            }
            else if ($new_pass == "") {
                echo "<script>alert('Veuillez indiquer votre mot de passe')</script>";
            }
            else if ($new_pass != $cnew_pass) {
                echo "<script>alert('Les mots de passe ne correspondent pas')</script>";
            }
            else {
                $req = $conn->query("UPDATE `user` SET `password` = '$new_pass' WHERE `token` = '$token'");
                echo "<script>alert('Votre mot de passe a bien été changé');window.location.href = './';</script>";
            }
        }

        return $this->render('change_forgot/index.html.twig', [
            'controller_name' => 'ChangeForgotController',
        ]);
    }
}
