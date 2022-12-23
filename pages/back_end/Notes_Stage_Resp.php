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
                
                /// Etudiant
                $Smt = $bdd->prepare("SELECT ID_ETU FROM STAGE WHERE ID_STAGE=?");
                $Smt -> execute(array($id_stage));
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                $id_etu = $row['ID_ETU'];
                

                if(!empty($_POST['notes_jury']) || !empty($_POST['note_encad']) || !empty($_POST['note_entrep']) )
                {
                    /// ***Insertion des notes des jury
                    if(!empty($_POST['notes_jury']) && !empty($_POST['jury_array']) )
                    {
          
                      $notes_jury = $_POST['notes_jury'];
                      $jury_array = unserialize($_POST['jury_array']);
                      var_dump($jury_array);
                      $i = 0;
                      
                      foreach($jury_array as $Jury){
                        
                          $Smt = $bdd->prepare("UPDATE juri SET NOTE =? WHERE ID_ENS=? AND ID_STAGE=? ");
                          $Smt -> execute(array($notes_jury[$i],$Jury['ID_ENS'],$id_stage));
                          $i++;
                      }
                    }
          
                  if(isset($_POST['note_encad'])){
                    
                    $Smt = $bdd->prepare("UPDATE stage SET NOTENCAD =? WHERE ID_STAGE=? ");
                    $Smt -> execute(array($_POST['note_encad'],$id_stage));
                  }
                  if(isset($_POST['note_entrep'])){
                    
                    $Smt = $bdd->prepare("UPDATE stage SET NOTENCAD_ENTREP =? WHERE ID_STAGE=? ");
                    $Smt -> execute(array($_POST['note_entrep'],$id_stage));
                  }
                  
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