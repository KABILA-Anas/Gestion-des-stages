<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else
    {

        if( $_SESSION['user_type'] == "Etudiant" )
        {
            if( !empty($_POST['id_etu']) && !empty($_POST['numtel_etu']) && !empty($_POST['adresse_etu']) && !empty($_POST['ville_etu']) && !empty($_POST['password']) ) 
            {
                $id_etu = $_POST['id_etu'];
                $numtel_etu = $_POST['numtel_etu'];
                $adresse_etu=$_POST['adresse_etu'];
                $ville_etu = $_POST['ville_etu'];
                $password = $_POST['password'];

                $Smt=$bdd->prepare("UPDATE etudiant SET NUMTEL_ETU=?,ADRESSE_ETU=?,VILLE_ETU=? WHERE ID_ETU=?");
                $Smt->execute(array($numtel_etu , $adresse_etu, $ville_etu ,$id_etu));
                $Smt->closeCursor();//vider le curseur (free) 

                $Smt = $bdd->prepare("SELECT ID_USER FROM etudiant WHERE ID_ETU=?");
                $Smt -> execute(array($id_etu));
                $id_user_etu = $Smt->fetch();
                var_dump($id_user_etu);
                $Smt->closeCursor();//vider le curseur (free)

                $Smt=$bdd->prepare("UPDATE users SET PASSWORD=? WHERE ID_USER=?");
                $Smt->execute(array($password , $id_user_etu[0]));
                $Smt->closeCursor();//vider le curseur (free) 
                

                header('location:../Profile.php');
            }
            else
            {
            header('location:../'.$_SESSION['main_page']);
            }
        }
    }
?>