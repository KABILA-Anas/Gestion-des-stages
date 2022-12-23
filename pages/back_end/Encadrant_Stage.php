<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if(!empty($_POST['id_stage']))
            {
                $id_stage = $_POST['id_stage'];
                
                if(!empty($_POST['encadrant_stage']))
                {
                    $encad = $_POST['encadrant_stage'];
                    $Smt=$bdd->prepare("UPDATE stage SET ID_ENS=? WHERE ID_STAGE=? ");
                    $Smt->execute(array($encad,$id_stage));  
                   
                }
                
               header('location:'.$_SESSION['Last_visite']);
            }
        }
        else
        {
          header('location:../'.$_SESSION['main_page']);
        }
    }

?>