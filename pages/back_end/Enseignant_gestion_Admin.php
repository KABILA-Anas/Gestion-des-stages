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
                $prenom_modif=$_POST['prenom_modif'];
                $cin_modif = $_POST['cin_modif'];
                $dep_modif = $_POST['dep_modif'];
                $email_modif = $_POST['email_modif'];

                $Smt=$bdd->prepare("UPDATE enseignant SET ID_DEPART=?,NOM_ENS=?,PRENOM_ENS=?,CIN_ENS=?,EMAIL_ENS=? WHERE ID_ENS=?");
                $Smt->execute(array($dep_modif , $nom_modif, $prenom_modif ,$cin_modif,$email_modif,$id_modif) );
                

                header('location:../Liste_Enseignants_Admin.php');
               
            }else if(!empty($_POST['nom_add']) && !empty($_POST['prenom_add']) && !empty($_POST['cin_add']) && !empty($_POST['dep_add']) && !empty($_POST['email_add']) && !empty($_POST['form_add'])){
                
                $nom_add = $_POST['nom_add'];
                $prenom_add=$_POST['prenom_add'];
                $cin_add = $_POST['cin_add'];
                $dep_add =$_POST['dep_add'];
                $email_add = $_POST['email_add'];
                $Form_IDS = array_keys($_POST['form_add'] , 'on');

                /// *** Inserer enseignant
                $Smt=$bdd->prepare("INSERT INTO enseignant(ID_DEPART,NOM_ENS,PRENOM_ENS,CIN_ENS,EMAIL_ENS) VALUES(?,?,?,?,?)");
                $Smt->execute(array($dep_add,$nom_add,$prenom_add,$cin_add,$email_add));
                
                /// *** ID de cet enseignant
                $Smt = $bdd->prepare("SELECT max(ID_ENS) as ID_ENS FROM enseignant");
                $Smt -> execute();
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                $Smt->closeCursor();//vider le curseur (free)
                $ID_ENS = $row['ID_ENS'];
                /// *** Inserer dans formations
                foreach($Form_IDS as $Form_ID){
                    
                    $Smt=$bdd->prepare("INSERT INTO enseigner(ID_FORM,ID_ENS) VALUES(?,?)");
                    $Smt->execute(array($Form_ID,$ID_ENS));
                }

               header('location:../Liste_Enseignants_Admin.php');
            }
        }
        else
        {
          header('location:../'.$_SESSION['main_page']);
        }
    }

?>