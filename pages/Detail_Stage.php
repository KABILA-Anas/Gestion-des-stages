<?php
  ob_start();
  session_start();
  if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location: login.php');
  }
  
    
      if(!empty($_GET['id_stage']))
      {
      
        require('back_end/connexion.php');
        $id_form = $_SESSION['user_id'];
        $id_stage=$_GET['id_stage'];
        /// *** formation de stage
        $Smt=$bdd->prepare("SELECT ID_FORM,STATUSTG from etudiant e,stage s WHERE s.ID_ETU=e.ID_ETU AND  s.ID_STAGE=?");
        $Smt->execute(array($id_stage));
        $row =$Smt->fetch(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free)
        $form_stage=$row['ID_FORM'];
        $stau_stage=$row['STATUSTG'];
        
        if($_SESSION['user_type'] == "Etudiant"){
          //*** formation de etudiant user
          $Smt=$bdd->prepare("SELECT ID_FORM from etudiant WHERE ID_ETU=?");
          $Smt->execute(array($_SESSION['user_id']));
          $row =$Smt->fetch(PDO::FETCH_ASSOC);
          $Smt->closeCursor();//vider le curseur (free)
          $id_form=$row['ID_FORM'];
        }
        /// *** Test d'acces
        if($id_form != $form_stage )
            exit("You're not allowed to access for this page");
        if($stau_stage != 2)
          exit("Stage n'est pas encore fini ");
        /// *** Detail de stage
        /// *** Offre et entreprise
        $Smt=$bdd->prepare("SELECT POSTE,NOM_ENTREP,DUREE,DESCRIP from entreprise e,offre o,stage s WHERE e.ID_ENTREP=o.ID_ENTREP AND s.ID_OFFRE=o.ID_OFFRE AND s.ID_STAGE=? ");
        $Smt->execute(array($id_stage));
        $row1 =$Smt->fetch(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free)

        /// *** rapport
        $Smt=$bdd->prepare("SELECT FICHIER from rapport r,stage s WHERE r.ID_STAGE=s.ID_STAGE AND s.ID_STAGE=? ");
        $Smt->execute(array($id_stage));
        $row2 =$Smt->fetch(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free)

        /// *** Encadrants
        $Smt=$bdd->prepare("SELECT e.NOM_ENS,e.PRENOM_ENS,s.NOTENCAD_ENTREP,s.NOTENCAD  from stage s,enseignant e WHERE s.ID_ENS=e.ID_ENS AND s.ID_STAGE=?");
        $Smt->execute(array($id_stage));
        $row3 =$Smt->fetch(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free)

        /// *** Jury
        $Smt=$bdd->prepare("SELECT e.NOM_ENS,e.PRENOM_ENS,j.NOTE from enseignant e,juri j WHERE j.ID_ENS=e.ID_ENS AND j.ID_STAGE=? ");
        $Smt->execute(array($id_stage));
        $rows4 =$Smt->fetchAll(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free)


      
    
  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nextab.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <title>Historique</title>
</head>
<body>
     
    <nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed" style="z-index: 9; width: 100%; top: 0;background: #F3F5F8 !important;">
        <div class="container-fluid">
          <a class="navbar-brand navt d-lg-block d-lg-none" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
        
             <?php 
                    if($_SESSION['user_type'] == "Responsable")
                    {
             ?>  
              <ul class="navbar-nav ">
                <li class="nav-item underline">
                  <a class="nav-link navlink " href="Find_Offre_Resp.php">Find offers</a>
                </li>
                <li class="nav-item underline">
                  <a class="nav-link navlink active_link_color" href="Historique.php">Historique</a><span class="active_link_line"></span>
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
              
              <?php
              if(isset($_SESSION['pdp']) && !empty($_SESSION['pdp'])){  ?>
                <img class="profile" onclick="menuToggle()" src="<?php print($_SESSION['pdp']);?>" alt="">
              <?php }else{ ?>
                <img class="profile" onclick="menuToggle()" src="<?php if( !empty($_SESSION['user_pdp']) ) echo $_SESSION['user_pdp']; else echo 'icons/avatar.png'; ?>" alt=""><?php } ?>
              <div class="menu" style="margin:5px;">
                  <h3><?php if( isset($_SESSION['user_name']) ) echo $_SESSION['user_name']['user_firstname'].'<br>'.$_SESSION['user_name']['user_lastname']; else echo "undefined user"; ?></h3>
              
                  <ul>
                      <li><a href=""><img src="popup/edit.png" alt="">Password</a></li>
                      <li><a href="back_end/logout.php"><img src="popup/log-out.png" alt="">Log out</a> </li>
                  </ul>
              
               </div>

              </div>
              <?php 
                    }else if($_SESSION['user_type'] == "Etudiant")
                    {
                      $Etu=$_SESSION['user_id'];
                      /// ***Nombre de soumissions
                      $Smt =$bdd->prepare("SELECT count(e.ID_ETU) as Nbr_soums FROM etudiant e,postuler p WHERE e.ID_ETU = p.ID_ETU AND e.ID_ETU=? AND p.STATU=? ");
                      $Smt->execute(array($Etu,'Retenue'));
                      $row = $Smt->fetch(PDO::FETCH_ASSOC);
              ?>   
              <ul class="navbar-nav ">  
                <li class="nav-item underline">
                  <a class="nav-link navlink " href="Find_Offre_Etu.php">Find offers</a>
                </li>
                <li class="nav-item underline">
                  <a class="nav-link navlink active_link_color" href="Historique.php">Historique</a><span class="active_link_line"></span>
                </li>
                <li class="nav-item underline">
                  <a class="nav-link navlink " href="Soumissions_Etu.php">Soumissions</a>
                  <?php if(!empty($row)){ if($row['Nbr_soums']){ ?><span class="icon-button__badge"><?php $Nb_rtn =$row['Nbr_soums'];if($Nb_rtn)print($Nb_rtn);}} ?></span>
                </li>
                <li class="nav-item underline">
                  <a class="nav-link navlink" href="MeStages_Etu.php">Mes Stages</a>
                </li>    
              </ul>
            
            <div class="" style="position: fixed; margin-left: 47%;">
                  <a class="navbar-brand navt d-none d-lg-block" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
            </div>
            <div class="navbar-nav ms-auto margin action" style="margin-right:2.5%;">
              
              <?php
              if(isset($_SESSION['pdp']) && !empty($_SESSION['pdp'])){  ?>
                <img class="profile" onclick="menuToggle()" src="<?php print($_SESSION['pdp']);?>" alt="">
              <?php }else{ ?>
                <img class="profile" onclick="menuToggle()" src="<?php if( !empty($_SESSION['user_pdp']) ) echo $_SESSION['user_pdp']; else echo 'icons/avatar.png'; ?>" alt=""><?php } ?>
              <div class="menu" style="margin:5px;">
                  <h3><?php if( isset($_SESSION['user_name']) ) echo $_SESSION['user_name']['user_firstname'].'<br>'.$_SESSION['user_name']['user_lastname']; else echo "undefined user"; ?></h3>
              
                  <ul>
                      <li><a href="Profile.php"><img src="popup/user.png" alt="">My profile</a></li>
                      <li><a href="back_end/logout.php"><img src="popup/log-out.png" alt="">Log out</a> </li>
                  </ul>
              
               </div>

              </div>
          </div>
          <?php }?>
        </div>
      </nav>

    <div class="container-fluid contpad">
      <div class="" style="margin-top: 150px;">
        


        
         


          <div class="row row1" style="display: flex !important; justify-content: center !important; ">

           

           
            <div class="col-12 col-md-7 fircol " 
                      >

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Informations de Stage</h4> 
                       
                        
                  </div><br>
                  <div style="display: flex; justify-content: space-between;">
                    <label for="" ><span>Poste :</span></label>
                  <input class="inpsty1" type="text"  value="<?php print($row1['POSTE']);?>" disabled>
                  </div><br>
                  <div style="display: flex; justify-content: space-between;">
                    <label for="" ><span>Entreprise :</span></label>
                  <input class="inpsty1" type="text" value="<?php print($row1['NOM_ENTREP']);?>" disabled>
                  </div><br>
                  <div style="display: flex; justify-content: space-between;">
                    <label for="" ><span>Dureé :</span></label>
                  <input class="inpsty1" type="text" value="<?php print($row1['DUREE']/30);?> mois" disabled>
                  </div><br><br>

                  <div style="display: flex; justify-content: space-between;">
                    <label for="" style="margin-top: 8px;"><span>Rapport :</span></label>
                  <div class="links">
                        <form action="back_end/PDFDownLoad.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="rapport" value="<?php print($row2['FICHIER']);?>">
                            <button type="submit" style="border:none;background:none;"><a style="color:white !important;"><img src="icons/download.png" alt="">Télécharger</a></button>
                            
                        </form>
                   
                  </div>
                  </div><br><br>
                  
                  
                  <div class="tableHead" style="margin-bottom: 30px;">
                    <h4>Notes</h4> 
                    
              </div><br>
              <div style="text-align: center;"><label for="" class="nt"><span style="font-weight: 500 !important;">Encadrants :</span></label></div><br>

              <div style="display: flex; justify-content: space-evenly;">
                  <?php if($row3['NOM_ENS']){ ?>
                  <div style="display: flex; justify-content: space-between; width: 30% !important;">
                    <label for="" ><span><?php print($row3['NOM_ENS']); ?> <?php print($row3['PRENOM_ENS']); ?> :</span></label>
                    <input class="inpsty2" type="text" value="<?php print($row3['NOTENCAD']);?>" disabled>
                  </div>
                  <?php } ?>

                  <div style="display: flex; justify-content: space-between; width: 30%;">
                    <label for="" ><span>Entreprise :</span></label>
                  <input class="inpsty2" type="text" value="<?php print($row3['NOTENCAD_ENTREP']);?>" disabled>
                  </div>

              </div><br><br>

              <?php if(!empty($rows4)){ ?>
              <div style="text-align: center; "><label for="" class="nt"><span style="font-weight: 500 !important;">Jury :</span></label></div><br>
              <?php foreach($rows4 as $row4): ?>
              <div style="display: flex; justify-content: space-evenly;">
                  <div style="display: flex; justify-content: space-between; width: 30% !important;">
                    <label for="" ><span><?php print($row4['NOM_ENS']); ?> <?php print($row4['PRENOM_ENS']); ?> :</span></label>
                    <input class="inpsty2" type="text" value="<?php print($row4['NOTE']); ?>" disabled>
                  </div>
              </div><br>
              <?php endforeach;} ?>

              </div>

              <div class="col-12 col-md-5 secol" 
                      >

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Déscription</h4>    
                  </div>
                  <div style="font-weight: 500; font-size: 18px;">
                    <p style="white-space: pre-line">
                      <?php print($row1['DESCRIP']); ?>
                    </p>
                  </div>

                
              
            </div>
          </div>


        


         



         
          

          
          



        </div>
        </div>
          
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
      
      
      </script>
      <script>
        function menuToggle(){
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle('active');
        }
      </script>

    
</body>
</html>
<?php
  
}else
  {
    echo "<h1>ERROR 301</h1> <p>Unauthorized Access !</p>";
  }

?>
