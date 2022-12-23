<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{
        if( $_SESSION['user_type'] == "Admin" )
        {
            if(!empty($_POST['resp_modif']) && !empty($_POST['form_modif']) ) 
            {
                $resp_modif = $_POST['resp_modif'];
                $id_form = $_POST['form_modif'] ;

                $Smt=$bdd->prepare("UPDATE formation SET ID_ENS=? WHERE ID_FORM=?");
                $Smt->execute(array($resp_modif,$id_form) );
                

                header('location:../Liste_Formations_Admin.php');
               
            }else if( !empty($_POST['nom_add']) && !empty($_POST['abv_add']) && isset($_POST['type_add']) && !empty($_POST['resp_add']) ){
                
                $nom_add = $_POST['nom_add'];
                $abv_add=$_POST['abv_add'];
                $type_add = $_POST['type_add'];
                $resp_add = $_POST['resp_add'];

                

                /// *** Email resp
                $Smt=$bdd->prepare("SELECT EMAIL_ENS,CIN_ENS,NOM_ENS FROM enseignant WHERE ID_ENS=?");
                $Smt->execute(array($resp_add));
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                $email_resp = $row['EMAIL_ENS'];
                $pass =$row['NOM_ENS'].''.$row['CIN_ENS'];

                /// *** Create User account
                $Smt=$bdd->prepare("INSERT INTO Users(LOGIN,PASSWORD,ACTIVE,VERIFIED) VALUES(?,?,?,?)");
                $Smt->execute(array($email_resp,$pass,1,1));
                
                /// *** 
                $Smt=$bdd->prepare("SELECT max(ID_USER) as ID_USER from Users");
                $Smt->execute();
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                $id_user = $row['ID_USER'];
                
                /// *** 
                $Smt=$bdd->prepare("INSERT INTO formation(ID_ENS,FULL_NAME,FILIERE,TYPE_FORM,ID_USER) VALUES(?,?,?,?,?)");
                $Smt->execute(array($resp_add,$nom_add , $abv_add ,$type_add,$id_user));
                
                
               header('location:../Liste_Formations_Admin.php');
            }
        }
        else
        {
          header('location:../'.$_SESSION['main_page']);
        }
    }

?>