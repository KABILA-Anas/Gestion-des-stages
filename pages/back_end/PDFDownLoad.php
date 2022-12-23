<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{
        
        if(!empty($_POST['rapport']) || !empty($_POST['contract']) || !empty($_POST['cv']) )
        {
            if(!empty($_POST['rapport'])){
                
                $filename = basename($_POST['rapport']);
                $filepath = '../uploads/rapport/' . $filename;
            }else if(!empty($_POST['contract'])){
                $filename = basename($_POST['contract']);
                $filepath = '../uploads/Contracts/' . $filename;
            }else if(!empty($_POST['cv'])){
                $filename = basename($_POST['cv']);
                $filepath = '../uploads/cv/' . $filename;
            }
            
            if(!empty($filename) && file_exists($filepath)){
        
                //Define Headers
                header("Cache-Control: public");
                header("Content-Description: FIle Transfer");
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-Type: application/zip");
                header("Content-Transfer-Emcoding: binary");
        
                readfile($filepath);
                exit;
        
            }
            else{
                echo "This File Does not exist.";
            }
            
        }else{
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>