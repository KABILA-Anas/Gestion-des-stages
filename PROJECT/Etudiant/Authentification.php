<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'authentifier</title>
</head>
<body>

    <form action="Authentification.php" method="post">
        Login:<input type="text" name="login_etu"><br>
        password:<input type="password" name="pass_etu"><br>
        <input type="submit">
    </form>
    
    <?php include "Connexion.php";
    
    session_start();
    if(isset($_POST['login_etu']) && isset($_POST['pass_etu']))
    {
        $L = $_POST['login_etu'];
        $P = $_POST['pass_etu'];
        
        $sql ="SELECT * FROM etudiant WHERE login_etu='$L' AND pass_etu='$P'";
                
        $req =$bdd->query($sql);

        $result = $req->fetch(PDO::FETCH_ASSOC);
        if(!empty($result))
        {
            $_SESSION['Etu']=$result['ID_ETU'];
            header('location:Find_Offre_Etu.php');
        }

    }
    
    
    ?>
</body>
</html>