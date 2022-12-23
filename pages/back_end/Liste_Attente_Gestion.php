<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if(!empty($_POST['id_offre']) && (!empty($_POST['etu_add']) || !empty($_POST['etu_supp'])) )
            {
                /// ***
                $id_offre = $_POST['id_offre'];
               
                
                if(!empty($_POST['etu_add']))
                {
                    $id_etu = $_POST['etu_add'];

                    /// *** Insertion en liste d'attante
                    $Smt = $bdd->prepare("INSERT INTO attente(ID_ETU,ID_OFFRE) VALUES(?,?) ");
                    $Smt->execute(array($id_etu,$id_offre));
                    $Smt->closeCursor();//vider le curseur (free)

                    /// *** Update statu to Retenue en attente
                    $timestamp = time()+60*60;
                    $curdate = date("Y-m-d h:i:s",$timestamp);
                    
                    $Smt = $bdd->prepare("UPDATE postuler SET STATU=?,DATEREPONS=? WHERE ID_ETU=? AND ID_OFFRE=?");
                    $Smt->execute(array('Retenue en attente',$curdate,$id_etu,$id_offre));
                    $Smt->closeCursor();//vider le curseur (free)
                }

                if(!empty($_POST['etu_supp']))
                {
                    $id_etu = $_POST['etu_supp'];

                    /// *** Suppression de la liste d'attante
                    $Smt = $bdd->prepare("DELETE FROM attente WHERE ID_ETU=? AND ID_OFFRE=? ");
                    $Smt->execute(array($id_etu,$id_offre));
                    $Smt->closeCursor();//vider le curseur (free)

                    /// *** Update statu to Postulée
                    $Smt = $bdd->prepare("UPDATE postuler SET STATU=?,DATEREPONS=? WHERE ID_ETU=? AND ID_OFFRE=?");
                    $Smt->execute(array('Postulée',NULL,$id_etu,$id_offre));
                    $Smt->closeCursor();//vider le curseur (free)
                }

                header('location:../Liste_Attente_Resp.php?id_offre='.$id_offre);          
            }else{
                echo "NO NO ENTER HH ";            
            }
            
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>