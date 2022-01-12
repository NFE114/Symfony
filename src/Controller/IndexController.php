<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $reg = $request->get('register');
        if (isset($reg)) {
            $nom = $request->get('register_nom');
            $prenom = $request->get('register_prenom');
            $email = $request->get('register_email');
            $password = md5($request->get('register_password'));
            $cpassword = md5($request->get('register_cpassword'));
            $token = md5(crypt($email, $nom));
            $valid = 0;
            
            $showAlert = false; 
            $showError = false; 
                
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                
                $server = 'localhost';
                $user = 'root';
                $db_password = '';
                $bdd = 'symfony';
                $conn = new \mysqli($server, $user, $db_password, $bdd);
                if (!$conn) {
                    echo "<script>alert('Une erreur a eu lieu');</script>";
                }
                $sql = "SELECT * FROM users WHERE email='$email'";
                $result = mysqli_query($conn, $sql);
                $repository = $doctrine->getRepository(User::class);
                $reg_m = $repository->findBy(['email' => $email]);
                $num = count($reg_m);
                $session->set('token', $token);
                $fullname = $prenom." ".$nom;
                if($num == 0) {
                    if ((str_contains($nom, "&") || str_contains($nom, "\"") || str_contains($nom, "'") || str_contains($nom, "(") || str_contains($nom, "-") || str_contains($nom, "_") || str_contains($nom, ")") || str_contains($nom, "=") || str_contains($nom, "$") || str_contains($nom, "*") || str_contains($nom, "ù") || str_contains($nom, "!") || str_contains($nom, ":") || str_contains($nom, ";") || str_contains($nom, ",") || str_contains($nom, "~") || str_contains($nom, "#") || str_contains($nom, "{") || str_contains($nom, "[") || str_contains($nom, "|") || str_contains($nom, "`") || str_contains($nom, "\\") || str_contains($nom, "^") || str_contains($nom, "@") || str_contains($nom, "]") || str_contains($nom, "}") || str_contains($nom, "°") || str_contains($nom, "+") || str_contains($nom, "0") || str_contains($nom, "1") || str_contains($nom, "2") || str_contains($nom, "3") || str_contains($nom, "4") || str_contains($nom, "5") || str_contains($nom, "6") || str_contains($nom, "7") || str_contains($nom, "8") || str_contains($nom, "9") || str_contains($nom, "¨") || str_contains($nom, "£") || str_contains($nom, "¤") || str_contains($nom, "µ") || str_contains($nom, "%") || str_contains($nom, "§") || str_contains($nom, "/") || str_contains($nom, ".") || str_contains($nom, "?") || str_contains($nom, "<") || str_contains($nom, ">") || str_contains($nom, "²")) || (str_contains($prenom, "&") || str_contains($prenom, "\"") || str_contains($prenom, "'") || str_contains($prenom, "(") || str_contains($prenom, "-") || str_contains($prenom, "_") || str_contains($prenom, ")") || str_contains($prenom, "=") || str_contains($prenom, "$") || str_contains($prenom, "*") || str_contains($prenom, "ù") || str_contains($prenom, "!") || str_contains($prenom, ":") || str_contains($prenom, ";") || str_contains($prenom, ",") || str_contains($prenom, "~") || str_contains($prenom, "#") || str_contains($prenom, "{") || str_contains($prenom, "[") || str_contains($prenom, "|") || str_contains($prenom, "`") || str_contains($prenom, "\\") || str_contains($prenom, "^") || str_contains($prenom, "@") || str_contains($prenom, "]") || str_contains($prenom, "}") || str_contains($prenom, "°") || str_contains($prenom, "+") || str_contains($prenom, "0") || str_contains($prenom, "1") || str_contains($prenom, "2") || str_contains($prenom, "3") || str_contains($prenom, "4") || str_contains($prenom, "5") || str_contains($prenom, "6") || str_contains($prenom, "7") || str_contains($prenom, "8") || str_contains($prenom, "9") || str_contains($prenom, "¨") || str_contains($prenom, "£") || str_contains($prenom, "¤") || str_contains($prenom, "µ") || str_contains($prenom, "%") || str_contains($prenom, "§") || str_contains($prenom, "/") || str_contains($prenom, ".") || str_contains($prenom, "?") || str_contains($prenom, "<") || str_contains($prenom, ">") || str_contains($prenom, "²")) || (str_contains($email, "&") || str_contains($email, "\"") || str_contains($email, "'") || str_contains($email, "(") || str_contains($email, ")") || str_contains($email, "=") || str_contains($email, "$") || str_contains($email, "*") || str_contains($email, "!") || str_contains($email, ":") || str_contains($email, ";") || str_contains($email, "~") || str_contains($email, "#") || str_contains($email, "{") || str_contains($email, "[") || str_contains($email, "|") || str_contains($email, "`") || str_contains($email, "\\") || str_contains($email, "^") || str_contains($email, "]") || str_contains($email, "}") || str_contains($email, "°") || str_contains($email, "+") || str_contains($email, "¨") || str_contains($email, "£") || str_contains($email, "¤") || str_contains($email, "µ") || str_contains($email, "%") || str_contains($email, "§") || str_contains($email, "/") || str_contains($email, "?") || str_contains($email, "<") || str_contains($email, ">") || str_contains($email, "²")) || (str_contains($password, "&") || str_contains($password, "\"") || str_contains($password, "'") || str_contains($password, "(") || str_contains($password, "-") || str_contains($password, "_") || str_contains($password, ")") || str_contains($password, "=") || str_contains($password, "$") || str_contains($password, "*") || str_contains($password, "ù") || str_contains($password, "!") || str_contains($password, ":") || str_contains($password, ";") || str_contains($password, ",") || str_contains($password, "~") || str_contains($password, "#") || str_contains($password, "{") || str_contains($password, "[") || str_contains($password, "|") || str_contains($password, "`") || str_contains($password, "\\") || str_contains($password, "^") || str_contains($password, "@") || str_contains($password, "]") || str_contains($password, "}") || str_contains($password, "°") || str_contains($password, "+")|| str_contains($password, "¨") || str_contains($password, "£") || str_contains($password, "¤") || str_contains($password, "µ") || str_contains($password, "%") || str_contains($password, "§") || str_contains($password, "/") || str_contains($password, ".") || str_contains($password, "?") || str_contains($password, "<") || str_contains($password, ">") || str_contains($password, "²")) || (str_contains($cpassword, "&") || str_contains($cpassword, "\"") || str_contains($cpassword, "'") || str_contains($cpassword, "(") || str_contains($cpassword, "-") || str_contains($cpassword, "_") || str_contains($cpassword, ")") || str_contains($cpassword, "=") || str_contains($cpassword, "$") || str_contains($cpassword, "*") || str_contains($cpassword, "ù") || str_contains($cpassword, "!") || str_contains($cpassword, ":") || str_contains($cpassword, ";") || str_contains($cpassword, ",") || str_contains($cpassword, "~") || str_contains($cpassword, "#") || str_contains($cpassword, "{") || str_contains($cpassword, "[") || str_contains($cpassword, "|") || str_contains($cpassword, "`") || str_contains($cpassword, "\\") || str_contains($cpassword, "^") || str_contains($cpassword, "@") || str_contains($cpassword, "]") || str_contains($cpassword, "}") || str_contains($cpassword, "°") || str_contains($cpassword, "+")|| str_contains($cpassword, "¨") || str_contains($cpassword, "£") || str_contains($cpassword, "¤") || str_contains($cpassword, "µ") || str_contains($cpassword, "%") || str_contains($cpassword, "§") || str_contains($cpassword, "/") || str_contains($cpassword, ".") || str_contains($cpassword, "?") || str_contains($cpassword, "<") || str_contains($cpassword, ">") || str_contains($cpassword, "²"))) {
                        echo "<script>alert('Des champs contiennent des caractères interdits. Pour rappel, on ne peut utiliser que des lettres, ou également des chiffres dans le cas du mail et du mot de passe.')</script>";
                    }
                    else if ($email == "") {
                        echo "<script>alert('Veuillez indiquer une adresse e-mail')</script>";
                    }
                    else if ($nom == "") {
                        echo "<script>alert('Veuillez indiquer votre nom')</script>";
                    }
                    else if ($prenom == "") {
                        echo "<script>alert('Veuillez indiquer votre prénom')</script>";
                    }
                    else if ($password == "") {
                        echo "<script>alert('Veuillez choisir un mot de passe')</script>";
                    }
                    else if ($password != $cpassword) {
                        echo "<script>alert('Les mots de passe ne correspondent pas')</script>";
                    }
                    else if($password == $cpassword) {
                                            
                        $sql = "INSERT INTO `user` ( `nom`, 
                            `prenom`, `email`, `password`, `token`, `valid`) VALUES ('$nom', 
                            '$prenom', '$email', '$password', '$token', $valid)";
                
                        $result = $conn->query($sql);
                
                        if ($result) {
                            $showAlert = true; 
                        }
                        else {
                            echo "<script>alert('Une erreur a eu lieu')</script>";
                        }

                        require '..\vendor\phpmailer\phpmailer\src\Exception.php';
                        require '..\vendor\phpmailer\phpmailer\src\PHPMailer.php';
                        require '..\vendor\phpmailer\phpmailer\src\SMTP.php';

                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->Port = 465;
                        $mail->SMTPAuth = 1;

                        if($mail->SMTPAuth){
                        $mail->SMTPSecure = 'ssl';
                        $mail->Username   =  'connexionprogramme@gmail.com'; 
                        $mail->Password   =  '@MotDePasse50$';
                        }
                        $mail->CharSet = 'UTF-8';
                        $mail->smtpConnect();

                        $mail->setFrom('connexionprogramme@gmail.com', 'Prgramme de connexion');
                        $mail->FromName   = 'Programme de connexion';

                        $mail->Subject    =  'Validation du compte';
                        $mail->MsgHTML("Bonjour,<br>Vous venez de vous inscrire sur notre site. Pour pouvoir y accéder, veuillez cliquer sur le lien ci-dessous pour valider votre adresse e-mail :<br><a href='http://localhost:8000/verif?$token'>Vérifier votre adresse e-mail</a><br><br>Toute l'équipe espère que vous vous plairez sur notre site.<br><br><small>En cas de problème de connexion, n'hésitez pas à contacter l'équipe en répondant à ce mail.</small>");
                        $mail->IsHTML(true);

                        $fullname = "$prenom"." "."$nom";
                        $mail->AddAddress("$email","$fullname");

                        if (!$mail->send()) {
                            echo "<script>alert('Une erreur a eu lieu')</script>";
                        }
                        else {
                            echo "<script>alert('Votre compte a bien été créé. Veuillez aller valider votre adresse mail.')</script>";
                        }

                    } 
                    else { 
                        $showError = "Les mots de passe ne correspondent pas"; 
                    }      
                }
                
            if($num != 0)
            {
                echo "<script>alert('Cet email est déjà utilisé')</script>"; 
            } 
                
            }
            echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
        }

        $log = $request->get('login');
        if (isset($log)) {
            $repository = $doctrine->getRepository(User::class);
            $lmail = $request->get('login_email');
            $lpassword = md5($request->get('login_password'));
            $server = 'localhost';
            $user = 'root';
            $password = '';
            $bdd = 'symfony';
            $conn = new \mysqli($server, $user, $password, $bdd);
            if (!$conn) {
                echo "<script>alert('Une erreur a eu lieu');</script>";
            }
            if ((str_contains($lmail, "&") || str_contains($lmail, "\"") || str_contains($lmail, "'") || str_contains($lmail, "(") || str_contains($lmail, ")") || str_contains($lmail, "=") || str_contains($lmail, "$") || str_contains($lmail, "*") || str_contains($lmail, "!") || str_contains($lmail, ":") || str_contains($lmail, ";") || str_contains($lmail, "~") || str_contains($lmail, "#") || str_contains($lmail, "{") || str_contains($lmail, "[") || str_contains($lmail, "|") || str_contains($lmail, "`") || str_contains($lmail, "\\") || str_contains($lmail, "^") || str_contains($lmail, "]") || str_contains($lmail, "}") || str_contains($lmail, "°") || str_contains($lmail, "+") || str_contains($lmail, "¨") || str_contains($lmail, "£") || str_contains($lmail, "¤") || str_contains($lmail, "µ") || str_contains($lmail, "%") || str_contains($lmail, "§") || str_contains($lmail, "/") || str_contains($lmail, "?") || str_contains($lmail, "<") || str_contains($lmail, ">") || str_contains($lmail, "²")) || (str_contains($lpassword, "&") || str_contains($lpassword, "\"") || str_contains($lpassword, "'") || str_contains($lpassword, "(") || str_contains($lpassword, "-") || str_contains($lpassword, "_") || str_contains($lpassword, ")") || str_contains($lpassword, "=") || str_contains($lpassword, "$") || str_contains($lpassword, "*") || str_contains($lpassword, "ù") || str_contains($lpassword, "!") || str_contains($lpassword, ":") || str_contains($lpassword, ";") || str_contains($lpassword, ",") || str_contains($lpassword, "~") || str_contains($lpassword, "#") || str_contains($lpassword, "{") || str_contains($lpassword, "[") || str_contains($lpassword, "|") || str_contains($lpassword, "`") || str_contains($lpassword, "\\") || str_contains($lpassword, "^") || str_contains($lpassword, "@") || str_contains($lpassword, "]") || str_contains($lpassword, "}") || str_contains($lpassword, "°") || str_contains($lpassword, "+")|| str_contains($lpassword, "¨") || str_contains($lpassword, "£") || str_contains($lpassword, "¤") || str_contains($lpassword, "µ") || str_contains($lpassword, "%") || str_contains($lpassword, "§") || str_contains($lpassword, "/") || str_contains($lpassword, ".") || str_contains($lpassword, "?") || str_contains($lpassword, "<") || str_contains($lpassword, ">") || str_contains($lpassword, "²"))) {
                echo "<script>alert('Des champs contiennent des caractères interdits. Pour rappel, on ne peut utiliser que des lettres, ou également des chiffres dans le cas du mail et du mot de passe.')</script>";
            }
            else if ($lmail == "") {
                echo "<script>alert('Veuillez indiquer votre adresse e-mail')</script>";
            }
            else if ($lpassword == "") {
                echo "<script>alert('Veuillez indiquer votre mot de passe')</script>";
            }
            else {
                $req = $conn->query("SELECT `valid` FROM `user` WHERE `email` = '$lmail'");
                $row = $req->fetch_assoc();
                if ($row['valid']) {
                    $query = mysqli_query($conn, "SELECT * FROM `user` WHERE `email`='$lmail'");
                    $row = mysqli_fetch_array($query);
                    $row = $row['password'];
                    if ($row == $lpassword) {
                    $repository = $doctrine->getRepository(User::class);
                    $reg_m = $repository->findBy(['email' => $lmail]);
                    $num_row = count($reg_m);
                    if ($num_row != 0) 
                        {	
                            $session->set('user', $lmail);
                            echo "<script>window.location.href = 'space'</script>";
                        }
                    else
                        {
                            echo "<script>alert('Identifiants invalides')</script>";
                            echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
                        }
                    }
                    else{
                        echo "<script>alert('Une erreur a eu lieu')</script>";
                    }

                }
            else {
                echo "<script>alert('Vous n\'avez pas validé votre adresse e-mail ou créé votre compte')</script>";
                echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}; location.reload()</script>";
            }
        }
        echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
