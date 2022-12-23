<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if(isset($_POST['id_stage']))
            {
                $id_stage =$_POST['id_stage'];   
                
                /// *** Cancel stage
                $Smt=$bdd->prepare("UPDATE stage SET STATUSTG=? WHERE ID_STAGE=? ");
                $Smt->execute(array('0',$id_stage));  
                $Smt->closeCursor();//vider le curseur (free)

                /// *** OFFRE ET ETUDIANT DE CET STAGE
                $Smt=$bdd->prepare("SELECT ID_OFFRE,ID_ETU from stage WHERE ID_STAGE=?");
                $Smt->execute(array($id_stage));
                $row=$Smt->fetch(PDO::FETCH_ASSOC);
                $Smt->closeCursor();//vider le curseur (free)
                $id_etu = $row['ID_ETU'];
                $id_offre=$row['ID_OFFRE'];

                ///*** Reouvre la postulation
                $Smt=$bdd->prepare("UPDATE postuler SET STATU=? WHERE ID_ETU=? AND ID_OFFRE=? ");
                $Smt->execute(array('Annulée',$id_etu,$id_offre));  
                $Smt->closeCursor();//vider le curseur (free)
                
                header('location:'.$_SESSION['Last_visite']);

            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>