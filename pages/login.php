<?php
  ob_start();
  session_start();
  require('back_end/connexion.php');
  if( !empty($_SESSION['main_page']) )
  {
    header('location:'.$_SESSION['main_page']);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
</head>
<body>

  
    
    <div class="container-fluid " style="height: 100%;">
        <div class="row" style="height: 100%;">

          <div class="col-md-6 col-sm-12 elm d-flex" style="justify-content: center;"> 
            
            <div class="logfrm">
              <div class="title">
                FSTAGE
              </div>
              <div class="titgrp"><span class="title1">Welcome Again!</span >  <br><span class="title2"> Login to continue to fstage </span></div>
              
              <br>


              <form action="back_end/authentification.php" method="post">
                <div>
                  <label class="inptit" for="username">Username</label><br>
                  <input type="text" id="username" name="username" style="padding-left:1rem;" required><br>
                  <div style="margin-top: 10px;">
                    <label class="inptit" for="password">Password</label><br>
                    <input type="password" id="password" name="password" style="padding-left:1rem;" required>
                  </div>
                </div><br>
                <?php 
                  if( !empty($_SESSION['error']))
                  {
                    echo "<p style='display: flex; justify-content: center; color: red;'> ".$_SESSION['error']."</p>" ;		
                  }
                ?>
                <br>
  
                <div class="select">
                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="type_user" >
                    <option value="Etudiant" selected>Etudiant</option>
                    <option value="Responsable">Responsable</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>
                  <button type="submit">Log In</button>
              </form>

                <div style="text-align: center; margin-top: 7px;">
                  <span class="qst">You don’t have an account ? </span><span class="link"><a href="signup.php">Sign up</a> </span><br>
                  <span class="link"><a href="">Forgot Password ?</a> </span>
                </div>
              

            </div>

          </div>

          <div class="col-6 d-none d-md-block elm pic bg-image">
            <div class="overlay"></div>
          </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
</body>
</html>