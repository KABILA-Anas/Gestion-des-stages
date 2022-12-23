
<?php 
  ob_start();
  session_start();
  if(empty($_SESSION['user_id']) || empty($_SESSION['user_type']))
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location:login.php');
  }
  
  if( $_SESSION['user_type'] == "Etudiant")
  {
    require("back_end/connexion.php");
    $Etu=$_SESSION['user_id'];
    /// ***Nombre de soumissions
    $Smt =$bdd->prepare("SELECT count(e.ID_ETU) as Nbr_soums FROM etudiant e,postuler p WHERE e.ID_ETU = p.ID_ETU AND e.ID_ETU=? AND p.STATU=? ");
    $Smt->execute(array($Etu,'Retenue'));
    $row = $Smt->fetch(PDO::FETCH_ASSOC);


    ///****  Select les stages fini
    $Smt1 =$bdd->prepare("SELECT s.ID_STAGE,POSTE,NOM_ENTREP,s.CONTRAT,r.FICHIER FROM entreprise ent,offre o,stage s,rapport r  WHERE ent.ID_ENTREP =o.ID_ENTREP AND o.ID_OFFRE=s.ID_OFFRE AND r.ID_STAGE=s.ID_STAGE AND s.ID_ETU=? AND s.STATUSTG='2'");
    $Smt1->execute(array($Etu));
    $stages_finis = $Smt1->fetchAll(PDO::FETCH_ASSOC);

    /// *** Autres Stages
    $Smt2 =$bdd->prepare("SELECT s.ID_STAGE,POSTE,NOM_ENTREP,s.CONTRAT,s.STATUSTG FROM entreprise ent,offre o,stage s  WHERE ent.ID_ENTREP =o.ID_ENTREP AND o.ID_OFFRE=s.ID_OFFRE AND s.ID_ETU=? AND s.STATUSTG!='2' ");
    $Smt2->execute(array($Etu));
    $stages_non_finis = $Smt2->fetchAll(PDO::FETCH_ASSOC);


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre</title>
    <link rel="stylesheet" href="nextab.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
</head>
<body>

  
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed" style="z-index: 9; width: 100%; top: 0;background: #F3F5F8 !important;">
        <div class="container-fluid">
          <a class="navbar-brand navt d-lg-block d-lg-none" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ">
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Find_Offre_Etu.php">Find offers</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Historique.php">Historique</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Soumissions_Etu.php">Soumissions</a>
                <?php if(!empty($row)){ if($row['Nbr_soums']){ ?><span class="icon-button__badge"><?php $Nb_rtn =$row['Nbr_soums'];if($Nb_rtn)print($Nb_rtn);}} ?></span>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink active_link_color" href="MeStages_Etu.php">Mes Stages</a><span class="active_link_line"></span>
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
        </div>
      </nav>
  

    <div class="container-fluid " >
      <div class="" style="margin-top: 100px; ">        
        <div class="row">
            <div class="col-12 col-md-6 pub_col">

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Mes Stages</h4>
                  </div>                  
                
                <table class="table" id="Table_Histo">
                    <thead>
                      <tr>
                     
                       
                      
                        <th scope="col">Poste</th>
                        <th scope="col">Entreprise</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>

                        <!-- FINIS -->
                        <?php foreach($stages_finis as $stage): ?>
                        <tr>
                          <td style="color: #7096FF;"><?php print($stage['POSTE']); ?></td>
                          <td style="color: #7096FF;"><?php print($stage['NOM_ENTREP']); ?></td>
                          <td ><p class="status status-paid">Finis</p> </td>
                          <td style="text-align: end;">
                          <form action="back_end/PDFDownLoad.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="contract" value="<?php print($stage['CONTRAT']); ?>">
                            <button type="submit" class="btn btn-outline-primary">Contract</button>
                          </form>
                          <form action="back_end/PDFDownLoad.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="rapport" value="<?php print($stage['FICHIER']); ?>">
                            <button type="submit" class="btn btn-outline-primary">Rapport</button>
                          </form>
                          <a href="Detail_Stage.php?id_stage=<?php print($stage['ID_STAGE']); ?>"><button type="button" class="btn btn-outline-primary">Detail</button></a>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                          <!-- NON finis -->
                        <?php foreach($stages_non_finis as $stage): ?>
                        <tr>
                          <td style="color: #7096FF;"><?php print($stage['POSTE']); ?></td>
                          <td style="color: #7096FF;"><?php print($stage['NOM_ENTREP']); ?></td>
                          <?php if($stage['STATUSTG'] == 1){ ?><td ><p class="status status-pending">Encours</p> </td>
                          <?php }else if($stage['STATUSTG'] == 0){ ?><td ><p class="status status-unpaid">Annulée</p> </td><?php } ?> 
                          <td style="text-align: end;">
                          <form action="back_end/PDFDownLoad.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="contract" value="<?php print($stage['CONTRAT']); ?>">
                            <button type="submit" class="btn btn-outline-primary">Contract</button>
                          </form>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                   
                  </table>
                 
              </div>
          </div>
          



        </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous"></script>
          

        
</body>
</html>

<script>
  
  function menuToggle(){
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle('active');
        }
 

      

     
    
 






</script>
<?php
  }
  else
  {
    echo "<h1>ERROR 301</h1> <p>Unauthorized Access !</p>";
  }
?>