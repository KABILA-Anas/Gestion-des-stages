<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if(!empty($_POST['id_etu_verif']) )
            {
                $id_etu = htmlspecialchars($_POST['id_etu_verif']);
                
                $Smt = $bdd->prepare("UPDATE users u,etudiant e SET u.VERIFIED='1' WHERE u.ID_USER = e.ID_USER AND e.ID_ETU=?");
                $Smt -> execute(array($id_etu));
                
                $Smt->closeCursor();//vider le curseur (free)
                
                header('location:../Verify_Etudiant_Resp.php');
            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>