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
      require('back_end/connexion.php');
  
      /// *** Responsables
      $Smt = $bdd->prepare("SELECT * FROM formation f,enseignant e,departement d WHERE f.ID_ENS=e.ID_ENS AND e.ID_DEPART=d.ID_DEPART ");
      $Smt -> execute();
	    $rows = $Smt -> fetchAll(PDO::FETCH_ASSOC);
      $Smt->closeCursor();//vider le curseur (free) 

      // $info_form = array();

      // echo "<br><br><br><br>";
      
      // array_push($info_form,$rows['FILIERE']);
      // array_push($info_form,$rows['TYPE_FORM']);
      // array_push($info_form,$rows['NOM_DEPART']);
  
      // var_dump($rows);
  
      


      /// *** Departements
      $Smt = $bdd->prepare("SELECT * FROM departement ");
      $Smt -> execute();
	    $deps = $Smt->fetchAll(PDO::FETCH_ASSOC);
      $Smt->closeCursor();//vider le curseur (free) 

      //$json_all_etu = json_encode($info_form);
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <title>Formations</title>
    
    
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
                <a class="nav-link navlink active_link_color" href="Liste_Formations_Admin.php">Formations</a><span class="active_link_line"></span>
              </li>
              
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Liste_Enseignants_Admin.php">Enseignants</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Liste_Entreprises_Admin.php">Entreprises</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Liste_Departement_Admin.php">Départements</a>
              </li>
            </ul>
            <div class="" style="position: fixed; margin-left: 44%;">
            <a class="navbar-brand navt d-none d-lg-block" href="#"> <img src="icons/weblog.png" alt="" width="150" height="35"></a>
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
      <div class="" style="margin-top: 150px;">
        


        
         


          <div class="row" >
            <div class="col-12 col-md-8 pub_col">

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Liste des Formations</h4> 
                        <i><img src="icons/plus.png" alt="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></i>
                  </div>
                  

                <table class="table" id="Table_Etu">
                    <thead>
                      <tr>
                         <th scope="col">Formation</th>
                         <th scope="col">Type</th>
                        <th scope="col">Departement</th>
                        <th scope="col">Responsable</th>
                        <th scope="col"></th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $formation) : ?>
                        <tr>
                          <td style="color: #7096FF;"><?php print($formation['FILIERE']); ?></td>
                          <td>
                          <?php
                              switch ($formation['TYPE_FORM']) {
                                case 1:
                                  echo '<p class = "status status-paid">Cycle</p>';
                                  break;
                                case 2:
                                  echo '<p class = "status status-pending">MST</p>';
                                  break;
                                case 0:
                                  echo '<p class = "status status-unpaid">LST</p>';
                                  break;
                                
                              }
                            ?>
                          </td>
                          <td ><?php print($formation['NOM_DEPART']); ?></td>
                          <td><?php print($formation['NOM_ENS']); ?></td>
                          <td style="text-align: end; ">
                            <i style="margin-right: 20px;"><img src="icons/businessman.png" alt="" data-bs-toggle="modal" data-bs-target="#resp_modif<?php print($formation['ID_FORM']); ?>"></i>
                            <i><img src="icons/rubbish-bin.png" alt=""></i>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                          <th scope="col">Formation</th>
                          <th scope="col">Type</th>
                          <th scope="col">Departement</th>
                          <th scope="col">Responsable</th>
                          <th scope="col"></th>
                        </tr>
                      </tfoot>
                  </table>
              </div>
          </div>

          <!-- New formation -->
          <form action="back_end/Formation_gestion_Admin.php" method="post">
            <div class="modal fade"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
                <div class="modal-content" >
                  <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Nouveau Formation</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="max-height: 4500px; padding: 30px;">
                    
                      <label for="poste"><span>Nom :</span></label><br>
                      <input  class="inpstyl" type="text" name="nom_add"><br>

                      <div style="display: flex; justify-content: space-between; margin-top: 30px; margin-bottom: 25px;">

                          <div>
                              <label for="poste"><span>Abréviation :</span></label><br>
                              <input class="inpstyl" type="text" name="abv_add" id="inpp">
                          </div>
                        

                          <div>
                              <label for="poste"><span>Type :</span></label>
                              <select class="form-select" aria-label="Default select example" name="type_add" id="inpp">
                                  <option >Please select</option>
                                  <option value="1" >Cycle</option>
                                  <option value="2" >MST</option>
                                  <option value="0" >LST</option>
                                </select>
                              </div>
                          </div>
                    
                      
                          <div>
                              <label for="poste"><span>Département :</span></label>
                              <select class="form-select" aria-label="Default select example" onchange="DEP_ENS(this.value);" >
                                  <option >Please select</option>
                                  <?php foreach($deps as $dep): ?>
                                  <option value="<?php print($dep['ID_DEPART']);?>" ><?php print($dep['NOM_DEPART']);?></option>
                                  <?php endforeach; ?>
                                </select>
          
                          </div>
                          <?php foreach($deps as $dep): 
                            
                            /// *** Departements
                            $Smt = $bdd->prepare("SELECT * FROM enseignant WHERE ID_DEPART=? AND ID_ENS NOT IN (SELECT ID_ENS FROM formation) ");
                            $Smt -> execute(array($dep['ID_DEPART']));
                            $Liste_Ens = $Smt->fetchAll(PDO::FETCH_ASSOC);
                            $Smt->closeCursor();//vider le curseur (free) 
                            
                          ?>
                          <div id="resps<?php print($dep['ID_DEPART'])?>" class="responsables" style="display:none;">
                            <div class="flip" ><i><img src="icons/right-arrow.png" alt=""></i>Responsable</div>
                            <div class="panel">
                                <table class="hovtr">
                                    <?php  foreach($Liste_Ens as $Ens): ?>
                                    <tr style="height: 50px;">
                                      <td><?php print($Ens['NOM_ENS']); ?></td>
                                      <td><?php print($Ens['PRENOM_ENS']); ?></td>
                                      <td style="text-align: end;"><input class="form-check-input" type="radio" name="resp_add" value="<?php print($Ens['ID_ENS']); ?>" id="flexCheckDefault"></td>
                                    </tr>
                                    <?php endforeach; ?>
                                  </table>
                            </div>
                          </div>
                          
                          <?php endforeach; ?>
                    
                </div>
                  
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </div>
              </div>
            </div>
          </form>


          <!-- Responsable modification -->
          <?php foreach($rows as $formation) : 
              
              /// *** Enseignants de cette departement
              $Smt = $bdd->prepare("SELECT * FROM enseignant e,enseigner eg WHERE e.ID_ENS=eg.ID_ENS AND eg.ID_FORM=? AND e.ID_ENS not in (SELECT ID_ENS FROM formation) ");
              $Smt -> execute(array($formation['ID_FORM']));
              $Liste_Ens = $Smt -> fetchAll(PDO::FETCH_ASSOC);
              $Smt->closeCursor();//vider le curseur (free) 

          ?>
          
          <form action="back_end/Formation_gestion_Admin.php" method="post">
            <div class="modal fade"  id="resp_modif<?php print($formation['ID_FORM']); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
                <div class="modal-content" >
                  <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Enseignants</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="max-height: 300px; padding: 35px;">
                    <table class="hovtr">
                    <?php foreach($Liste_Ens as $Ens){ ?>
                      <tr style="height: 50px;">
                        <td><?php print($Ens['NOM_ENS']); ?></td>
                        <td><?php print($Ens['PRENOM_ENS']); ?></td>
                        <td style="text-align: end;"><input class="form-check-input" type="radio" name="resp_modif" id="flexRadioDefault1" value=<?php print($Ens['ID_ENS'] ); ?> required></td>
                      </tr>
                      <?php } ?>
                    </table>
                </div>
                  
                  <div class="modal-footer">
                    <input type="hidden" name="form_modif" value="<?php print($formation['ID_FORM']); ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <?php endforeach; ?>
          

          
          



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

<script>

  
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
        case 'Type':
          $(this).html('<select  id="table-filter1" class="form-select select" ><option value="">Choix de TYPE</option><option value="LST">LST</option><option value="MST">MST</option><option value="Cycle">Cycle</option></select>');
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
    
    }
    )

    let flips = document.querySelectorAll('.flip');
    for(var i=0 ; i<flips.length ; i++){
      flips[i].onclick=()=>{
        $(".panel").slideToggle("slow");
      }
    }



function DEP_ENS(id)
{
  
  //alert(resps);
  document.getElementById('resps'+id).style.display="block";
  
  let resps = document.querySelectorAll('.responsables');
  resps.forEach((resp)=>{
    
    if(resp.id != ('resps'+id) )
        resp.style.display="none";
  })

  
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
