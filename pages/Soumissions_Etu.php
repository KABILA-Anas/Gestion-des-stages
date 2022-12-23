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
    
    require('back_end/connexion.php');
    /// ***ID  etudiant
    $Etu=$_SESSION['user_id'];
    /// ***Nombre de soumissions
    $Smt =$bdd->prepare("SELECT count(e.ID_ETU) as Nbr_soums FROM etudiant e,postuler p WHERE e.ID_ETU = p.ID_ETU AND e.ID_ETU=? AND p.STATU=? ");
    $Smt->execute(array($Etu,'Retenue'));
    $row = $Smt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumission Etudiants</title>
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
                <a class="nav-link navlink " href="Find_Offre_Etu.php">Find offers</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Historique.php">Historique</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink active_link_color" href="Soumissions_Etu.php">Soumissions</a><span class="active_link_line"></span>
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
                      <li><a href="Profile.php"><img src="popup/user.png" alt="">My profile</a> </li>
                      <li><a href="back_end/logout.php"><img src="popup/log-out.png" alt="">Log out</a> </li>
                  </ul>
              
               </div>

              </div>
          </div>
        </div>
      </nav>

    <div class="container-fluid ">
      <div class="" style="margin-top: 56px;">
        <div class="row">
          <div class="col-3 d-none d-md-block elm guid1_col"></div>
          <form action="Soumissions_Etu.php" method='POST'>
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

            function Create_Offres($Offres ,$Etat_Offre){
                
                require('back_end/connexion.php');
                
                if(!empty($Offres))
                  {
                    foreach($Offres as $Offre):
                                               
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
                        
                        switch($Etat_Offre){
                            case 1:
                                echo'<label style="text-align:end;text-decoration:underline;color: cornflowerblue;">Acceptée</label>';
                                break;
                            case 2:
                                echo'<label style="text-align:end;text-decoration:underline;color: cornflowerblue;">Non Acceptée</label>';
                                break;
                            case 3:
                                echo'<form action="back_end/Statu_Post_Etu.php" method="post" style="display: inline-block;">
                                      <input type="hidden" name="offre_non_accepte" value="'.$of_id.'">
                                      <button type="submit" class="butt_style"  style="background:lightgrey;" onClick="LastScroll()" >REFUSER</button>
                                   </form>';
                                echo"  ";
                                echo'<form action="back_end/Statu_Post_Etu.php" method="post" style="display: inline-block;">
                                      <input type="hidden" name="offre_accepte" value="'.$of_id.'">
                                      <button type="submit" class="butt_style" onClick="LastScroll()" >ACCEPTER</button>
                                    </form>';
                                break;
                            case 4:
                                echo'<label style="text-align:end;text-decoration:underline;color: cornflowerblue;">Postulée</label>';
                                break;
                            case 5:
                                echo'<label style="text-align:end;text-decoration:underline;color: cornflowerblue;">Non Retenue</label>';
                                break;
                            case 6:
                                echo'<label style="text-align:end;text-decoration:underline;color: cornflowerblue;"> Retenue en attente</label>';
                                break;
                        }
                    ?>
                </div>
              </div>


            </div><br>
            <?php endforeach;} }
            
            
              
            ///Niveau et Formation de l'etudiant
            $sql ="SELECT NIVEAU,ID_FORM FROM etudiant WHERE ID_ETU='$Etu' ";
            $req =$bdd->query($sql);
            $result = $req->fetch(PDO::FETCH_ASSOC);
            $NIVEAU=$result['NIVEAU'];
            $FORMATION=$result['ID_FORM'];
            
            /// ***
            $Filter_sql = "";
            /// ***
            if(isset($_POST['Filter']) && !empty( $_POST['Filter'] )){

                $Filter_search = $_POST['Filter'];
                $Filter_sql=" AND( ( E.VILLE = '$Filter_search' ) OR (O.POSTE = '$Filter_search' ) OR (O.DESCRIP LIKE '%$Filter_search%' ) OR (E.NOM_ENTREP LIKE '$Filter_search' ) OR (STATU LIKE '$Filter_search' ))";

            }
            /// *** SELECTION ET JOINTURE
            $select_join = "SELECT * FROM offre O,entreprise E,postuler P WHERE E.ID_ENTREP=O.ID_ENTREP AND O.ID_OFFRE = P.ID_OFFRE AND P.ID_ETU='$Etu' ";

            /// *** Retenue
            $sql3 =$select_join." AND P.STATU='Retenue'".$Filter_sql;            
            $req3 =$bdd->query($sql3);
            $result3 = $req3->fetchAll(PDO::FETCH_ASSOC);
            Create_Offres($result3 , 3);
            
            /// *** Retenue en attente
            $sql1 =$select_join." AND P.STATU='Retenue en attente'".$Filter_sql;             
            $req1 =$bdd->query($sql1);
            $result1 = $req1->fetchAll(PDO::FETCH_ASSOC);
            Create_Offres($result1 , 6);

            /// *** Acceptée
            $sql1 =$select_join." AND P.STATU='Acceptée'".$Filter_sql;             
            $req1 =$bdd->query($sql1);
            $result1 = $req1->fetchAll(PDO::FETCH_ASSOC);
            Create_Offres($result1 , 1);
            
            /// *** Postulée
            $sql4 =$select_join." AND P.STATU='Postulée'".$Filter_sql;            
            $req4=$bdd->query($sql4);
            $result4 = $req4->fetchAll(PDO::FETCH_ASSOC);
            Create_Offres($result4 , 4);
            
            /// *** Non Acceptée
            $sql2 =$select_join." AND P.STATU='Non Acceptée'".$Filter_sql;             
            $req2 =$bdd->query($sql2);
            $result2 = $req2->fetchAll(PDO::FETCH_ASSOC);
            Create_Offres($result2 , 2);
            
            
            /// *** Non retenue
            $sql5 =$select_join." AND P.STATU='Non Retenue'".$Filter_sql;             
            $req5 =$bdd->query($sql5);
            $result5= $req5->fetchAll(PDO::FETCH_ASSOC);
            Create_Offres($result5 , 5);

            ;?>            
          </div>
          <div class="col-3 d-none d-md-block elm blank_col"></div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
    
    <script>
      
      var scrollpos = localStorage.getItem('scrollpos_Soumis_Etu');
      
      if (scrollpos){
            window.scrollTo({left:0,top:scrollpos,behavior:'instant',});
            localStorage.removeItem('scrollpos_Soumis_Etu');
      }

      function LastScroll(){
        localStorage.setItem('scrollpos_Soumis_Etu', window.scrollY);
      }
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
    echo "<h1>ERROR 301</h1> <p>Unauthorized Access !</p>";
  }
?>

