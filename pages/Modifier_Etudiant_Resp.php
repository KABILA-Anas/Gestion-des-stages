<?php
  ob_start();
  session_start();
  if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location: login.php');
  }
  
    if($_SESSION['user_type'] == "Responsable")
    {
      require('back_end/connexion.php');
      $id_form = $_SESSION['user_id'];
      
    
      if( !empty($_GET['id_etu']) )
      {
       
        // fetch current Etudiant data
        $id_etu = htmlspecialchars($_GET['id_etu']);
        $Smt = $bdd->prepare("SELECT * FROM etudiant WHERE ID_ETU=?");
        $Smt -> execute(array($id_etu));
        $rows = $Smt -> fetch();
        $Smt->closeCursor();//vider le curseur (free)
        
        if($rows['ID_FORM'] != $id_form )
          exit("You're not allowed to access for this student");

        
        // get type Formation
        $Smt = $bdd->prepare("SELECT TYPE_FORM FROM formation WHERE ID_FORM=?");
        $Smt -> execute(array($id_form));
        $formation = $Smt -> fetch();
        $Smt->closeCursor();//vider le curseur (free)

      }

      

      if( !empty($_POST['nom_etu']) )
      { echo "<br><br><br><br>hahaha";
        $target_dir = "uploads/cv/";
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
            $cv = "uploads/cv/".htmlspecialchars( basename( $_FILES["cv"]["name"]));
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        }
       
        $id_etu = htmlspecialchars($_POST['id_etu']);
        $nom_etu = htmlspecialchars($_POST['nom_etu']);
        $prenom_etu = htmlspecialchars($_POST['prenom_etu']);
        $email_etu = htmlspecialchars($_POST['email_etu']);
        $cin_etu = htmlspecialchars($_POST['cin_etu']);
        $cne = htmlspecialchars($_POST['cne']);
        $adresse_etu = htmlspecialchars($_POST['adresse_etu']);
        $numtel_etu = htmlspecialchars($_POST['numtel_etu']);
        $niveau = htmlspecialchars($_POST['niveau']);
        $promotion = htmlspecialchars($_POST['promotion']);
        $datenaiss_etu = htmlspecialchars($_POST['datenaiss_etu']);
        

        $Smt = $bdd->prepare("UPDATE etudiant SET NOM_ETU=? , PRENOM_ETU=? , EMAIL_ETU=? , CIN_ETU=? , CNE=? , ADRESSE_ETU=? ,
        NUMTEL_ETU=? , NIVEAU=? , PROMOTION=? , DATENAISS_ETU=? , CV=? WHERE ID_ETU=?");
        $Smt -> execute(array($nom_etu,$prenom_etu,$email_etu,$cin_etu,$cne,$adresse_etu,$numtel_etu,$niveau,$promotion,$datenaiss_etu,$cv,$id_etu));
        
        header('location:Liste_Etudiant_Resp.php');

      }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Publier_Offre_Resp.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <title>Listes des Etudiants</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed" style="z-index: 9; width: 100%; top: 0;">
        <div class="container-fluid">
          <a class="navbar-brand navt d-lg-block d-lg-none" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ">
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Find_Offre_Resp.php">Find offers</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Historique.php">Historique</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink active_link_color" href="Liste_Etudiant_Resp.php">Etudiants</a><span class="active_link_line"></span>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Liste_Enseignant_Resp.php">Enseignants</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Verify_Etudiant_Resp.php">Verification</a>
                <?php 
                /// ***Nombre de soumissions
                $Smt =$bdd->prepare("SELECT count(u.ID_USER) as Nbr_non_Verif from etudiant e,Users u WHERE u.ID_USER=e.ID_USER AND u.VERIFIED=? AND e.ID_FORM=?  ");
                $Smt->execute(array('0',$id_form));
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                if(!empty($row)){ if($row['Nbr_non_Verif']){ ?><span class="icon-button__badge"><?php $Nb_non_verif =$row['Nbr_non_Verif'];if($Nb_non_verif)print($Nb_non_verif);}} ?></span>
              </li>
            </ul>
            <div class="" style="position: fixed; margin-left: 47%;">
            <a class="navbar-brand navt d-none d-lg-block" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
          </div>
          <div class="navbar-nav ms-auto margin action" style="margin-right:2.5%;">
              
              <img class="profile" onclick="menuToggle()" src="<?php if( !empty($_SESSION['user_pdp']) ) echo $_SESSION['user_pdp']; else echo 'icons/avatar.png'; ?>" alt="">
              
              <div class="menu" style="margin:5px;">
                  <h3><?php if( isset($_SESSION['user_name']) ) echo $_SESSION['user_name']['user_firstname'].'<br>'.$_SESSION['user_name']['user_lastname']; else echo "undefined user"; ?></h3>
              
                  <ul>
                      <li><a href=""><img src="popup/edit.png" alt="">Password</a> </li>
                      <li><a href="back_end/logout.php"><img src="popup/log-out.png" alt="">Log out</a> </li>
                  </ul>
              
               </div>

              </div>
          </div>
        </div>
      </nav>

    <div class="container-fluid ">
      <div class="" style="margin-top: 140px;">
        <?php     
          if(!empty($rows))
          {
          ?>

      <form action="Modifier_Etudiant_Resp.php" method="post" enctype="multipart/form-data" id="form" >
    
         <div class="row" style="background-color: #FFFEFB;">
            <div class="col-md-8 elm pub_col">

                
                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Modifier Etudiant</h4>
                  </div>
                
                

                <div class="row" style="background-color: #FFFEFB; margin-top: 40px;">

                  <div class="col-4 col-md-2 elm " > 
                    <label for="nom"><span>Nom</span></label><br>
                    <label for="entrep" style="margin-top: 55px;"><span>Prenom</span></label><br>
                    <label for="email" style="margin-top: 55px;"><span>Email Etudiant</span></label><br>
                    <label for="cin" style="margin-top: 55px;"><span>Cin</span></label><br>
                    <label for="cne" style="margin-top: 55px;"><span>Cne</span></label><br>
                  </div>

                  <div class="col-8 col-md-4 elm " >
                    <input class="inpstyl" type="text" id="nom" value="<?php echo $rows['NOM_ETU']; ?>" name="nom_etu"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="entrep" value="<?php echo $rows['PRENOM_ETU']; ?>" name="prenom_etu"><br>
                    <input class="inpstyl" type="email" style="margin-top: 45px;" id="email" value="<?php echo $rows['EMAIL_ETU']; ?>" name="email_etu"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="cin" value="<?php echo $rows['CIN_ETU']; ?>" name="cin_etu"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="cne" value="<?php echo $rows['CNE']; ?>" name="cne"><br>
                  </div>

                

                    
                      <div class="col-4 col-md-2 elm "> 
                        <label for="address"><span>Adresse</span></label><br>
                        <label for="tel" style="margin-top: 55px;"><span>Tel</span></label><br>
                        <?php if($formation[0]){ ?><label for="niveau" style="margin-top: 55px;"><span>Niveau</span></label><br><?php } ?>
                        <label for="promo" style="margin-top: 55px;"><span>Promotion</span></label><br>
                        <label for="naiss" style="margin-top: 55px;"><span>Date Naissance</span></label><br>
                      </div>
                      <div class="col-8 col-md-4 elm" >
                        
                        <input class="inpstyl" type="text" id="address" value="<?php echo $rows['ADRESSE_ETU']; ?>" name="adresse_etu"><br>
                        <input class="inpstyl" type="tel" id="tel" style="margin-top: 45px;" value="<?php echo $rows['NUMTEL_ETU']; ?>" name="numtel_etu"><br>
                        

                        

                        <?php
                        if( $formation[0] == 1 )
                        {
                          switch( $rows['NIVEAU'] ){
                            case 1:
                              echo '
                                  <div >
                                    <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" " onchange="ext();" id="type" name="niveau">
                                      <option value=1 selected >1ere</option>
                                      <option value=2>2eme</option>
                                      <option value=3>3eme</option>
                                    </select>
                                  </div>
                                    ';
                              break;
                            case 2:
                              echo '
                                  <div >
                                    <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" " onchange="ext();" id="type" name="niveau">
                                      <option value=1 >1ere</option>
                                      <option value=2 selected >2eme</option>
                                      <option value=3 >3eme</option>
                                    </select>
                                  </div>
                                    ';
                              break;
                            case 3:
                              echo '
                              <div >
                                <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" " onchange="ext();" id="type" name="niveau">
                                  <option value=1 >1ere</option>
                                  <option value=2 >2eme</option>
                                  <option value=3 selected >3eme</option>
                                </select>
                              </div>
                                ';
                              break;
                          
                          }
                          
                        }else if( $formation[0] == 2 ){
                          switch( $rows['NIVEAU'] ){
                            case 1:
                              echo '
                                  <div >
                                    <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" " onchange="ext();" id="type" name="niveau">
                                      <option value=1 selected >1ere</option>
                                      <option value=2>2eme</option>
                                    </select>
                                  </div>
                                    ';
                              break;
                            case 2:
                              echo '
                                  <div >
                                    <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" " onchange="ext();" id="type" name="niveau">
                                      <option value=1 >1ere</option>
                                      <option value=2 selected >2eme</option>
                                    </select>
                                  </div>
                                    ';
                              break;
                          
                          }
                        
                        }
                                  
                                ?>
                        
                        <input class="inpstyl" type="hidden" style="margin-top: 45px;" id="promo" value="<?php echo $rows['ID_ETU']; ?>" name="id_etu"><br>

                        <input class="inpstyl" type="number" style="margin-top: 28px;" id="promo" value="<?php echo $rows['PROMOTION']; ?>" name="promotion"><br>
                        <input class="inpstyl" type="date" style="margin-top: 45px;" id="naiss" value="<?php echo $rows['DATENAISS_ETU']; ?>" name="datenaiss_etu"><br>
                    
                    
                    
                    
                  
                </div>

                
                
              </div>
                          
                    
              <div class="row" style="background-color: #FFFEFB; margin-top: 30px;">
                <div class="col-4 col-md-2 elm ">  
                  <label for="exampleFormControlTextarea5" ><span>CV</span></label>
                </div>
                  <div class="col-8 col-md-8 elm ">  
                    <div class="form-group green-border-focus">
                      
                    <input class="inpstyl" type="file" style="margin-top: 28px;" id="cv" value="<?php echo $rows['CV']; ?>" name="cv">
                    </div>
                  </div>
                  <div class="col-4 col-md-2 elm ">
                    <button class="save" >Enregistrer</button>
                    <button class="cancel">Annuler</button>
                    
                  </div>
              </div>

        
          </div>
        </div>           
      </form>
        <?php
            }else{  
        ?>
      <form action="Modifier_Etudiant_Resp.php" method="post" enctype="multipart/form-data" id="form" >
    
          <div class="row" style="background-color: #FFFEFB;">
            <div class="col-md-8 elm pub_col">

                
                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Modifier Etudiant</h4>
                  </div>
                
                

                <div class="row" style="background-color: #FFFEFB; margin-top: 40px;">

                  <div class="col-4 col-md-2 elm " > 
                    <label for="nom"><span>Nom</span></label><br>
                    <label for="entrep" style="margin-top: 55px;"><span>Prenom</span></label><br>
                    <label for="email" style="margin-top: 55px;"><span>Email Etudiant</span></label><br>
                    <label for="cin" style="margin-top: 55px;"><span>Cin</span></label><br>
                    <label for="cne" style="margin-top: 55px;"><span>Cne</span></label><br>
                  </div>

                  <div class="col-8 col-md-4 elm " >
                    <input class="inpstyl" type="text" id="nom" name="nom_etu"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="entrep" name="prenom_etu"><br>
                    <input class="inpstyl" type="email" style="margin-top: 45px;" id="email" name="email_etu"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="cin" name="cin_etu"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="cne" name="cne"><br>
                  </div>

                

                    
                      <div class="col-4 col-md-2 elm "> 
                        <label for="address"><span>Adresse</span></label><br>
                        <label for="tel" style="margin-top: 55px;"><span>Tel</span></label><br>
                        <label for="niveau" style="margin-top: 55px;"><span>Niveau</span></label><br>
                        <label for="promo" style="margin-top: 55px;"><span>Promotion</span></label><br>
                        <label for="naiss" style="margin-top: 55px;"><span>Date Naissance</span></label><br>
                      </div>
                      <div class="col-8 col-md-2 elm" >
                        
                        <input class="inpstyl" type="text" id="address" name="adresse_etu"><br>
                        <input class="inpstyl" type="tel" id="tel" style="margin-top: 45px;" name="numtel_etu"><br>
                        
                        <?php
                          if( $formation[0] == 1 )
                          {
                        ?>
                        <div >
                              <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" onchange="ext();" id="type" name="niveau">
                                <option value=1 selected >1ere</option>
                                <option value=2>2eme</option>
                                <option value=3>3eme</option>
                              </select>
                        </div>
                        <?php
                          }else if( $formation[0] == 2 ){
                        ?>
                        <div >
                              <select class="form-select" aria-label="Default select example" style="margin-top: 45px; width:10rem;" onchange="ext();" id="type" name="niveau">
                                <option value=1 selected >1ere</option>
                                <option value=2>2eme</option>
                              </select>
                        </div>
                        <?php
                          }
                        ?>

                        

                        <input class="inpstyl" type="number" style="margin-top: 28px;" id="promo" name="promotion"><br>
                        <input class="inpstyl" type="date" style="margin-top: 45px;" id="naiss" name="datenaiss_etu"><br>
                    
                    
                    
                    
                  
                </div>

                
                
              </div>


              <div class="row" style="background-color: #FFFEFB; margin-top: 30px;">
                <div class="col-4 col-md-2 elm ">  
                  <label for="exampleFormControlTextarea5" ><span>CV</span></label>
                </div>
                  <div class="col-8 col-md-8 elm ">  
                    <div class="form-group green-border-focus">
                      
                    <input class="inpstyl" type="file" style="margin-top: 28px;" id="cv" value="<?php echo $rows['CV']; ?>" name="cv">
                    </div>
                  </div>
                  <div class="col-4 col-md-2 elm ">
                    <button class="save" >Enregistrer</button>
                    <button class="cancel">Annuler</button>
                    
                  </div>
              </div>
        
          </div>
        </div> 
       
      </form>
      <?php
          }
      ?>
          </div>
        
  
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous"></script>
          
    
</body>
</html>
<?php
  }
  else
  {
    echo "<h1> ERROR 301:</h1> <p>Unauthorized Access !</p>";
  }

?>
<script>
  
  function changeFunc() 
  {
    document.getElementById("form").submit();
  }
  
  function menuToggle(){
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle('active');
        }

</script>