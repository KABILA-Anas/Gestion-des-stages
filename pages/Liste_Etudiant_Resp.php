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
      /// *** Type formation
      $Smt = $bdd->prepare("SELECT TYPE_FORM FROM formation WHERE ID_FORM=?");
      $Smt -> execute(array($id_form));
      $row = $Smt->fetch(PDO::FETCH_ASSOC);
      $type_form = $row['TYPE_FORM'];
      $json_type_form = json_encode($type_form); 
      /// *** Liste des etudiants
      $Smt = $bdd->prepare("SELECT * FROM etudiant e,users u WHERE e.ID_USER=u.ID_USER AND e.ID_FORM=? AND u.VERIFIED=1");
      $Smt -> execute(array($id_form));
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
    <title>Listes des Etudiants</title>
</head>
<body>
      
    <nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed" style="z-index: 9; width: 100%; top: 0; background: #F3F5F8 !important;">
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
      <div class="" style="margin-top: 100px;">
        


        <div class="row" >
            <div class="col-md-11 pub_col">

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Liste des etudiants</h4>   
                  </div>
                  
                  <?php
                  if( !empty( $rows ) )
                  {
                  ?>

                <table class="table" id="Table_Etu">
                    <thead>
                      <tr>
                      <?php if( $type_form){ ?><th scope="col">N</th><?php } ?>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">CNE</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>

                   
                    <?php foreach ($rows as $row): 
                    
                      ?>
                        <tr>
                        <?php if( $type_form){ ?><td scope="row" ><p style="color: #7096FF;"><?php echo $row['NIVEAU'] ; ?></p></td><?php } ?>
                          <td><?php echo $row['NOM_ETU']; ?></td>
                          <td><?php echo $row['PRENOM_ETU']; ?></td>
                          <td style="color: #7096FF;"><?php echo $row['CNE']; ?></td>
                          <td style="text-align: end;">
                          
                          <a href="Encours_Resp.php?id_etu=<?php print($row['ID_ETU']);?>" ><button  class="btn btn-outline-primary">Encours</button></a>
                          <a href="Soumis_Resp.php?id_etu=<?php print($row['ID_ETU']);?>" ><button  class="btn btn-outline-primary">Soumissions</button></a>
                          <a href="Modifier_Etudiant_Resp.php?id_etu=<?php print($row['ID_ETU']);?>" ><button  class="btn btn-outline-primary">Modifier</button></a>
                            <?php if($row['ACTIVE'] == 1){ ?>
                            <form action="back_end/Desactiver.php" method="post" style="display: inline-block;" >
                                <input type="hidden" name="id_etu_disac" value="<?php echo $row['ID_ETU']; ?>">
                                <button type="submit" class="btn btn-outline-primary" >Desactiver</button>
                            </form>
                            <?php }else{?>
                            <form action="back_end/Desactiver.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="id_etu_ac" value="<?php echo $row['ID_ETU']; ?>">
                                <button type="submit" class="btn btn-outline-primary" style="width:7rem;">Activer</button>
                            </form>
                            <?php }?>
                          </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                    <tfoot>
                      <tr>
                      <?php if( $type_form){ ?><th scope="col">N</th><?php } ?>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">CNE</th>
                        <th scope="col"></th>
                      </tr>
                    </tfoot>
                  </table>

                  <?php
                  }
                  else
                    echo '<div class="alert alert-primary" role="alert">
                            No data found !
                          </div>';
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
<?php
  }
  else
  {
    echo "<h1> ERROR 301:</h1> <p>Unauthorized Access !</p>";
  }

?>
<script>

  var type_form = <?php echo $json_type_form; ?>;


  $(document).ready( function () {
    var dataTable = $('#Table_Etu').DataTable({
      // remove label for search and add placeholder
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search..."
    }
    });



    $('#Table_Etu tfoot tr th').each(function () {
    var title = $('#Table_Etu thead tr th').eq($(this).index()).text();
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

    // column filter function
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
    
    }
    )


    function menuToggle(){
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle('active');
        }
  

</script>