<?php

if(( (isset($_POST['prenom_etu']))  && (isset($_POST['nom_etu'])) && (isset($_POST['day'])) && (isset($_POST['month'])) && (isset($_POST['year'])) && (isset($_POST['cin'])) && (isset($_POST['number'])) && (isset($_POST['adress'])) && (isset($_POST['city'])) && (isset($_POST['cne'])) && (isset($_POST['type'])) &&  (isset($_POST['promo'])) && (isset($_POST['user_mail'])) && (isset($_POST['pass']))  ) ||  (isset($_POST['imageUpload'])) ||  (isset($_POST['cvUpload'])))
{

        require('connexion.php');
        
        $formation=NULL;
        $niveau=NULL;
        $type_form = htmlspecialchars($_POST['type']);
        $nom_etu = htmlspecialchars($_POST['nom_etu']);
        $prenom_etu = htmlspecialchars($_POST['prenom_etu']);
        $user_mail = htmlspecialchars($_POST['user_mail']);
        $password = htmlspecialchars($_POST['pass']);
        $cin = htmlspecialchars($_POST['cin']);
        $cne = htmlspecialchars($_POST['cne']);
        $adress = htmlspecialchars($_POST['adress']);
        $ville =$_POST['city'];
        $numtel = htmlspecialchars($_POST['number']);
        $promotion = htmlspecialchars($_POST['promo']);
        $day = htmlspecialchars($_POST['day']);
        $month = htmlspecialchars($_POST['month']);
        $year = htmlspecialchars($_POST['year']);
        $date_naiss = htmlspecialchars(date('Y-m-d', strtotime( $day.'-'.$month.'-'.$year ) ) );
        $cv=NULL;
        $pdp=NULL;
        
        /// *** 
        switch($type_form)
        {
            case 0:
                
                if(isset($_POST['filière_lst']))
                    $formation=htmlspecialchars($_POST['filière_lst']);
                    $niveau = 0;

                break;
            case 1:
                if(isset($_POST['filière_cyc']) && isset($_POST['niveau_cyc'])){
                    $formation=htmlspecialchars($_POST['filière_cyc']);
                    $niveau =htmlspecialchars($_POST['niveau_cyc']);
                }
                break;
            case 2:
                if(isset($_POST['filière_mst']) && isset($_POST['niveau_mst'])){
                    $formation=htmlspecialchars($_POST['filière_mst']);
                    $niveau =htmlspecialchars($_POST['niveau_mst']);
                }
                break;
        }
        //// ***Insert file
        function Insert_file($folder ,$name)
        {
            
            $target_dir = "../uploads/".$folder."/";
            echo $target_dir;
            $target_file = $target_dir . basename($_FILES[$name]["name"]);
            echo $target_file;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            

            // Check if file already exists
            if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
            }

            // Check file size
            if ($_FILES[$name]["size"] > 50000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "dotx"
            && $imageFileType != "doc" && $imageFileType != "jpg" && $imageFileType != "png" )  {
            echo "Sorry, only PDF, DOCX, DOC,JPG ,PNG & DOTX files are allowed.";
            $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES[$name]["name"])). " has been uploaded.";
                    $file = "../uploads/".$folder."/".htmlspecialchars( basename( $_FILES[$name]["name"]));
                    return $file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    return NULL;
                }
            }
        }

        
        /// ***Insert pdp
        $pdp = Insert_file("pdp","imageUpload");
        echo "<br><br><br>".$pdp."<br><br>";
        /// ***Insert cv
        $cv=Insert_file("cv","cvUpload");
        echo "<br><br><br>".$cv;

        /// ***Insert in Users 
        $Smt = $bdd->prepare("INSERT INTO Users(LOGIN,PASSWORD,PICTURE,ACTIVE,VERIFIED)  VALUES(?,?,?,?,?)");
        $Smt -> execute(array($user_mail,$password,$pdp,'1','0'));
        $Smt->closeCursor();//vider le curseur (free)

        /// *** ID_USER
        $Smt = $bdd->prepare("SELECT max(ID_USER) as ID_USER FROM Users");
        $Smt -> execute();
        $row = $Smt->fetch(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free)
        $id_user = $row['ID_USER'];
        
        // /// ***Insert  eetudiant data in database
        $Smt = $bdd->prepare("INSERT INTO etudiant(ID_FORM,NOM_ETU,PRENOM_ETU,CIN_ETU,CNE,NIVEAU,PROMOTION,DATENAISS_ETU,VILLE_ETU,ADRESSE_ETU,EMAIL_ETU,NUMTEL_ETU,CV,ID_USER)  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $Smt -> execute(array($formation,$nom_etu,$prenom_etu,$cin,$cne,$niveau,$promotion,$date_naiss,$ville,$adress,$user_mail,$numtel,$cv,$id_user));
        $Smt->closeCursor();//vider le curseur (free)

        header('location:../login.php');
        
                
    
}


















?>