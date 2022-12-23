<?php
  ob_start();
  session_start();
  if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
  {
    $_SESSION['page'] = $_SERVER['REQUEST_URI'];
    header('location: login.php');
  }

  if($_SESSION['user_type'] == "Etudiant")
  {

      require('back_end/connexion.php');
      $id_etu = $_SESSION['user_id'];
           
      $Smt = $bdd->prepare("SELECT * FROM etudiant e, formation f,Users u WHERE e.ID_FORM=f.ID_FORM AND e.ID_USER=u.ID_USER AND ID_ETU=?");
      $Smt -> execute(array($id_etu));
      $Data = $Smt -> fetch();
      $Smt->closeCursor();//vider le curseur (free)
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil_etud.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>profile</title>

</head>
<body>
      
    <nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed" style="z-index: 9; width: 100%; top: 0; background-color: #F2F5F8 !important;">
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
                <?php 
                /// ***Nombre de soumissions
              
                $Smt =$bdd->prepare("SELECT count(e.ID_ETU) as Nbr_soums FROM etudiant e,postuler p WHERE e.ID_ETU = p.ID_ETU AND e.ID_ETU=? AND p.STATU=? ");
                $Smt->execute(array($id_etu,'Retenue'));
                $row = $Smt->fetch(PDO::FETCH_ASSOC);
                if(!empty($row)){ if($row['Nbr_soums']){ ?><span class="icon-button__badge"><?php $Nb_rtn =$row['Nbr_soums'];if($Nb_rtn)print($Nb_rtn);}} ?></span>
              </li>
              <li class="nav-item underline">
                <a class="nav-link navlink" href="MeStages_Etu.php">Mes Stages</a>
              </li>
            </ul>
            <div class="" style="position: fixed; margin-left: 44%;">
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

    <div class="container-fluid ">
      <div class="" style="margin-top: 70px; padding: 30px;">
          <div class="row" >
            <div class="col-12 col-md-6 elm title">
                        Informations generale
              </div>
          </div>

          <div class="row" >
            <div class="col-12 col-md-12 elm colpadd" style="padding-left: 8%; padding-right: 8%;">
              <div class="cov">
              <div class="pic">
                
                <div>
                <form action="back_end/Image_Upload_Etu.php" method="post" id="upload_image" enctype="multipart/form-data">
                  <div class="avatar-upload">
                    <div class="avatar-edit">
                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" name="picture" />
                        <label for="imageUpload"></label>
                    </div>
                    <div class="avatar-preview">
                        <?php if(!empty($Data['PICTURE']) ){ ?>
                        <div id="imagePreview" style="background-image: url('<?php print(strchr($Data['PICTURE'],'uploads'));?>');"></div>
                        <?php }else{?>
                        <div id="imagePreview" style="background-image: url('icons/avatar.png');"></div><?php } ?>
                        
                    </div>
                </div>

                </div>
                  </form>

                <div class="nomdiv">
                  <span class="nom"><?php echo $Data['NOM_ETU'].' '.$Data['PRENOM_ETU'];  ?></span><br><a href="" style="text-decoration: none;"><span class="reperror">Report error</span></a>
                </div>

                <div class="twobutt">
                  
                <form action="back_end/Cv_Upload_Etu.php" method="post" style="display:inline;" id="upload_form" enctype="multipart/form-data">
                  <input type="hidden" name="id_etu" value="<?php print($Data['ID_ETU']); ?>">  
                  <button type = "button" class = "btn-warnin" style="margin-left: 1%; margin-bottom: 10px;">
                    <i class = "fa fa-upload"></i> Upload File
                    <input type="file" name="cv" id="upload_file" >
                    </button>
                  </form>
                  
                  <form action="back_end/PDFDownLoad.php" method="post" style="display:inline;" >
                    <button type = "submit" class = "btn-warnin" <?php if(!$Data['CV']){ ?> disabled <?php }?> >
                      <img src="icons/download2.png" title="Download CV"><span> Mon Cv</span>
                    </button>
                    <input type="hidden" name="cv" value="<?php print($Data['CV']); ?>">
                  </form>
                </div>
              </div>

              <div class="nomdiv2">
                <span class="nom"><?php echo $Data['NOM_ETU'].' '.$Data['PRENOM_ETU'];  ?></span><br><a href="" style="text-decoration: none;"><span class="reperror">Report error</span></a>
              </div>

              <div class="desc">
                <p>Etudiant en <span>
                  <?php 
                    switch ( $Data['TYPE_FORM'] ) {
                      case 1:
                        switch ( $Data['NIVEAU'] )
                        {
                          case 1:
                            echo "première année";
                            break;
                          case 2:
                            echo "deuxième année";
                            break;
                          case 3:
                            echo "troisième année";
                            break;
                        }
                        echo "</span> cycle d'ingénieur<br>";
                        break;
                      case 2:
                        switch ( $Data['NIVEAU'] )
                        {
                          case 1:
                            echo "première année";
                            break;
                          case 2:
                            echo "deuxième année";
                            break;
                        }
                        echo "</span> master<br>";
                        break;

                      default:
                      echo "</span> licence<br>";
                        break;
                    }
                  ?>
                  (
                    <?php
                      switch ($Data['FILIERE']) {
                        case 'ILISI':
                          echo "Ingénieurie Logiciel et Intégration des Systèmes Informatiques";
                          break;
                        case 'IRM':
                          echo "Informatique, Réseau et Multimédia";
                          break;
                        case 'MQSE':
                          echo "Management de la Qualité, de la Sécurité et de l’Environnement";
                          break;
                      }
                    ?>
                  )</p>
              </div>
            </div><br>
              

              <div style="display: flex; justify-content: center; margin-left: -2%; margin-right: -2%;"><span class="separ"></span></div><br>

                  <div class="twoinp">
                    <div id="fir1">
                    <label for="">CIN</label><br>
                    <input type="text" class="inpp" name="cin_etu" value="<?php echo $Data['CIN_ETU'] ?>" disabled>
                  </div>
                  <div id="fir">
                  <label  for="">CNE</label><br>
                  <input type="text" class="inpp" name="cne" value="<?php echo $Data['CNE'] ?>" disabled>
                  </div>
                  </div>




                        <div class="twoinp">
                          <div id="sec">
                          <label for="">Date de Naissance</label><br>
                          <?php
                          $timestamp = strtotime($Data['DATENAISS_ETU']);

                          $day = date('d', $timestamp);
                          $month = date('m', $timestamp);
                          $year = date('Y', $timestamp);
                          ?>
                          <input class="inpp1" type="text" name="day" value="<?php echo $day ?>" disabled><input class="inpp2" type="text" name="month" value="<?php echo $month ?>" disabled><input class="inpp3" type="text" name="year" value="<?php echo $year ?>" disabled>
                          <?php  ?>
                          </div>
                      
                        </div>

              </div>
          </div><br>

          <div class="row" >
            <div class="col-12 col-md-6 elm title">
                        Contact
              </div>
          </div>

          <div class="row" >
            <div class="col-12 col-md-12 elm colpadd" style="padding-left: 8%; padding-right: 8%;">

              <div class="twoinp">
                <div id="fir">
                 <label for="">Email</label><br>
                 <input type="text" class="inpp" name="email_etu" value="<?php echo $Data['EMAIL_ETU'] ?>" disabled>
               </div>
              <div id="fir">
               <label  for="">Phone</label><br>
               <input type="text" class="inpp" name="numtel_etu" value="<?php echo $Data['NUMTEL_ETU'] ?>" disabled>
              </div>
             </div>
           

           

                    <div class="twoinp">
                      <div id="sec">
                      <label for="">Adresse</label><br>
                      <input type="text" class="inpp" name="adresse_etu" value="<?php echo $Data['ADRESSE_ETU'] ?>" disabled>
                    </div>
                    <div id="sec">
                    <label  for="">Ville</label><br>
                    <input type="text" class="inpp" value="<?php echo $Data['VILLE_ETU'] ?>" disabled>
                    </div>
                    </div>

                    <button class="update" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Update Profile</button>
                  </div>

                  
            
          </div>
        </div>
        </div>

        <div class="modal fade"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
            <div class="modal-content" >
              <div class="modal-header">
                <h3 class="modal-title" id="staticBackdropLabel" style="color: #7096FF; font-weight: 600;">Nouveau Formation</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="form_updateProfile" action="back_end/Update_profile.php" method="post">
              <div class="modal-body" style="max-height: 4500px; padding: 30px;">
                
                 
                 
                 
                  
                     

                      <div id="flip1"><i  style="margin-right: 5px;"><img id="rt1" src="icons/right-arrow.png" alt="" ></i>Phone</div>
                      <div id="panel1">
                          <div class="inputBox">
                            <input type="text" name="numtel_etu" id="numtel_etu" value="<?php echo $Data['NUMTEL_ETU'] ?>" required>
                            <span>Phone</span>
                          </div>
                      </div>
                      <div id="flip2"><i  style="margin-right: 5px;"><img id="rt2" src="icons/right-arrow.png" alt="" ></i>Adresse</div>
                      <div id="panel2">
                        <div class="inputBox">
                          <input type="text" name="adresse_etu" id="adresse_etu" value="<?php echo $Data['ADRESSE_ETU'] ?>" required>
                          <span>Adresse</span>
                        </div>
                      </div>
                      <div id="flip3"><i  style="margin-right: 5px;"><img id="rt3" src="icons/right-arrow.png" alt="" ></i>Ville</div>
                      <div id="panel3">
                        <div class="inputBox">
                          <input type="text" name="ville_etu" id="ville_etu" value="<?php echo $Data['VILLE_ETU'] ?>" required>
                          <span>Ville</span>
                        </div>
                      </div>
                      <div id="flip4"><i  style="margin-right: 5px;"><img id="rt4" src="icons/right-arrow.png" alt="" ></i>Password</div>
                      <div id="panel4">
                        <div class="inputBox">
                          <input type="password" name="password" id="password" required>
                          <span id="pass1_msg">Password</span>
                        </div>
                        <div class="inputBox">
                          <input type="password" name="confirm_password" id="confirm_password" required>
                          <span>CONFIRM Password</span>
                        </div>
                      </div>
                      
                 
            </div>
              
              <div class="modal-footer">
                <input type="hidden" name="id_etu" value="<?php echo $_SESSION['user_id'] ?>" >
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" id="submit">Enregistrer</button>
              </div>
                    </form>
            </div>
          </div>
        </div>
          
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
      
      
      </script>

<script src="profil_etud.js"></script>
      

<script>
  
  $(document).ready(function(){
  $("#flip1").click(function(){
    $("#panel1").slideToggle("slow");
    if ($("#panel2").is(":visible")) { 
      $("#panel2").slideToggle("slow");
      $("#rt2").toggleClass("rot");
} 

if ($("#panel3").is(":visible")) { 
      $("#panel3").slideToggle("slow");
      $("#rt3").toggleClass("rot");
} 

if ($("#panel4").is(":visible")) { 
      $("#panel4").slideToggle("slow");
      $("#rt4").toggleClass("rot");
} 

    $("#rt1").toggleClass("rot");
  });
});

$(document).ready(function(){
  $("#flip2").click(function(){
    $("#panel2").slideToggle("slow");
    $("#rt2").toggleClass("rot");
    if ($("#panel1").is(":visible")) { 
      $("#panel1").slideToggle("slow");
      $("#rt1").toggleClass("rot");
} 

if ($("#panel3").is(":visible")) { 
      $("#panel3").slideToggle("slow");
      $("#rt3").toggleClass("rot");
} 

if ($("#panel4").is(":visible")) { 
      $("#panel4").slideToggle("slow");
      $("#rt4").toggleClass("rot");
} 
  });
});

$(document).ready(function(){
  $("#flip3").click(function(){
    $("#panel3").slideToggle("slow");
    $("#rt3").toggleClass("rot");
    if ($("#panel2").is(":visible")) { 
      $("#panel2").slideToggle("slow");
      $("#rt2").toggleClass("rot");
} 

if ($("#panel1").is(":visible")) { 
      $("#panel1").slideToggle("slow");
      $("#rt1").toggleClass("rot");
} 

if ($("#panel4").is(":visible")) { 
      $("#panel4").slideToggle("slow");
      $("#rt4").toggleClass("rot");
} 
  });
});

$(document).ready(function(){
  $("#flip4").click(function(){
    $("#panel4").slideToggle("slow");
    $("#rt4").toggleClass("rot");
    if ($("#panel2").is(":visible")) { 
      $("#panel2").slideToggle("slow");
      $("#rt2").toggleClass("rot");
} 

if ($("#panel3").is(":visible")) { 
      $("#panel3").slideToggle("slow");
      $("#rt3").toggleClass("rot");
} 

if ($("#panel1").is(":visible")) { 
      $("#panel1").slideToggle("slow");
      $("#rt1").toggleClass("rot");
} 
  });
});


    $(document).ready(function(){
  $("#flip").click(function(){
    $("#panel").slideToggle("slow");
  });
});

$('#upload_file').change(function() {
  $('#upload_form').submit();
});

/** verification des donnees */

var submit = (document).getElementById('submit');

function enable_button(button){
                button.disabled = false;
            }

function disable_button(button){
                button.disabled = true;
            }

function verifier(regularExp,inputValue,class_name)
            {
                
                if(regularExp.test(inputValue))
                {
                    $(class_name).css(
                    {
                        "border-color" : "#54BE4A"                           
                    })
                        enable_button(submit);
                    return true;
                }
                
                $(class_name).css(
                {
                    "border-color" : "red"
                });

                    disable_button(submit);
                
                return false;    
            }


            $("#form_updateProfile").on('focus keyup mousedown',function()
            {
                // body...
                //var regEx_name = /^[A-Z][a-zA-Z]{1,20}( [A-Z][a-zA-Z]{0,20})*$/;
                var regEx_month = /(0[1-9]|1[012])$/;
                var regEx_day = /(0[1-9]|1[0-9]|2[0-9]|3[01])$/;
                var regEx_year = /(19[0-9][0-9]|2[0-9][0-9][0-9])$/;
                var regEx_cne = /(^[a-zA-Z][0-9]{9})$/;
                //var regEx_cin = /(^[a-zA-Z][a-zA-Z][0-9]{5})$/;
                var regEx_mail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var regEx_pass = /.{8,}$/;
                var regEx_phone = /[^a-z][^A-Z]$/;
                var regEx_nonNull = /.+$/;


                var adresse = (document).getElementById('adresse_etu');
                var ville = (document).getElementById('ville_etu');
                var phone = (document).getElementById('numtel_etu');
                var pass1 = (document).getElementById('password');
                var pass2 = (document).getElementById('confirm_password');

                

                


                verifier(regEx_phone,phone.value,phone);

                verifier(regEx_nonNull,ville.value,ville);

                verifier(regEx_nonNull,adresse.value,adresse);



                    if(verifier(regEx_pass,pass1.value,pass1))
                    {
                      $('#pass1_msg').text('PASSWORD');
                      $('#pass1_msg').css({
                        "color" : "#54BE4A"
                      });
                    }
                        
                    else
                    {
                      $('#pass1_msg').text('more than 8 chars');
                      $('#pass1_msg').css({
                        "color" : "red"
                      });
                    }
                        
                    if((pass1.value == pass2.value) && verifier(regEx_pass,pass2.value,pass2))
                    {
                        $(pass2).css(
                        {
                            "border-color" : "#54BE4A"
                            
                        })
                        enable_button(submit);
                    }
                    else
                    {
                        $(pass2).css(
                        {
                            "border-color" : "red"
                        });
                        disable_button(submit);
                    }
                
                
                
 
            })


            $('#imageUpload').on('change', function () {
              document.getElementById("upload_image").submit();

            })

</script>


    
</body>
</html>
<?php
  
  }else
  {
    echo "<h1> ERROR 301:</h1> <p>Unauthorized Access !</p>";
  }

?>
