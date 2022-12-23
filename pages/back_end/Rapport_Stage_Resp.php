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
                
                /// Insert Rapport
                echo "<br><br><br><br>kakakaka";
                $target_dir = "../uploads/rapport/";
                $target_file = $target_dir . basename($_FILES["rapport"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $rapport = NULL;
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["rapport"]["tmp_name"]);
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
                if ($_FILES["rapport"]["size"] > 10000000) {
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
                if (move_uploaded_file($_FILES["rapport"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["rapport"]["name"])). " has been uploaded.";
                    $rapport = "../uploads/rapport/".htmlspecialchars( basename( $_FILES["rapport"]["name"]));
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
                }

                /// *** Insert rapport
                if($rapport)
                {
                    /// *** Inserer rapport
                    $Smt = $bdd->prepare("INSERT INTO rapport(fichier,id_stage) values(?,?)");
                    $Smt -> execute(array($rapport,$id_stage));
                    $Smt->closeCursor();//vider le curseur (free)

                    /// *** Finir le stage
                    $Smt=$bdd->prepare("UPDATE stage SET STATUSTG=? WHERE ID_STAGE=? ");
                    $Smt->execute(array('2',$id_stage));  
                    $Smt->closeCursor();//vider le curseur (free)
                    /// *** OFFRE ET ETUDIANT DE CET STAGE
                    $Smt=$bdd->prepare("SELECT ID_OFFRE,ID_ETU from stage WHERE ID_STAGE=?");
                    $Smt->execute(array($id_stage));
                    $row=$Smt->fetch(PDO::FETCH_ASSOC);
                    $Smt->closeCursor();//vider le curseur (free)
                    $id_etu = $row['ID_ETU'];
                    $id_offre=$row['ID_OFFRE'];

                    /// ***
                    $Smt=$bdd->prepare("UPDATE postuler SET STATU=? WHERE ID_ETU=? AND ID_OFFRE=? ");
                    $Smt->execute(array('Fini',$id_etu,$id_offre));  
                    $Smt->closeCursor();//vider le curseur (free)


                }
                ///Insert motcles
                if( !empty($_POST['motscle']) )
                 {

                    /// ***ID of Rapport  
                    $Smt = $bdd->prepare("SELECT ID_RAPP FROM RAPPORT WHERE ID_STAGE=?");
                    $Smt -> execute(array($id_stage));
                    $row = $Smt->fetch(PDO::FETCH_ASSOC);
                    $ID_RAPPORT = $row['ID_RAPP'];
                    $Smt->closeCursor();//vider le curseur (free)

                    $MotsCles = $_POST['motscle'];
                        
                    foreach($MotsCles as $MotCle)
                    {
                        if(!empty($MotCle))
                        {
                            /// *** test si mot cle existe
                            $Smt = $bdd->prepare("SELECT ID_MOTCLE FROM motcle WHERE MOT=?");
                            $Smt -> execute(array($MotCle));
                            $row = $Smt->fetch(PDO::FETCH_ASSOC);
                            $ID_MOT = $row['ID_MOTCLE'];

                            if($ID_MOT == NULL){
                                /// *** mot cle
                                $Smt = $bdd->prepare("INSERT INTO motcle(MOT) values(?)");
                                $Smt -> execute(array($MotCle));
                                
                                /// *** 
                                $Smt = $bdd->prepare("SELECT max(ID_MOTCLE) as ID_MOTCLE FROM motcle");
                                $Smt -> execute();
                                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                                $ID_MOT = $row['ID_MOTCLE'];
                            }
                            /// *** 
                            $Smt = $bdd->prepare("INSERT INTO referencer(ID_RAPP,ID_MOTCLE) values(?,?)");
                            $Smt -> execute(array($ID_RAPPORT,$ID_MOT));
                        }
                        

            
                    }
                  }              
                  header('location:../Liste_Etudiant_Resp.php');
            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>