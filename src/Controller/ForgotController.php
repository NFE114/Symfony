<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class ForgotController extends AbstractController
{
    /**
     * @Route("/forgot", name="forgot")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $forgot = $request->get('forgot');
        if (isset($forgot)) {
            $server = 'localhost';
            $user = 'root';
            $password = '';
            $bdd = 'symfony';
            $conn = new \mysqli($server, $user, $password, $bdd);
            if (!$conn) {
                echo "<script>alert('Une erreur a eu lieu');</script>";
            }

            $email = $request->get('forgot_email');
    
            if ((str_contains($email, "&") || str_contains($email, "\"") || str_contains($email, "'") || str_contains($email, "(") || str_contains($email, ")") || str_contains($email, "=") || str_contains($email, "$") || str_contains($email, "*") || str_contains($email, "!") || str_contains($email, ":") || str_contains($email, ";") || str_contains($email, "~") || str_contains($email, "#") || str_contains($email, "{") || str_contains($email, "[") || str_contains($email, "|") || str_contains($email, "`") || str_contains($email, "\\") || str_contains($email, "^") || str_contains($email, "]") || str_contains($email, "}") || str_contains($email, "°") || str_contains($email, "+") || str_contains($email, "¨") || str_contains($email, "£") || str_contains($email, "¤") || str_contains($email, "µ") || str_contains($email, "%") || str_contains($email, "§") || str_contains($email, "/") || str_contains($email, "?") || str_contains($email, "<") || str_contains($email, ">") || str_contains($email, "²"))) {
                echo "<script>alert('Des champs contiennent des caractères interdits. Pour rappel, on ne peut utiliser que des lettres, ou des chiffres dans le cas du mail et du mot de passe.')</script>";
            }
            if ($email == "") {
                echo "<script>alert('Veuillez indiquer votre adresse e-mail')</script>";
            }
            else {
    
                $query = mysqli_query($conn, "SELECT * FROM `user` WHERE `email`='$email'");
                $repository = $doctrine->getRepository(User::class);
                $reg_m = $repository->findBy(['email' => $email]);
                $num_row = count($reg_m);
                if ($num_row == 1) {
                    $req0 = $conn->query("SELECT `token` FROM `user` WHERE `email` = '$email'");
                    $row0 = $req0->fetch_assoc();
                    $token = $row0['token'];
                    $req = $conn->query("SELECT * FROM `user` WHERE `token` = '$token'");
                    $row = $req->fetch_assoc();
                    $nom = $row['nom'];
                    $prenom = $row['prenom'];
                    $user_mail = $row['email'];
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
    
                    $mail->Subject    =  'Changement de mot de passe';
                    $mail->MsgHTML("Bonjour,<br>Vous venez de demander un changement de mot de passe. <strong>Si vous n'êtes pas à l'origine de cette demande, merci de ne pas la prendre en compte.</strong><br>S'il s'agit bien de vous, cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :<br><a href='http://localhost:8000/change_forgot?$token'>Modifier votre mot de passe</a><br><br>Toute l'équipe espère que vous vous plairez sur notre site.<br><br><small>En cas de problème de connexion, n'hésitez pas à contacter l'équipe en répondant à ce mail.</small>");
                    $mail->IsHTML(true);
    
                    $fullname = "$prenom"." "."$nom";
                    $mail->AddAddress("$user_mail","$fullname");
    
                    if (!$mail->send()) {
                        echo "<script>alert('Une erreur a eu lieu')</script>";
                    }
                    else {
                        echo "<script>alert('Un mail de réinitialisation de mot de passe vous a été envoyé. Vous pourrez le réinitiliser depuis ce mail.');window.location.href = './';</script>";
                    }
                }
                else {
                    echo "<script>alert('Votre email n\'est pas enregistré')</script>";
                }
            }
        }

        return $this->render('forgot/index.html.twig', [
            'controller_name' => 'ForgotController',
        ]);
    }
}
