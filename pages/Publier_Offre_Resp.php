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

      $Smt = $bdd->prepare("SELECT TYPE_FORM FROM formation WHERE ID_FORM=?");
		  $Smt -> execute(array($id_form));
      $row = $Smt->fetch();
      $type_form = $row[0];
      $Smt->closeCursor();//vider le curseur (free)

      // cne tester
      $existeCne = true;
      $error_msgCne = NULL;

      if( !empty( $_POST['poste'] ) )
      {
        
        $poste = htmlspecialchars( $_POST['poste'] );
        $nom_entrep = htmlspecialchars( $_POST['nom_entrep'] );
        $email_entrep = htmlspecialchars( $_POST['email_entrep'] );
        $ville = htmlspecialchars( $_POST['ville'] );
        $datefin = htmlspecialchars($_POST['datefin'] );
        $duree = (htmlspecialchars( $_POST['duree'] ) * 30);// * 30 (month -> days)
        $nbrcandidat = htmlspecialchars( $_POST['nbrcandidat'] );
        $source_offre = (htmlspecialchars( $_POST['source_offre'] ));// 0 interne 1 externe
        $descrip = htmlspecialchars( $_POST['descrip'] );
        $datedebut = date('y-m-d');
        

        // s'il s'agit d'une licence
        if( empty($_POST['niveau']) )
        {
          $niveau = 0;
        }else{
          $niveau = htmlspecialchars( $_POST['niveau'] );
        }

        // insertion d'entreprise
        $Smt = $bdd->prepare("SELECT ID_ENTREP,EMAIL_ENTREP FROM entreprise WHERE NOM_ENTREP=? AND VILLE=?");
        $Smt -> execute(array($nom_entrep,$ville));
        $row = $Smt->fetch();
        echo "<br><br><br><br>";
        
        $Email = $row['EMAIL_ENTREP'];
        var_dump($email_entrep);
        echo "<br><br><br><br>";
        var_dump($Email);
        $Smt->closeCursor();//vider le curseur (free)

        // isertion d'entreprise s'elle n'existe pas
        if( empty($row) )
        {
          $Smt = $bdd->prepare("INSERT INTO entreprise (NOM_ENTREP,EMAIL_ENTREP,VILLE) VALUES (?,?,?)");
          $Smt -> execute(array($nom_entrep,$email_entrep,$ville));
          $Smt->closeCursor();//vider le curseur (free)


          $Smt = $bdd->prepare("SELECT ID_ENTREP FROM entreprise WHERE NOM_ENTREP=? AND VILLE=?");
          $Smt -> execute(array($nom_entrep,$ville));
          $row = $Smt->fetch();
          $Smt->closeCursor();//vider le curseur (free)
        }

        

        
        
        

        // select l'id d'entreprise
        $id_entrep = $row[0];

        // update mail entreprise 
        if ( $Email != $email_entrep )
        {
          var_dump($Email);
            $Smt = $bdd->prepare("UPDATE entreprise SET EMAIL_ENTREP=? WHERE ID_ENTREP=?");
            $Smt -> execute(array($email_entrep,$id_entrep));
            $test = $Smt->fetch();
            var_dump($test);
            $Smt->closeCursor();//vider le curseur (free)
        }

        echo "<br><br><br><br>".$source_offre;
        
        // le type de stage (interne/externe)
        if( $source_offre == 0 )
        {
          // tester l'existance d'etudiant 
          $cne = htmlspecialchars( $_POST['cne'] );
          echo "<br><br><br><br>".$cne;
          $Smt = $bdd->prepare("SELECT ID_ETU FROM etudiant WHERE CNE LIKE ? AND NIVEAU=?");
          $Smt -> execute(array($cne,$niveau));
          $row = $Smt->fetch();
          $Smt->closeCursor();//vider le curseur (free)

          if( empty($row) )
          {
            $existeCne = false;
            $error_msgCne = "CNE introuvable dans le niveau saisit !!";
            echo "<br><br><br><br>";
            var_dump($row);
            
          }else{
            $id_private_etu = $row[0];
            // si l'offre

            // publier et postuler
            $Smt = $bdd->prepare("INSERT INTO offre (ID_FORM,ID_ENTREP,STATUOFFRE,NBRCANDIDAT,POSTE,DUREE,DATEDEBUT,DATEFIN,DESCRIP,NIVEAU_OFFRE,SOURCE_OFFRE) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $Smt -> execute(array($id_form,$id_entrep,'Completée',$nbrcandidat,$poste,$duree,$datedebut,$datefin,$descrip,$niveau,$source_offre));
            $Smt->closeCursor();//vider le curseur (free)

            // get offre id
            $Smt = $bdd->prepare("SELECT MAX(ID_OFFRE) FROM offre");
            $Smt -> execute(array());
            $row = $Smt->fetch();
            $Smt->closeCursor();//vider le curseur (free)

            $id_offre = $row[0];
            // postuler a l'offre
            $Smt = $bdd->prepare("INSERT INTO postuler (ID_ETU,ID_OFFRE,STATU,DATEREPONS,DATEPOST) VALUES (?,?,?,?,?)");
            $Smt -> execute(array($id_private_etu,$id_offre,'Retenue',$datedebut,$datedebut));
            $Smt->closeCursor();//vider le curseur (free)

            header('location:Find_Offre_Resp.php');
          }

          

        }else{
          $Smt = $bdd->prepare("INSERT INTO offre (ID_FORM,ID_ENTREP,STATUOFFRE,NBRCANDIDAT,POSTE,DUREE,DATEDEBUT,DATEFIN,DESCRIP,NIVEAU_OFFRE,SOURCE_OFFRE) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
          $Smt -> execute(array($id_form,$id_entrep,'Nouveau',$nbrcandidat,$poste,$duree,$datedebut,$datefin,$descrip,$niveau,$source_offre));
          $Smt->closeCursor();//vider le curseur (free)
          header('location:Find_Offre_Resp.php');
        }

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Publier Offre</title>
</head>
<body onbeforeunload = "reload();">
      
    <nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed" style="z-index: 9; width: 100%; top: 0;">
        <div class="container-fluid">
          <a class="navbar-brand navt d-lg-block d-lg-none" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ">
              <li class="nav-item underline">
                <a class="nav-link navlink active_link_color" href="Find_Offre_Resp.php">Find offers</a><span class="active_link_line"></span>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Historique.php">Historique</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Liste_Etudiant_Resp.php">Etudiants</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Liste_Enseignant_Resp.php">Enseignants</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Verify_Etudiant_Resp.php">Verification</a>
                <?php 
                /// ***Nombre de soumissions
                $Smt =$bdd->prepare("SELECT count(u.ID_USER) as Nbr_non_Verif from etudiant e,users u WHERE u.ID_USER=e.ID_USER AND u.VERIFIED=? AND e.ID_FORM=?  ");
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
        

      <form action="Publier_Offre_Resp.php" method="post" id="form" >
        <?php
        if($existeCne == false)
        {
        ?>
           
        <div class="row" style="background-color: #FFFEFB;">
            <div class="col-md-8 elm pub_col">

                
                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Publier Offre</h4>
                  </div>
                
                
                <div class="row" style="background-color: #FFFEFB; margin-top: 40px;">

                  <div class="col-4 col-md-2 elm " > 
                    <label for="poste"><span>Poste</span></label><br>
                    <label for="entrep" style="margin-top: 55px;"><span>Entreprise</span></label><br>
                    <label for="email" style="margin-top: 55px;"><span>Email Entreprise</span></label><br>
                    <label for="ville" style="margin-top: 55px;"><span>Ville</span></label><br>
                    <label for="dateExp" style="margin-top: 55px;"><span>Date d'expiration</span></label><br>
                  </div>

                  <div class="col-8 col-md-4 elm " >
                    <input class="inpstyl" type="text" id="poste" name="poste" value="<?php echo $poste ?>"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="entrep" name="nom_entrep" value="<?php echo $nom_entrep ?>"><br>
                    <input class="inpstyl" type="email" style="margin-top: 45px;" id="email" name="email_entrep" value="<?php echo $email_entrep ?>"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="ville" name="ville" value="<?php echo $ville ?>"><br>
                    <input class="inpstyl" type="date" style="margin-top: 45px;" id="dateExp" name="datefin" value="<?php echo $datefin ?>"><br>
                  </div>

                

                    
                      <div class="col-4 col-md-2 elm "> 
                        <label for="duree"><span>Durée (mois)</span></label><br>
                        <label for="nbrcandidat" style="margin-top: 55px;"><span>Nombre de Condidats</span></label><br>
                        <label for="niveau" style="margin-top: 28px;"><span>Niveau</span></label><br>
                        <label for="type" style="margin-top: 55px;"><span>Type</span></label><br>
                      </div>
                      <div class="col-8 col-md-2 elm" >
                        <input class="inpstyl" type="number" step="1" min="0" id="duree" name="duree" value="<?php echo $duree ?>"><br>
                        <input class="inpstyl" type="number" step="1" min="1" style="margin-top: 45px;" id="nbrcandidat" name="nbrcandidat" value="<?php echo $nbrcandidat ?>"><br>
                        <?php 
                          if($type_form == 1)
                          {
                            switch( $niveau ){
                              case 1:
                                echo "<div >
                                        <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                                          <option value='1' selected>1ere</option>
                                          <option value='2'>2ème</option>
                                          <option value='3'>3ème</option>
                                        </select><br>
                                      </div>";
                                break;
                              case 2:
                                echo "<div >
                                        <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                                          <option value='1'>1ere</option>
                                          <option value='2' selected>2ème</option>
                                          <option value='3'>3ème</option>
                                        </select><br>
                                      </div>";
                                break;
                              case 3:
                                echo "<div >
                                        <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                                          <option value='1'>1ere</option>
                                          <option value='2'>2ème</option>
                                          <option value='3' selected>3ème</option>
                                        </select><br>
                                      </div>";
                                break;
                            
                            }
                            
                          }
                          else if( $type_form == 2)
                          {
                            switch( $niveau ){
                              case 1:
                                echo "<div >
                                        <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                                          <option value='1' selected>1ere</option>
                                          <option value='2'>2ème</option>
                                        </select><br>
                                      </div>";
                                break;
                              case 2:
                                echo "<div >
                                        <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                                          <option value='1'>1ere</option>
                                          <option value='2' selected>2ème</option>
                                        </select><br>
                                      </div>";
                                break;
                          }
                        }
                          ?>
                        

                        <div >
                          <?php
                            switch( $source_offre ){
                              case 1:
                                echo "<select class='form-select' aria-label='Default select example' style='margin-top: 23px; ' onchange='ext();' id='cne' name='source_offre'>
                                        <option value='1' selected>Interne</option>
                                        <option value='0' >Externe</option>
                                      </select>";
                                break;
                              case 0:
                                echo "<select class='form-select' aria-label='Default select example' style='margin-top: 23px; ' onchange='ext();' id='cne' name='source_offre'>
                                        <option value='1' >Interne</option>
                                        <option value='0' selected>Externe</option>
                                      </select>";
                                break;
                              }
                          ?>
                        
                        
                      </div>
                      
                    
                    
                    
                    
                  
                </div>
                <div id="cne" class="col-8 col-md-2 elm" style="display: none;"><label for="cne" style="margin-top: 247px; margin-left: -20px;"><span>CNE</span></label><input class="inpstyl" type="text" style="margin-left: 10px;" name="cne" id="cne" value="<?php echo $cne ?>"> <?php
                  echo $error_msgCne;
                ?></div>
                
                
              </div>

              <div class="row" style="background-color: #FFFEFB; margin-top: 30px;">
                <div class="col-4 col-md-2 elm ">  
                  <label for="exampleFormControlTextarea5" ><span>Description</span></label>
                </div>
                  <div class="col-8 col-md-8 elm ">  
                    <div class="form-group green-border-focus">
                      
                      <textarea class="form-control inpstyl" id="exampleFormControlTextarea5" rows="8" name="descrip"><?php echo $descrip ?></textarea>
                    </div>
                  </div>
                  <div class="col-4 col-md-2 elm ">
                    <button class="save" >Enregistrer</button>
                    <button class="cancel">Annuler</button>
                    
                  </div>
              </div>
          </div>
          

         

          <div class="modal fade"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
              <div class="modal-content" >
                <div class="modal-header">
                  <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Enseignants</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 300px;">
                  <table class="hovtr">
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  </td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                  </table>
              </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" >Enregistrer</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <?php
        }else{
          // situation nominale
        ?>
         
         <div class="row" style="background-color: #FFFEFB;">
            <div class="col-md-8 elm pub_col">

                
                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Publier Offre</h4>
                  </div>
                
                
                <div class="row" style="background-color: #FFFEFB; margin-top: 40px;">

                  <div class="col-4 col-md-2 elm " > 
                    <label for="poste"><span>Poste</span></label><br>
                    <label for="entrep" style="margin-top: 55px;"><span>Entreprise</span></label><br>
                    <label for="email" style="margin-top: 55px;"><span>Email Entreprise</span></label><br>
                    <label for="ville" style="margin-top: 55px;"><span>Ville</span></label><br>
                    <label for="dateExp" style="margin-top: 55px;"><span>Date d'expiration</span></label><br>
                  </div>

                  <div class="col-8 col-md-4 elm " >
                    <input class="inpstyl" type="text" id="poste" name="poste"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="entrep" name="nom_entrep"><br>
                    <input class="inpstyl" type="email" style="margin-top: 45px;" id="email" name="email_entrep"><br>
                    <input class="inpstyl" type="text" style="margin-top: 45px;" id="ville" name="ville"><br>
                    <input class="inpstyl" type="date" style="margin-top: 45px;" id="dateExp" name="datefin"><br>
                  </div>

                

                    
                      <div class="col-4 col-md-2 elm "> 
                        <label for="duree"><span>Durée (mois)</span></label><br>
                        <label for="nbrcandidat" style="margin-top: 55px;"><span>Nombre de Condidats</span></label><br>
                        <?php
                          if($type_form != 0)
                          {
                        ?>
                        <label for="niveau" style="margin-top: 28px;"><span>Niveau</span></label><br>
                        <?php 
                        }
                        ?>

                        <label for="type" style="margin-top: 55px;"><span>Type</span></label><br>
                      </div>
                      <div class="col-8 col-md-2 elm" >
                        <input class="inpstyl" type="number" step="1" min="0" id="duree" name="duree"><br>
                        <input class="inpstyl" type="number" step="1" min="1" style="margin-top: 45px;" id="nbrcandidat" name="nbrcandidat"><br>
                        <?php 
                          if($type_form == 1)
                          {
                            echo "<div >
                            <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                              <option value='1'>1ere</option>
                              <option value='2'>2ème</option>
                              <option value='3'>3ème</option>
                            </select><br>
                          </div>";
                          }
                          else if( $type_form == 2)
                          {
                            echo "<div >
                            <select class='form-select' aria-label='Default select example' style='margin-top: 40px;' id='niveau' name='niveau'>
                              <option value='1'>1ere</option>
                              <option value='2'>2ème</option>
                            </select><br>
                          </div>";
                          }
                          ?>
                        
                        <?php 
                          if($type_form == 0)
                          {
                        ?>
                        <div >
                        <select class="form-select" aria-label="Default select example" style="margin-top: 70px; " id="type" name='source_offre'>
                          <option value="1">Interne</option>
                          <option value="0" >Externe</option>
                        </select>
                        
                      </div>
                      
                      <?php 
                          }
                          else{
                        ?>
                    <div >
                        <select class="form-select" aria-label="Default select example" style="margin-top: 23px; " id="type" name='source_offre'>
                          <option value="1">Interne</option>
                          <option value="0" >Externe</option>
                        </select>
                        
                      </div>
                    <?php
                          }?>
                    
                  
                </div>
                <div id="cne" class="col-8 col-md-2 elm" style="display: none;"><label for="" style="margin-top: <?php if($type_form == 0) echo "200px"; else echo "247px";?>;  margin-left: -20px;"><span>CNE</span></label><input class="inpstyl" type="text" style="margin-left: 10px;" name="cne"></div>
                
                
              </div>

              <div class="row" style="background-color: #FFFEFB; margin-top: 30px;">
                <div class="col-4 col-md-2 elm ">  
                  <label for="exampleFormControlTextarea5" ><span>Description</span></label>
                </div>
                  <div class="col-8 col-md-8 elm ">  
                    <div class="form-group green-border-focus">
                      
                      <textarea class="form-control inpstyl" id="exampleFormControlTextarea5" rows="8" name="descrip"></textarea>
                    </div>
                  </div>
                  <div class="col-4 col-md-2 elm ">
                    <button class="save" >Enregistrer</button>
                    <button class="cancel">Annuler</button>
                    
                  </div>
              </div>
          </div>
          

         

          <div class="modal fade"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
              <div class="modal-content" >
                <div class="modal-header">
                  <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Enseignants</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 300px;">
                  <table class="hovtr">
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  </td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                    <tr style="height: 50px;">
                      <td>test</td>
                      <td>test</td>
                      <td style="text-align: end;"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                    </tr>
                  </table>
              </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" >Enregistrer</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php
        }
        ?>

        

      </form>
        </div></div>
          
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
      
      
      </script>
      <script>
        // function ext(){
        //     const select = document.getElementById("type");
        //     const elem = document.getElementById("cne");
        //     const candidat = document.getElementById("nbrcandidat");
            
        //     //elem.classList.toggle("cne");
        //     console.log(select.value);
        //     if(select.value == '1')
        //     {
        //       elem.style.display = "none";
        //       candidat.value = '1';
        //       //candidat.disabled = true;
        //     } else if(select.value == '0'){
        //       elem.style.display = "block";
        //       candidat.value = '';
        //       //candidat.disabled = false;
        //     }
        // }


            $('#type').on('load change', function () {
              var cne =  $('#cne');
              var candidat = $('#nbrcandidat');
              console.log(candidat);

              if(this.value == '1')
              {
                cne.hide();
                candidat.val('');
                candidat.prop( "disabled", false );
              }
              else if(this.value == '0')
              {
                cne.show();
                candidat.val('1');
                candidat.prop( "disabled", true );
              }
        });

        function menuToggle(){
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle('active');
        }

        
      </script>
    
</body>
</html>
<?php
  }
  else
  {
    echo "<h1> ERROR 301:</h1> <p>Unauthorized Access !</p>";
  }

?>
