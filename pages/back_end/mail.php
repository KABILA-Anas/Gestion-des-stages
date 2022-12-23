<?php
    require('connexion.php');
    
    // Subscribe my channel if you are using this code
    // Subscribe my channel if you are using this code
    // Subscribe my channel if you are using this code
    // Subscribe my channel if you are using this code
    // Subscribe my channel if you are using this code

    

    use PHPMailer\PHPMailer\PHPMailer;
    function sendmail(){
        /*
        require('connexion.php');
        
        $id_etu = $_GET['id_etu'];
        var_dump($id_etu);
        $id_offre = $_GET['id_offre'];

        $Smt = $bdd->prepare("SELECT * FROM offre o,postuler p,etudiant e WHERE o.ID_OFFRE = p.ID_OFFRE AND p.ID_ETU = e.ID_ETU AND e.ID_ETU = ? AND o.ID_OFFRE = ?");
        $Smt -> execute(array($id_etu,$id_offre));
        $Data = $Smt->fetch();
        //var_dump($Data);
        $Smt->closeCursor();//vider le curseur (free)*/
        
        
        
        
        
        $name = "FSTAGE";  // Name of your website or yours
        $to = "yassinejrayfy36@gmail.com";  // mail of reciever
        $subject = "Tutorial or any subject";
        $body = "Send Mail Using PHPMailer - MS The Tech Guy";
        $from = "fstage.media@gmail.com";  // you mail
        $password = "rmjqxniouziiythp";  // your mail password
        //$cv = "path";

        // Ignore from here

        require_once "PHPMailer/src/PHPMailer.php";
        require_once "PHPMailer/src/SMTP.php";
        require_once "PHPMailer/src/Exception.php";
        $mail = new PHPMailer();

        // To Here

        //SMTP Settings
        $mail->isSMTP();
        $mail->oauthUserEmail = "[Redacted]@gmail.com";
        $mail->oauthClientId = "[Redacted]";
        $mail->oauthClientSecret = "[Redacted]";
        $mail->oauthRefreshToken = "[Redacted]";
        // $mail->SMTPDebug = 3;  Keep It commented this is used for debugging                          
        $mail->Host = "smtp.gmail.com"; // smtp address of your email
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->Port = 587;  // port
        $mail->SMTPSecure = "tls";  // tls or ssl
        $mail->smtpConnect([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            ]
        ]);

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom($from, $name);
        $mail->addAddress($to); // enter email address whom you want to send
        $mail->Subject = ("$subject");
        $mail->Body = $body;
        //$mail->addAttachment($cv);
        if ($mail->send()) {
            echo "Email is sent!";
        } else {
            echo "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }
    }


        // sendmail();  // call this function when you want to

        //if (isset($_GET['sendmail'])) {
            sendmail();
        //}
        

        
?>