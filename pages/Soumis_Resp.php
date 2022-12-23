<?php 
  session_start();
  if(empty($_SESSION['user_id']) || empty($_SESSION['user_type']))
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location:login.php');
  }  

  if($_SESSION['user_type'] == "Responsable")
  {
    require('back_end/connexion.php');

    $Resp = $_SESSION['user_id'];
    if(isset($_GET['id_etu']))
    {
      $id_etu = $_GET['id_etu'];
      /// *** Test access
      $Smt =$bdd->prepare("SELECT ID_FORM FROM etudiant WHERE ID_ETU=?");
      $Smt->execute(array($id_etu));
      $etu_form = $Smt->fetch(PDO::FETCH_ASSOC);
      $Smt->closeCursor();//vider le curseur (free) 
      
      if($etu_form['ID_FORM'] != $Resp )
          exit("You're not allowed to access for this student");
    }else{
        exit("Error 404 ");
  }

  if(isset($_GET['id_etu']))
    {
      //$sql ="SELECT * FROM postuler p,offre o,entreprise e WHERE p.ID_OFFRE = o.ID_OFFRE AND o.ID_ENTREP =e.ID_ENTREP  AND  o.ID_FORM='$Resp' AND p.ID_ETU='$id_etu' AND o.STATUOFFRE!='Completée'";
      $sql = "SELECT * FROM
              (
                SELECT p.*  
                FROM postuler p,offre o
                WHERE p.ID_OFFRE = o.ID_OFFRE AND NOT EXISTS 
                                                            (SELECT p1.*
                                                             FROM postuler p1 ,offre o1
                                                             WHERE p1.ID_OFFRE = o1.ID_OFFRE AND STATUOFFRE ='Completée' AND STATU='Postulée'
                                                             AND o.ID_OFFRE=o1.ID_OFFRE AND p.ID_ETU=p1.ID_ETU	
                                                            )
                ) j,postuler p2,offre o2,entreprise e WHERE p2.ID_ETU = j.ID_ETU AND p2.ID_OFFRE = j.ID_OFFRE 
                  AND p2.ID_OFFRE = o2.ID_OFFRE AND o2.ID_ENTREP =e.ID_ENTREP AND o2.ID_FORM='$Resp' AND p2.ID_ETU='$id_etu' AND p2.STATU!='Annulée' AND p2.STATU!='Fini' ";
      ///***Search bar
      if(isset($_POST['Filter']) && !empty( $_POST['Filter'] )){

          $Filter_search = $_POST['Filter'];
          $sql=$sql." AND( (e.VILLE = '$Filter_search' ) OR (o2.POSTE = '$Filter_search' ) OR (o2.DESCRIP LIKE '%$Filter_search%' ) OR (e.NOM_ENTREP LIKE '$Filter_search' ) OR (p2.STATU LIKE '$Filter_search' ) )";
          
      }
      /// ***Order by
      $sql=$sql." ORDER BY o2.ID_OFFRE DESC";
      $req =$bdd->query($sql);
      $result = $req->fetchAll(PDO::FETCH_ASSOC);
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumission Responsable</title>
    <link rel="stylesheet" href="pub_etud.css">
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
                $Smt->execute(array('0',$etu_form['ID_FORM']));
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
      <div class="" style="margin-top: 60px;">
        <div class="row">
          <div class="col-3 d-none d-md-block elm guid1_col"></div>
          <?php if(!empty($result)){ ?>
          <form action="Soumis_Resp.php?id_etu=<?php if(isset($_GET['id_etu']))print($_GET['id_etu']);?>" method='POST'>
            <div class="col-md-6 col-sm-12 elm pub_col" style="position:fixed; text-align: center; display:flex; justify-content:center;">
              
                <div class="search">
                  <div class="input-group rounded">
                      <input type="search" class="form-control rounded" name='Filter' placeholder="Type a Keyword, Title, City" aria-label="Search" aria-describedby="search-addon" />
                      <span class="input-group-text border-0" id="search-addon">
                          <button type='submit' style="border:none;background:none;"><i class="fas fa-search"><img src="icons/search.png"></i></button>
                      </span>
                  </div>
                </div>
              </div>
          </form>
          <?php } ?>
          <div class="col-3 d-none d-md-block elm blank_col"></div>
        </div>

        <div class="row" style="margin-top: 56px;">
          <div class="col-12  d-md-none elm">

            <div class="greenc" style="display:inline-block;margin-left: 0%;">
                 <span class="guide2"> Nouveau </span>
            </div>

            <div class="grayc" style="display:inline-block; margin-left: 27%;">
                 <span class="guide2"> Complétée </span> 
            </div>

            <div class="redc" style="display:inline-block; margin-left: 34%;">
              <span class="guide2">Close</span>
            </div>

          </div>
        </div>


        <div class="row">

          <div class="col-3 d-none d-md-block elm guid1_col">
            <div class="guid2_col">

              <div class="greenc"> 
                <span class="guide"> Nouveau </span>
              </div> <br>

              <div class="grayc"> 
                <span class="guide"> Complétée </span>
              </div> <br>

              <div class="redc"> 
                <span class="guide">Close</span>
              </div> <br>

            </div>
          </div>
          
          <div class="col-md-6 col-sm-12 elm pub_col">

<?php  
          if(!empty($result)){
          
            foreach($result as $Offre):                  
            ?>
          
            <div class="brd">
              <?php
                  if($Offre['STATUOFFRE'] == 'Nouveau')
                        echo '<div class="greenc"> </div> <br>'; 
                  else if($Offre['STATUOFFRE'] == 'Completée')
                        echo '<div class="grayc"> </div> <br>';
                  else if($Offre['STATUOFFRE'] == 'Closed')
                        echo '<div class="redc"> </div> <br>';  
              ?>
              

              <div class="content">

                <span class="poste" ><?php print($Offre['POSTE'])?></span> <br><br>

                <span class="ville" ><?php print($Offre['NOM_ENTREP'])?> - <?php print($Offre['VILLE'])?></span> <br>

                <span class="duree" >(Durée <?php print($Offre['DUREE']/30);?> months)</span> <br><br>

                <div class="desc" >
                  <p style="white-space: pre-line"><?php print($Offre['DESCRIP']);?></p>
                </div>

                <div>
                  <span class="time"> <img src="icons/time.png" alt=""> <?php print($Offre['DATEFIN']);?> </span>
                </div>

              </div>
    
              <div class="butt_align">
                <div style="text-align:end;">
                    <?php
                        
                        $of_id = $Offre['ID_OFFRE'];
                        $sql_statu = "SELECT STATU FROM postuler WHERE ID_OFFRE ='$of_id' AND ID_ETU='$id_etu' ";
                        $req_statu =$bdd->query($sql_statu);
                        $result_statu = $req_statu->fetch(PDO::FETCH_ASSOC);

                        if(!empty($result_statu)){
                          
                          $Statu_etu =  $result_statu['STATU'];
                          if($Statu_etu == 'Postulée'){
                              echo'<form action="back_end/Statu_Post_Resp.php" method="post" style="display: inline-block;">
                                      <input type="hidden" name="Post_Non_Retenue" value="'.$of_id.'">
                                      <input type="hidden" name="id_etu" value="'.$id_etu.'">
                                      <button type="submit" class="butt_style" style="background:lightgrey;" onClick="LastScroll()">NON RETENUE</button>
                                    </form>';
                              echo"  ";
                              echo'<form action="back_end/Statu_Post_Resp.php" method="post" style="display: inline-block;">
                                      <input type="hidden" name="Post_Retenue" value="'.$of_id.'">
                                      <input type="hidden" name="id_etu" value="'.$id_etu.'">
                                      <button type="submit" class="butt_style" style="background:7096FF;" onClick="LastScroll()">RETENUE</button>
                                    </form>';
                          }else{
                              echo'<label style="text-align:end;text-decoration:underline;color: cornflowerblue;">'.$Statu_etu.'</label>';
                          }
                          
                        }else{
                          echo '<div class="alert alert-primary" role="alert">
                            No data found !
                          </div>';
                        }
                        
                        
                        
                    ?>
                </div>
              </div>


            </div><br>
            <?php 
              endforeach;
            }
            else
              echo '<div class="alert alert-primary" role="alert" style="margin-top:5%;">
                      No data found !
                    </div>';
              } 
              
            ?>            
          </div>
          <div class="col-3 d-none d-md-block elm blank_col"></div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
    <script>
      var scrollpos = localStorage.getItem('scrollpos_Soumis_Resp');
      
      if (scrollpos){
            window.scrollTo({left:0,top:scrollpos,behavior:'instant',});
            localStorage.removeItem('scrollpos_Soumis_Resp');
      }

      function LastScroll(){
        localStorage.setItem('scrollpos_Soumis_Resp', window.scrollY);
      }
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
















