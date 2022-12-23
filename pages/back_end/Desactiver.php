<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if((!empty($_POST['id_etu_disac'])) || (!empty($_POST['id_etu_ac'])))
            {
                if(!empty($_POST['id_etu_disac']) )
                {
                    $id_etu = $_POST['id_etu_disac'];
                    
                    $Smt = $bdd->prepare("UPDATE users u,etudiant e SET u.ACTIVE='0' WHERE u.ID_USER = e.ID_USER AND e.ID_ETU=?");
                    $Smt -> execute(array($id_etu));
                    
                    $Smt->closeCursor();//vider le curseur (free)
                    
                    header('location:../Liste_Etudiant_Resp.php');
                }else if(!empty($_POST['id_etu_ac'])){
                
                    $id_etu = $_POST['id_etu_ac'];
                    
                    $Smt = $bdd->prepare("UPDATE users u,etudiant e SET u.ACTIVE='1' WHERE u.ID_USER = e.ID_USER AND e.ID_ETU=?");
                    $Smt -> execute(array($id_etu));
                    
                    $Smt->closeCursor();//vider le curseur (free)
                    
                    header('location:../Liste_Etudiant_Resp.php');

                }
            }
            else if((!empty($_POST['id_ens_disac'])) || (!empty($_POST['id_ens_ac'])))
            {
                if(!empty($_POST['id_ens_disac']) )
                {
                    $id_ens = $_POST['id_ens_disac'];
                    
                    $Smt = $bdd->prepare("UPDATE enseignant SET ACTIVE_ENS='0' WHERE ID_ENS=?");
                    $Smt -> execute(array($id_ens));
                    
                    $Smt->closeCursor();//vider le curseur (free)

                   header('location:../Liste_Enseignant_Resp.php');

                }else if(!empty($_POST['id_ens_ac'])){
                
                    $id_ens = $_POST['id_ens_ac'];

                    $Smt = $bdd->prepare("UPDATE enseignant SET ACTIVE_ENS='1' WHERE ID_ENS=?");
                    $Smt -> execute(array($id_ens));
                    
                    $Smt->closeCursor();//vider le curseur (free)
                    header('location:../Liste_Enseignant_Resp.php');

                }
                

            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>