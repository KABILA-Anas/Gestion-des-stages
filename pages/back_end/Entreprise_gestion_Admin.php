<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Admin" )
        {
            if(!empty($_POST['id_modif'])) 
            {
                $id_modif = $_POST['id_modif'];
                $nom_modif = $_POST['nom_modif'];
                $email_modif=$_POST['email_modif'];
                $website_modif = $_POST['website_modif'];

                $Smt=$bdd->prepare("UPDATE entreprise SET NOM_ENTREP=?,EMAIL_ENTREP=?,WEBSITE=? WHERE ID_ENTREP=?");
                $Smt->execute(array($nom_modif , $email_modif, $website_modif ,$id_modif) );
                

               header('location:../Liste_Entreprises_Admin.php');
               
            }else if(!empty($_POST['nom_add']) && !empty($_POST['email_add']) && !empty($_POST['website_add']) ){
                $nom_add = $_POST['nom_add'];
                $email_add=$_POST['email_add'];
                $website_add = $_POST['website_add'];

                $Smt=$bdd->prepare("INSERT INTO entreprise(NOM_ENTREP,EMAIL_ENTREP,WEBSITE) VALUES(?,?,?)");
                $Smt->execute(array($nom_add , $email_add, $website_add));
                

               header('location:../Liste_Entreprises_Admin.php');
            }
        }
        else
        {
          header('location:../'.$_SESSION['main_page']);
        }
    }

?>