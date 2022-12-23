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


                $Smt=$bdd->prepare("UPDATE departement SET NOM_DEPART=? WHERE ID_DEPART=?");
                $Smt->execute(array($nom_modif , $id_modif));
                

                header('location:../Liste_Departement_Admin.php');
               
            }else if(!empty($_POST['nom_add'])){
                
                $nom_add = $_POST['nom_add'];

                $Smt=$bdd->prepare("INSERT INTO departement (NOM_DEPART) VALUES(?)");
                $Smt->execute(array($nom_add));
                

               header('location:../Liste_Departement_Admin.php');
            }
        }
        else
        {
          header('location:../'.$_SESSION['main_page']);
        }
    }

?>