<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'authentifier</title>
</head>
<body>

    <form action="Authentification_Resp.php" method="post">
        Login Resp:<input type="text" name="login_resp"><br>
        password Resp:<input type="password" name="pass_resp"><br>
        <input type="submit">
    </form>
    
    <?php include "Connexion.php";
    
    session_start();
    if(isset($_POST['login_resp']) && isset($_POST['pass_resp']))
    {
        $L = $_POST['login_resp'];
        $P = $_POST['pass_resp'];
        
        $sql ="SELECT * FROM formation WHERE login_resp='$L' AND pass_resp='$P'";
                
        $req =$bdd->query($sql);

        $result = $req->fetch(PDO::FETCH_ASSOC);
        if(!empty($result))
        {
            $_SESSION['Resp']=$result['ID_FORM'];
            header('location:Soumis_Resp.php');
        }

    }
    
    
    ?>
</body>
</html>