<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Etudiant" )
        {
            if(!empty($_POST['id_etu']))
            {
                $Etu = $_POST['id_etu'];
                
                /// *** CV add
                echo "<br><br><br><br>hahaha";
                $target_dir = "../uploads/cv/";
                $target_file = $target_dir . basename($_FILES["cv"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $cv = NULL;
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["cv"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["cv"]["size"] > 10000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
                }

                // Allow certain file formats
                if($imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "dotx"
                && $imageFileType != "doc" ) {
                echo "Sorry, only PDF, DOCX, DOC & DOTX files are allowed.";
                $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["cv"]["name"])). " has been uploaded.";
                    $cv = "../uploads/cv/".htmlspecialchars( basename( $_FILES["cv"]["name"]));
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
                }
                
                /// *** Update CV 
                $Smt = $bdd->prepare("UPDATE etudiant SET CV=? WHERE ID_ETU=?");
                $Smt -> execute(array($cv,$Etu)); 
                $Smt->closeCursor();//vider le curseur (free)  

                header('location:../Profile.php');
                
                
            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>