<?php 
  ob_start();
  session_start();
  if(empty($_SESSION['user_id']) || empty($_SESSION['user_type']))
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location:login.php');
  }

  if($_SESSION['user_type'] == "Responsable")
  {

    require("back_end/connexion.php");

    $id_form = $_SESSION['user_id'];
    
    /// *** Type formation
    $Smt = $bdd->prepare("SELECT TYPE_FORM FROM formation WHERE ID_FORM=?");
		$Smt -> execute(array($id_form));
    $row = $Smt->fetch(PDO::FETCH_ASSOC);
    $type_form = $row['TYPE_FORM'];   
    $json_type_form = json_encode($type_form);              
    ///Tous les offres de cette formation
    $Smt = $bdd->prepare("SELECT * FROM offre O,entreprise E WHERE E.ID_ENTREP=O.ID_ENTREP AND O.ID_FORM=? ORDER BY O.ID_OFFRE DESC");
    $Smt -> execute(array($id_form));
	  $rows = $Smt -> fetchAll(PDO::FETCH_ASSOC);
    $Smt->closeCursor();

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
    <title>Find Offers</title>
    
    
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
                $Smt =$bdd->prepare("SELECT count(u.ID_USER) as Nbr_non_Verif from etudiant e,Users u WHERE u.ID_USER=e.ID_USER AND u.VERIFIED=? AND e.ID_FORM=?  ");
                $Smt->execute(array('0',$id_form));
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                if(!empty($row)){ if($row['Nbr_non_Verif']){ ?><span class="icon-button__badge"><?php $Nb_non_verif =$row['Nbr_non_Verif'];if($Nb_non_verif)print($Nb_non_verif);}} ?></span>
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
      <div class="" style="margin-top:5%;">

          <div class="row" >
            
            <div class="col-md-8 col-12 pub_col" style="border-radius: 35px !important; ">
            <!-- <div class="col-md-6 col-12 pub_col" style="display:flex; justify-content:center;z-index:9 !important;">
              <div class="search">
                  <div class="input-group rounded">
                      <input type="search" class="form-control rounded" id="search_filter" name='Filter' placeholder="Type a Keyword, Title, City" aria-label="Search" aria-describedby="search-addon" />
                      <span class="input-group-text border-0" id="search-addon">
                          <button type='submit' style="border:none;background:none;"><i class="fas fa-search"><img src="icons/search.png"></i></button>
                      </span>
                  </div>
                </div>
              </div> -->
                  <div class="tableHead" style="margin-bottom: 30px; margin-top:10px;">
                        <h4>Liste des offres</h4>
                        <a href="Publier_Offre_Resp.php"><i><img src="icons/plus.png" alt="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></i></a>   
                  </div>
                  
                  <?php
                    if( !empty($rows) )
                    {
                  ?>

                <table class="table" id="Table_Offre">
                    <thead>
                      <tr>
                         <?php if( $type_form){ ?><th scope="col">N</th><?php } ?>
                        <th scope="col">Statu</th>
                        <th scope="col">Entreprise</th>
                        <th scope="col">Ville</th>
                        <th scope="col">Poste</th>
                        <th scope="col"></th>
                        
                      </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                        foreach ($rows as $row): 
                    ?>
                        <tr>
                            <?php if( $type_form){ ?><td scope="row" > <p style="color: #7096FF;"><?php echo $row['NIVEAU_OFFRE']; ?></p></td><?php } ?>
                            <?php
                              switch ($row['STATUOFFRE']) {
                                case 'Nouveau':
                                  $class = 'status status-paid';
                                  break;
                                case 'Completée':
                                  $class = 'status status-pending';
                                  break;
                                case 'Closed':
                                  $class = 'status status-unpaid';
                                  break;
                                
                                // default:
                                //   $class = '#7096FF';
                                //   break;
                              }
                            ?>
                            <td ><p class="<?php echo $class; ?>"><?php echo $row['STATUOFFRE']; ?></p></td>
                          <td><?php echo $row['NOM_ENTREP']; ?></td>
                          <td><?php echo $row['VILLE']; ?></td>
                          <td ><p style="color: #7096FF;"><?php echo $row['POSTE']; ?></p></td>
                          <td style="text-align: end; ">
                            
                            <a href="#" data-bs-toggle="modal" data-bs-target="#offre<?php print($row['ID_OFFRE']);?>" title="detail" ><i style="margin-right: 15px;"><img src="icons/loupe.png" alt=""></i></a>
                            <a href="Modifier_Offre_Resp.php?id_offre=<?php print($row['ID_OFFRE']);?>" ><i style="margin-right: 15px;"><img src="icons/edit.png" alt=""></i></a>
                            <a href="Liste_Attente_Resp.php?id_offre=<?php print($row['ID_OFFRE']);?>" ><i style="margin-right: 15px;"><img src="icons/file.png" alt=""></i></a>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        <?php if( $type_form ){ ?><th scope="col">N</th><?php } ?>
                          <th scope="col">Statu</th>
                          <th scope="col">Entreprise</th>
                          <th scope="col">Ville</th>
                          <th scope="col">Poste</th>
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
          
          <!-- Detail -->
          <?php foreach($rows as $Offre): ?>
          
            <div class="modal fade"  id="offre<?php print($Offre['ID_OFFRE']); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"  style="max-width:800px !important;" >
                <div class="modal-content" >
                  <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Offre </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" >
                  <div class="col-md-12 elm pub_col">
                      <div class="brd">
                        <?php                  
                            if($Offre['STATUOFFRE'] == 'Nouveau')
                                  echo '<div class="greenc"> </div>'; 
                            else if($Offre['STATUOFFRE'] == 'Completée')
                                  echo '<div class="grayc"> </div>';
                            else if($Offre['STATUOFFRE'] == 'Closed')
                                  echo '<div class="redc"> </div>';  
                        ?>
                
                        <div class="content">

                          <span class="poste" ><?php print($Offre['POSTE'])?></span> <br><br>

                          <span class="ville" ><?php print($Offre['NOM_ENTREP'])?> - <?php print($Offre['VILLE'])?></span> <br>

                          <span class="duree" >(Durée <?php print($Offre['DUREE']/30);?> months)</span> <br><br>

                          <div  >
                            <p style="white-space: pre-line"><?php print($Offre['DESCRIP']);?></p>
                          </div>

                          <div>
                            <span class="time"> <img src="icons/time.png" alt=""> <?php print($Offre['DATEFIN']);?> </span>
                          </div>

                        </div>

                      </div><br>
                                 
                    </div>
                      
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
                  </div>
                </div>
              </div>
            </div>
    
        </div>
        <?php endforeach;?>
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

<script>

  var type_form = <?php echo $json_type_form; ?>;
console.log(type_form)

// jquery main function
$(document).ready( function () {
  //data table init and props
    var dataTable = $('#Table_Offre').DataTable({
      columnDefs: [
                              {targets: -1 }
                            ],
      responsive: true
      ,
      // remove label for search and add placeholder
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search..."
    }
    });
    
    
    // add input or select for column filter 
    $('#Table_Offre tfoot tr th').each(function () {
    var title = $('#Table_Offre thead tr th').eq($(this).index()).text();
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


    //console.log($('thead tr th').length) 
      // dataTable.column(0).every( function () {
      //   var dataTableColumn = this;
      //   //console.log(dataTableColumn);
      //   $('#search_filter').on('keyup change', function () {
      //       // console.log(this.value);
      //         dataTableColumn.search(this.value).draw();
      //     });
      // })

      
      
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

