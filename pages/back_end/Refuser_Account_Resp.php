<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else
    {

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if(!empty($_GET['id_etu_refus']) )
            {
                $id_etu = htmlspecialchars($_GET['id_etu_refus']);
                $Smt = $bdd->prepare("SELECT ID_USER FROM etudiant WHERE ID_ETU=?");
                $Smt -> execute(array($id_etu));
                $id_user_etu = $Smt->fetch();
                $Smt->closeCursor();//vider le curseur (free)

                $Smt = $bdd->prepare("DELETE FROM etudiant WHERE ID_ETU=?");
                $Smt -> execute(array($id_etu));
                $Smt->closeCursor();//vider le curseur (free)

                $Smt = $bdd->prepare("DELETE FROM users WHERE ID_USER=?");
                $Smt -> execute(array($id_user_etu[0]));
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