<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if(((!empty($_POST['jury_add']) || !empty($_POST['jury_supp'])) && (!empty($_POST['id_stage'])) ))
            {
                
                 
                $id_stage = $_POST['id_stage'];

                if(!empty($_POST['jury_supp'])){
                    
                    $JURY_ID = $_POST['jury_supp'] ;  
                    $Smt = $bdd->prepare("DELETE FROM juri WHERE ID_ENS=? AND ID_STAGE =? ");
                    $Smt -> execute(array($JURY_ID,$id_stage));
                    

                
                }else if(!empty($_POST['jury_add'])){
                    ///Indexes of array represent the IDS of ENSEIGNANTS,"on" means est checkée
                    $ENS_IDS = array_keys($_POST['jury_add'] , 'on');
                   
                    foreach($ENS_IDS as $ENS_ID){
                        
                        $Smt = $bdd->prepare("SELECT ACTIVE_ENS FROM enseignant WHERE ID_ENS=? ");
                        $Smt -> execute(array($ENS_ID));
                        $row = $Smt->fetch(PDO::FETCH_ASSOC);
                        $Smt->closeCursor();//vider le curseur (free)
                        
                        if($row['ACTIVE_ENS'] == 1)
                        {
                            
                            $Smt = $bdd->prepare("INSERT INTO juri(ID_ENS,ID_STAGE) VALUES(?,?) ");
                            $Smt -> execute(array($ENS_ID,$id_stage));
                            $Smt->closeCursor();//vider le curseur (free)
                        }
                        
                        
                    }
                    
                }
                $Smt->closeCursor();//vider le curseur (free)
                
                header('location:../Jury_Resp.php?id_stage='.$id_stage);  
            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>