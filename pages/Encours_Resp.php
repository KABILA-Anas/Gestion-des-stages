<?php 
  ob_start();
  session_start();
  if(empty($_SESSION['user_id']) || empty($_SESSION['user_type']))
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location:login.php');
  }
  
  if( $_SESSION['user_type'] == "Responsable")
  {
    
    require("back_end/connexion.php");
    $id_form = $_SESSION['user_id'];
    /// *** Type formation
    $Smt = $bdd->prepare("SELECT TYPE_FORM FROM formation WHERE ID_FORM=?");
    $Smt -> execute(array($id_form));
    $row = $Smt->fetch(PDO::FETCH_ASSOC);
    $type_form = $row['TYPE_FORM'];
    
    
    if(isset($_GET['id_etu']))
    {

      $id_etu = $_GET['id_etu'];
      
      /// *** Test access
      $Smt =$bdd->prepare("SELECT ID_FORM FROM etudiant WHERE ID_ETU=?");
      $Smt->execute(array($id_etu));
      $etu_form = $Smt->fetch(PDO::FETCH_ASSOC);
      $Smt->closeCursor();//vider le curseur (free) 
      
      if($etu_form['ID_FORM'] != $_SESSION['user_id'] )
          exit("You're not allowed to access for this student");

      ///Stage
      $sql1 = "SELECT * FROM entreprise ent,offre o,stage s,etudiant etu  WHERE ent.ID_ENTREP =o.ID_ENTREP AND o.ID_OFFRE=s.ID_OFFRE AND s.ID_ETU = etu.ID_ETU AND etu.ID_ETU='$id_etu' AND s.STATUSTG='1' ";
      $req1 =$bdd->query($sql1);
      $result1 = $req1->fetch(PDO::FETCH_ASSOC);
      
      if( !empty($result1['ID_STAGE']) )
      {
        $id_stage = $result1['ID_STAGE'];
      
        ///L'Encadrant
        $sql2 = "SELECT e.ID_ENS,e.NOM_ENS,e.PRENOM_ENS,s.NOTENCAD FROM enseignant e,stage s WHERE s.ID_ENS = e.ID_ENS AND s.ID_STAGE = '$id_stage' ";
        $req2 =$bdd->query($sql2);
        $result2 = $req2->fetch(PDO::FETCH_ASSOC);
  
        ///Jury
        $sql3 = "SELECT e.ID_ENS,e.NOM_ENS,e.PRENOM_ENS,j.NOTE FROM enseignant e,juri j WHERE j.ID_ENS = e.ID_ENS AND j.ID_STAGE = '$id_stage' ";
        $req3 =$bdd->query($sql3);
        $result3 = $req3->fetchAll(PDO::FETCH_ASSOC);
      }
        


      /// Enseignants d'etre encadrants
      $Smt =$bdd->prepare("SELECT e.ID_ENS,e.NOM_ENS,e.PRENOM_ENS FROM enseignant e,enseigner eg WHERE e.ID_ENS=eg.ID_ENS AND  e.ACTIVE_ENS='1' AND eg.ID_FORM=(SELECT ID_FORM FROM etudiant WHERE ID_ETU=?)");
      $Smt->execute(array($id_etu));
      $rows = $Smt->fetchAll(PDO::FETCH_ASSOC);
      ///Last visited page
      $_SESSION['Last_visite'] =$_SERVER['REQUEST_URI']; 

    

      
      

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ListeEtudiants.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Encours</title>
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
                $Smt->execute(array('0',$id_form));
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                if(!empty($row)){ if($row['Nbr_non_Verif']){ ?><span class="icon-button__badge"><?php $Nb_non_verif =$row['Nbr_non_Verif'];if($Nb_non_verif)print($Nb_non_verif);}} ?></span>
              </li>
            </ul>
            
            <div class="" style="position: fixed; margin-left: 47%;">
                  <a class="navbar-brand navt d-none d-lg-block" href="#"><img src="icons/weblog.png" alt="" width="150" height="35"></a>
            </div>
            <div class="navbar-nav ms-auto margin action" style="margin-right:2.5%;">
              
              <img class="profile" onclick="toggle1();" src="<?php if( !empty($_SESSION['user_pdp']) ) echo $_SESSION['user_pdp']; else echo 'icons/avatar.png'; ?>" alt="">
              
              <div class="menu" id="profile_pop" style="margin:5px;">
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
        


        <div class="row" style="background-color: #FFFEFB;">
            <div class="col-md-8 elm pub_col">

                <form action="Liste_Etudiant_Resp.php" method="post" id="form" >
                  <div class="tableHead" style="margin-bottom: 30px;">
                        <h4>Stage en cours</h4>
                  </div>
                </form>
                  
                <?php if(!empty($result1)){?>
                <table class="table">
                    <thead>
                      <tr>
                      <?php if( $type_form){ ?><th scope="col">N</th><?php } ?>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">CNE</th>
                        <th scope="col">Stage</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>

                        
                        <tr>
                        <?php if( $type_form){ ?><th scope="row" style="color: #7196FF"><?php print($result1['NIVEAU_STAGE'])?></th><?php } ?>
                          <td style="color: #616161;"><?php print($result1['NOM_ETU'])?></td>
                          <td style="color: #616161;"><?php print($result1['PRENOM_ETU'])?></td>
                          <td style="color: #7196FF;"><?php print($result1['CNE'])?></td>
                          <td style="color: #616161;"><?php print($result1['POSTE'])?>-<?php print($result1['NOM_ENTREP'])?></td>
                          <td class="opt">
                            <span id="option_pop" onclick="toggle2()">Options</span>
                            <div class="menu" id="mn1" >
            
                              <ul>
                                <li><img src="icons/loupe.png" alt="" ><a href="" data-bs-toggle="modal" data-bs-target="#stage_offre">Details</a> </li>
                                <li><img src="icons/teacher.png" alt=""><a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop4">Encadrant</a> </li>
                                <li><img src="icons/jury.png" alt=""><a href="Jury_Resp.php?id_stage=<?php print($result1['ID_STAGE']);?>">Jury</a> </li>
                                <li><img src="icons/certificate.png" alt=""><a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Notes</a> </li>
                                <li><img src="icons/application.png" alt=""><a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3">Rapport</a> </li>
                                <form action="back_end/CancelStage_Resp.php" method="post">
                                  <input type="hidden" name="id_stage" value="<?php print($result1['ID_STAGE']);?>">
                                  <li><img src="icons/cancel.png" alt=""><button type="submit" style="background:none; border:none;">Cancel</button></li>
                                </form>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <?php }
                        else
                        echo '<div class="alert alert-primary" role="alert" style="margin-top:5%;">
                        No data found !
                      </div>';?>
                    </tbody>
                  </table>
              </div>
          </div>
          



        </div>
        </div>
        
        <!-- Notes -->

        <form action="back_end/Notes_Stage_Resp.php" method="post">
          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Notes</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="padding: 16px;  ">
                  <h5 style="border-bottom: 1px solid #717171; color: #717171; font-weight: 600;">Encadrants</h5>
                  <table style="width: 100%; color: #616161;">
                    <tr style="height: 50px;">
                      <?php if(!empty($result2)){?>
                      <td><?php print($result2['NOM_ENS']);?></td>
                      <td><?php print($result2['PRENOM_ENS']);?></td>
                      <td style="text-align: end;">Note <input type="number" step="0.01" min="0" max="20" value="<?php print($result2['NOTENCAD']);?>" name="note_encad" style="width: 60px; margin-left: 5px; border: 1px solid #B3B3B3;"></td>
                      <?php }?>
                    </tr>
                    <tr style="height: 50px;">
                      <td colspan="2">Entreprise</td>
                  <td style="text-align: end;">Note <input type="number" step="0.01" min="0" max="20" value="<?php print($result1['NOTENCAD_ENTREP']);?>" name="note_entrep" style="width: 60px; margin-left: 5px; border: 1px solid #B3B3B3;"></td>
                    </tr>
                  </table>
                </div>
                
                <div style="padding: 16px;  ">
                  <h5 style="border-bottom: 1px solid #717171; color: #717171; font-weight: 600;">Jury</h5>
                  <table style="width: 100%; color: #616161;">
                      
                      <?php if(!empty($result3)){
                          foreach($result3 as $Jury):
                      ?>
                      <tr style="height: 50px;">
                        <td><?php print($Jury['NOM_ENS']);?></td>
                        <td><?php print($Jury['PRENOM_ENS']);?></td>
                        <td style="text-align: end;">Note <input type="number" step="0.01" min="0" max="20"  value ="<?php print($Jury['NOTE']);?>" name="notes_jury[]" style="width: 60px; margin-left: 5px; border: 1px solid #B3B3B3;"></td>
                      </tr>
                      <?php endforeach;}?>
                  </table>
                </div>
                <!-- stage id -->
                <input type='hidden' name='id_stage' value="<?php print($id_stage); ?>" />
                <!-- Jury array -->
                <input type='hidden' name='jury_array' value="<?php echo htmlentities(serialize($result3)); ?>" />
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <!-- RAPPORT -->
        <form action="back_end/Rapport_Stage_Resp.php" method="post" enctype="multipart/form-data">
          <div class="modal fade"  id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"  style="width: 400px;" >
                <div class="modal-content" >
                  <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Rapport</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="max-height: 300px;">
                        <div style="margin-left:130px">
                          <button type = "button" class = "btn-warnin">
                              <i class = "fa fa-upload"></i> Upload File
                                <input type="file" class="form-control" id="rapport" name="rapport" required >
                          </button>
                        </div>
                        <div style="display: flex;">
                          <h5 style="border-bottom: 1px solid #717171; color: #717171; font-weight: 600; margin-top: 25px; border-bottom: none; text-decoration: underline;">Mots clés :</h5>
                          <div id="inp" style="margin-top: 20px; margin-left: 20px;">
                              <input type="text" name='motscle[]' class="inp"><br>
                              <input type="text" name='motscle[]' class="inp"><button id="bt" class="todo-app-btn" onclick="add()"><i class="bi bi-plus-lg"></i> Add </button><br>
                          </div>
                      </div>
                </div>
                  
                  <div class="modal-footer">
                    <input type="hidden" name="id_stage" value="<?php print($id_stage); ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </div>
              </div>
            </div>
        </form>

        <!-- Encadrant -->
        <form action="back_end/Encadrant_Stage.php" method="post">
            <div class="modal fade"  id="staticBackdrop4" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
                <div class="modal-content" >
                  <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Enseignants</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="max-height: 300px;">
                    <table class="hovtr">
                      <?php
                          if(!empty($rows)){
                              foreach($rows as $Ens):
                          
                      ?>
                      <tr style="height: 50px;">
                        <td><?php print($Ens['NOM_ENS'])?></td>
                        <td><?php print($Ens['PRENOM_ENS'])?></td>
                        <td style="text-align: end;">
                          <input type="hidden" name="id_stage" value="<?php print($id_stage);?>">
                          <?php if(!empty($result2)){ if($result2['ID_ENS'] == $Ens['ID_ENS']){ ?>
                          <input class="form-check-input" type="radio" name='encadrant_stage' value="<?php print($Ens['ID_ENS']);?>" id="flexCheckDefault" checked>
                          <?php }else{ ?>
                            <input class="form-check-input" type="radio" name='encadrant_stage' value="<?php print($Ens['ID_ENS']);?>" id="flexCheckDefault">
                           <?php }}else{ ?> 
                            <input class="form-check-input" type="radio" name='encadrant_stage' value="<?php print($Ens['ID_ENS']);?>" id="flexCheckDefault">
                           <?php } ?>
                        </td>
                      </tr>
                      <?php endforeach;}?>
                      
                    </table>
                </div>
                  
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </div>
              </div>
            </div>
          </form>


          <!-- Detail -->
          <div class="modal fade"  id="stage_offre" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"  style="max-width:800px !important;" >
              <div class="modal-content" >
                <div class="modal-header">
                  <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Offre </h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                <div class="col-md-12 elm pub_col">
                      <div class="content">

                        <span class="poste" ><?php print($result1['POSTE'])?></span> <br><br>

                        <span class="ville" ><?php print($result1['NOM_ENTREP'])?> - <?php print($result1['VILLE'])?></span> <br>

                        <span class="duree" >(Durée <?php print($result1['DUREE']/30);?> months)</span> <br><br>

                        <div class="desc" >
                          <p style="white-space: pre-line"><?php print($result1['DESCRIP']);?></p>
                        </div>

                        <div>
                          <span class="time"> <img src="icons/time.png" alt=""> <?php print($result1['DATEFIN']);?> </span>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
      
      
      </script>
      
      <script>
        const profile_pop = document.getElementById("profile_pop");
        const option_pop = document.getElementById("mn1");

        // $(document).ready( function () {
        //   $('#profile_pop').on('click', function() {
        //     console.log('haha');
        //     this.toggleClass('active');
        //   } )
        // })

        function toggle1() {
          profile_pop.classList.toggle('active');
        }

        function toggle2() {
          option_pop.classList.toggle('active');
        }
        

        
        
        function add()
        {
            const inpt = document.createElement("input");
            const butt = document.createElement("button");
            const icn = document.createElement("i");
            const textnode = document.createTextNode("Add");
            const line = document.createElement("br");
            document.getElementById("bt").remove();
            butt.classList.add("todo-app-btn");
            butt.setAttribute("id", "bt");
            inpt.setAttribute('name','motscle[]');
            inpt.classList.add("inp");
            icn.classList.add("bi");
            icn.classList.add("bi-plus-lg");
            butt.appendChild(icn);
            butt.appendChild(textnode);
            butt.onclick = ()=>{
              add();
            }
            document.getElementById("inp").appendChild(inpt);
            document.getElementById("inp").appendChild(butt);
            document.getElementById("inp").appendChild(line);
        }
      </script>
    
</body>
</html>
<?php
    }else
    {
      echo "<h1>ERROR 301</h1><p>Pas de stage encours !</p>";
    }
}else
  {
    echo "<h1>ERROR 301</h1> <p>Unauthorized Access !</p>";
  }

?>
