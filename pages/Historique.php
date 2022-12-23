<?php
  ob_start();
  session_start();
  if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location: login.php');
  }
  if($_SESSION['user_type'] == "Admin")
  {
    echo "<h1> ERROR 301:</h1> <p>Unauthorized Access !</p>";
    echo "<a href='".$_SESSION['main_page']."' >return te the main page</a>";
    exit(0);
  }
    
      require('back_end/connexion.php');
      $id_form = $_SESSION['user_id'];
      //echo "<br><br><br><br><br>";
      /// *** Type formation
      $Smt = $bdd->prepare("SELECT TYPE_FORM FROM formation WHERE ID_FORM=?");
      $Smt -> execute(array($id_form));
      $row = $Smt->fetch(PDO::FETCH_ASSOC);
      //var_dump($row);
      if( !empty($row['TYPE_FORM']) )
        $type_form = $row['TYPE_FORM']; 
      else
        $type_form = NULL;
       
                 

      if($_SESSION['user_type'] == "Etudiant"){
         
        $sql = "SELECT f.ID_FORM,f.TYPE_FORM FROM etudiant e,formation f WHERE e.ID_FORM =f.ID_FORM AND ID_ETU='$id_form' ";
        $req = $bdd->query($sql); 
        $result = $req->fetch(PDO::FETCH_ASSOC);
        $id_form = $result['ID_FORM'];
        $type_form = $result['TYPE_FORM'];
      }

      $json_type_form = json_encode($type_form);   
      
      /// *** Stage Infos
      $req = "SELECT s.ID_STAGE,NIVEAU_STAGE,NOM_ETU,PRENOM_ETU,POSTE,NOM_ENTREP,r.FICHIER,s.DATEDEBUT_STAGE FROM entreprise ent,offre o,stage s,etudiant etu,rapport r  WHERE ent.ID_ENTREP =o.ID_ENTREP AND o.ID_OFFRE=s.ID_OFFRE AND s.ID_ETU = etu.ID_ETU AND r.ID_STAGE=s.ID_STAGE AND o.ID_FORM='$id_form' ";    
      $Smt = $bdd->query($req);
      $rows = $Smt->fetchAll(PDO::FETCH_ASSOC);



      
    
  

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
  

    <div class="container-fluid " >
      <div class="" style="margin-top: 100px; ">        
        <div class="row">
            <div class="col-md-11 pub_col">

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Liste des Stages</h4>
                  </div>                  
                <?php 
                if( !empty($rows) )
                {
                ?>
                <table class="table" id="Table_Histo">
                    <thead>
                      <tr>
                      <?php if($type_form){ ?><th scope="col">N</th><?php } ?>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Entreprise</th>
                        <th scope="col">Année</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>

                   
                    <?php 
                    
                    foreach ($rows as $row): 
                    
                      ?>
                        <tr>
                        <?php if( $type_form){ ?><td scope="row" ><p style="color: #7096FF;"><?php echo $row['NIVEAU_STAGE'] ; ?></p></td><?php } ?>
                          
                          <td><?php echo $row['NOM_ETU']; ?></td>
                          <td><?php echo $row['PRENOM_ETU']; ?></td>
                          <td style="color: #7096FF;"><?php echo $row['POSTE']; ?></td>
                          <td style="color: #7096FF;"><?php echo $row['NOM_ENTREP']; ?></td>
                          <td colspan="2">
                            <?php echo date('Y', strtotime($row['DATEDEBUT_STAGE'])); ?>
                          </td>
                          <td style="display:none;">
                            <?php
                              $Smt = $bdd->prepare("SELECT * from motcle m,referencer r WHERE r.ID_MOTCLE=m.ID_MOTCLE AND 
                                                    ID_RAPP = ( SELECT ID_RAPP FROM rapport WHERE ID_STAGE=?)");
                              $Smt -> execute(array($row['ID_STAGE']));
                              $motcles = $Smt->fetchAll(PDO::FETCH_ASSOC);

                              foreach($motcles as $motcle){
                                echo $motcle['MOT']." ";
                              }
                            ?>
                          </td>
                          <td style="text-align: end;">
                          <form action="back_end/PDFDownLoad.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="rapport" value="<?php print($row['FICHIER']);?>">
                            <button type="submit" class="btn btn-outline-primary">Rapport</button>
                          </form>
                            <a href="Detail_Stage.php?id_stage=<?php print($row['ID_STAGE']); ?>"><button type="button" class="btn btn-outline-primary">Detail</button></a>
                          </td>
                        </tr>
                    <?php 
                    endforeach; 
                    
                    ?>

                    </tbody>
                    <tfoot>
                    <?php if( $type_form){ ?><th scope="col">N</th><?php } ?>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Entreprise</th>
                        <th scope="col">Année</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tfoot>
                  </table>
                  <?php
                  }
                  else
                  {
                    echo '<div class="alert alert-primary" role="alert">
                            No data found !
                          </div>';
                  }
                  ?>
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
  var type_form = <?php echo $json_type_form; ?>;
  function menuToggle(){
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle('active');
        }
  $(document).ready( function () {
      var dataTable = $('#Table_Histo').DataTable({
        // remove label for search and add placeholder
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Mots clés..."
    }
      });

      

      $('#Table_Histo tfoot tr th').each(function () {
      var title = $('#Table_Histo thead tr th').eq($(this).index()).text();
      if(title != "")
      {
        switch (title) {
        case 'N':
      if( type_form == 1 )
              $(this).html('<select  id="table-filter1" class="form-select select" ><option value="">Choix de N</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>');
            else if( type_form == 2 )
              $(this).html('<select  id="table-filter1" class="form-select select" ><option value="">Choix de N</option><option value="1">1</option><option value="2">2</option></select>');
          break;
        case 'Statu':
          $(this).html('<select  id="table-filter1" class="form-select select" ><option value="">Choix de STATU</option><option value="Nouveau">Nouveau</option><option value="Closed">Closed</option><option value="Completée">Completée</option></select>');
          break;
        default:
        $(this).html('<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="' + title + '" />');
          break;
      }
      }
      
      });

      
      dataTable.columns().every(function () {
        var dataTableColumn = this;

        $(this.footer()).find('select').on('change', function () {
            dataTableColumn.search(this.value).draw();
        });

        $(this.footer()).find('input').on('keyup change', function () {
            dataTableColumn.search(this.value).draw();
        });

        

    });


    // search style class
    $('.dataTables_filter').addClass('rounded search_custom');

    })

    
 






</script>