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
  
        
        $Smt = $bdd->prepare("SELECT * FROM departement ");
        $Smt -> execute();
	    $Deps = $Smt->fetchAll(PDO::FETCH_ASSOC);
        $Smt->closeCursor();//vider le curseur (free) 
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
    <title>Départements</title>
    
    
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
                <a class="nav-link navlink " href="Liste_Formations_Admin.php">Formations</a>
              </li>
              
              <li class="nav-item underline">
                <a class="nav-link navlink" href="Liste_Enseignants_Admin.php">Enseignants</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink " href="Liste_Entreprises_Admin.php">Entreprises</a>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink active_link_color" href="Liste_Departement_Admin.php">Départements</a><span class="active_link_line"></span>
              </li>
            </ul>
            <div class="" style="position: fixed; margin-left: 44%;">
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
      <div class="" style="margin-top: 150px;">
        


        
         


          <div class="row" >
            <div class="col-12 col-md-8 pub_col">

                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Liste des Départements</h4> 
                        <i><img src="icons/plus.png" alt="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></i>
                  </div>
                  

                <table class="table" id="Table_Etu">
                    <thead>
                      <tr>
                         <th scope="col">Nom Départements</th>
                        <th scope="col"></th>
                        
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach($Deps as $Dep) : ?>
                        <tr>
                          <td ><?php print($Dep['NOM_DEPART']); ?></td>
                          <td style="text-align: end; ">
                            <i style="margin-right: 20px;"><img src="icons/edit.png" title="détail" alt="" data-bs-toggle="modal" data-bs-target="#Dep<?php print($Dep['ID_DEPART']); ?>"></i>
                            
                          </td>
                        </tr>
                        <?php endforeach; ?>
                    <tfoot>
                        <tr>
                          <th scope="col">Nom Départements</th>
                          <th scope="col"></th>
                        </tr>
                    </tfoot>

                    </tbody>
                    
                  </table>
              </div>
          </div>

          <!-- Add -->
          <form action="back_end/Departement_gestion_Admin.php" method="post">
           <div class="modal fade"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
              <div class="modal-content" >
                <div class="modal-header">
                  <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Nouvelle Depeignant</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 4500px; padding: 30px;">
                  
                    <label for="nom_add"><span>Nom Département :</span></label><br>
                    <input  class="inpstyl" type="text" name="nom_add" id="nom_add" required><br><br><br>
              </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
              </div>
            </div>
          </div>
          </form>

          <!-- Modification -->
          <?php foreach($Deps as $Dep_Modif) : ?>
          <form action="back_end/Departement_gestion_Admin.php" method="post">
            <div class="modal fade"  id="Dep<?php print($Dep_Modif['ID_DEPART']); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
                <div class="modal-content" >
                  <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Modifier Depeignant</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="max-height: 4500px; padding: 30px;">
                    
                      <label for="nom_modif"><span>Nom :</span></label><br>
                      <input  class="inpstyl" type="text" name="nom_modif" id="nom_modif" value="<?php print($Dep_Modif['NOM_DEPART']); ?>" ><br><br><br>


                </div>
                  
                  <div class="modal-footer">
                    <input type="hidden" name="id_modif" value="<?php print($Dep_Modif['ID_DEPART']); ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <?php endforeach ?>



         
          

          
          



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
        case 'Formation':
          $(this).html('<select  id="table-filter1" class="form-select select" ><option value="">Choix de FORMATION</option><option value="MQSE">MQSE</option><option value="IRM">IRM</option><option value="ILISI">ILISI</option></select>');
          break;
        case 'Type':
          $(this).html('<select  id="table-filter1" class="form-select select" ><option value="">Choix de TYPE</option><option value="LST">LST</option><option value="MST">MST</option><option value="Cycle">Cycle</option></select>');
          break;
        case 'Départements':
          $(this).html('<select  id="table-filter1" class="form-select DEPARTEMENT" ><option value="">Choix de TYPE</option><option value="INFO">INFO</option><option value="ELECTRIQUE">ELECTRIQUE</option><option value="MATH">MATH</option></select>');
          break;
        default:
        $(this).html('<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="' + title + '" />');
          break;
      }
    }
    
    });

    dataTable.columns().every(function () {
        var dataTableColumn = this;

        $(this.footer()).find('input').on('keyup change', function () {
            dataTableColumn.search(this.value).draw();
        });
    });

    // search style class
    $('.dataTables_filter').addClass('rounded search_custom');
    
    }
    )

    $(document).ready(function(){
  $("#flip").click(function(){
    $("#panel").slideToggle("slow");
  });
});

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
