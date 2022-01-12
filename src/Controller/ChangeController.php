<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class ChangeController extends AbstractController
{
    /**
     * @Route("/change", name="change")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

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
        if ($num_row == 0) {
            echo "<script>window.location.href = './'";
            header('location:./');
            exit;
        }

        $change = $request->get('change');
        if (isset($change)) {
            $old = md5($request->get('change_password_old'));
            $new = md5($request->get('change_password'));
            $cnew = md5($request->get('confirm_change_password'));

            $server = 'localhost';
            $user = 'root';
            $password = '';
            $bdd = 'symfony';
            $conn = new \mysqli($server, $user, $password, $bdd);
            if (!$conn) {
                echo "<script>alert('Une erreur a eu lieu');</script>";
            }

            $mail = $le;

            if ((str_contains($old, "&") || str_contains($old, "\"") || str_contains($old, "'") || str_contains($old, "(") || str_contains($old, "-") || str_contains($old, "_") || str_contains($old, ")") || str_contains($old, "=") || str_contains($old, "$") || str_contains($old, "*") || str_contains($old, "ù") || str_contains($old, "!") || str_contains($old, ":") || str_contains($old, ";") || str_contains($old, ",") || str_contains($old, "~") || str_contains($old, "#") || str_contains($old, "{") || str_contains($old, "[") || str_contains($old, "|") || str_contains($old, "`") || str_contains($old, "\\") || str_contains($old, "^") || str_contains($old, "@") || str_contains($old, "]") || str_contains($old, "}") || str_contains($old, "°") || str_contains($old, "+")|| str_contains($old, "¨") || str_contains($old, "£") || str_contains($old, "¤") || str_contains($old, "µ") || str_contains($old, "%") || str_contains($old, "§") || str_contains($old, "/") || str_contains($old, ".") || str_contains($old, "?") || str_contains($old, "<") || str_contains($old, ">") || str_contains($old, "²")) || (str_contains($new, "&") || str_contains($new, "\"") || str_contains($new, "'") || str_contains($new, "(") || str_contains($new, "-") || str_contains($new, "_") || str_contains($new, ")") || str_contains($new, "=") || str_contains($new, "$") || str_contains($new, "*") || str_contains($new, "ù") || str_contains($new, "!") || str_contains($new, ":") || str_contains($new, ";") || str_contains($new, ",") || str_contains($new, "~") || str_contains($new, "#") || str_contains($new, "{") || str_contains($new, "[") || str_contains($new, "|") || str_contains($new, "`") || str_contains($new, "\\") || str_contains($new, "^") || str_contains($new, "@") || str_contains($new, "]") || str_contains($new, "}") || str_contains($new, "°") || str_contains($new, "+")|| str_contains($new, "¨") || str_contains($new, "£") || str_contains($new, "¤") || str_contains($new, "µ") || str_contains($new, "%") || str_contains($new, "§") || str_contains($new, "/") || str_contains($new, ".") || str_contains($new, "?") || str_contains($new, "<") || str_contains($new, ">") || str_contains($new, "²")) || (str_contains($cnew, "&") || str_contains($cnew, "\"") || str_contains($cnew, "'") || str_contains($cnew, "(") || str_contains($cnew, "-") || str_contains($cnew, "_") || str_contains($cnew, ")") || str_contains($cnew, "=") || str_contains($cnew, "$") || str_contains($cnew, "*") || str_contains($cnew, "ù") || str_contains($cnew, "!") || str_contains($cnew, ":") || str_contains($cnew, ";") || str_contains($cnew, ",") || str_contains($cnew, "~") || str_contains($cnew, "#") || str_contains($cnew, "{") || str_contains($cnew, "[") || str_contains($cnew, "|") || str_contains($cnew, "`") || str_contains($cnew, "\\") || str_contains($cnew, "^") || str_contains($cnew, "@") || str_contains($cnew, "]") || str_contains($cnew, "}") || str_contains($cnew, "°") || str_contains($cnew, "+")|| str_contains($cnew, "¨") || str_contains($cnew, "£") || str_contains($cnew, "¤") || str_contains($cnew, "µ") || str_contains($cnew, "%") || str_contains($cnew, "§") || str_contains($cnew, "/") || str_contains($cnew, ".") || str_contains($cnew, "?") || str_contains($cnew, "<") || str_contains($cnew, ">") || str_contains($cnew, "²"))) {
                echo "<script>alert('Des champs contiennent des caractères interdits. Pour rappel, on ne peut utiliser que des lettres, ou des chiffres dans le cas du mail et du mot de passe.')</script>";
            }
            else if ($old == "") {
                echo "<script>alert('Veuillez indiquer votre ancien mot de passe')</script>";
            }
            else if ($new == "") {
                echo "<script>alert('Veuillez indiquer votre nouveau mot de passe')</script>";
            }
            else if ($cnew == "") {
                echo "<script>alert('Veuillez confirmer votre nouveau mot de passe')</script>";
            }
            else {
                $req = $conn->query("SELECT `password` FROM `user` WHERE `email` = '$mail'");
                $row = $req->fetch_assoc();

                if ($row['password'] == $new) {
                    echo "<script>alert('Vous ne pouvez pas utiliser votre mot de passe actuel comme nouveau mot de passe.')</script>";
                }
                elseif ($new == $cnew && $row['password'] != $new && $row['password'] == $old) {
                    $upd = $conn->query("UPDATE `user` SET `password` = '$new' WHERE `email` = '$mail'");
                    echo "<script>alert('Votre mot de passe a bien été mis à jour');window.location.href = 'space';</script>";
                }
                else {
                    echo "<script>alert('Une erreur a eu lieu.')</script>";
                }
            }
        }

        return $this->render('change/index.html.twig', [
            'controller_name' => 'ChangeController',
        ]);
    }

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
