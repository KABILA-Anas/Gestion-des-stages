<?php
	ob_start();
	session_start();
	require('connexion.php');


?>

<?php

	function get_user_id_type_mainPage($table_name,$id_table,$main_page,$user_type,$name,$pass)
	{
		require('connexion.php');
		$Smt = $bdd->prepare("SELECT * FROM $table_name as x, users u WHERE x.ID_USER=u.ID_USER AND u.LOGIN=? AND u.PASSWORD=? AND u.ACTIVE=1");
		$Smt -> execute(array($name,$pass));
		$rows = $Smt -> fetch();
		$Smt->closeCursor();//vider le curseur (free)
		// var_dump($name);
		// var_dump($rows);
		$user_name = NULL;

		if( $table_name == 'etudiant')
		{
			$user_name = array('user_firstname'=>$rows['NOM_ETU'],'user_lastname'=>$rows['PRENOM_ETU']);
		}
		else if( $table_name == 'formation' )
		{
			$Smt = $bdd->prepare("SELECT NOM_ENS, PRENOM_ENS FROM enseignant e, formation f WHERE e.ID_ENS=f.ID_ENS AND f.ID_FORM=?");
			$Smt -> execute(array($rows['ID_FORM']));
			$row = $Smt -> fetch();
			$user_name = array("user_firstname"=>$row['NOM_ENS'],"user_lastname"=>$row['PRENOM_ENS']);
		}

		$Smt->closeCursor();//vider le curseur (free)
		$results = array($rows[$id_table],$main_page,$user_type,$rows['PICTURE'],$user_name,$rows['VERIFIED']);//array contains id and main page and user type

		var_dump($results);
		return $results;
	}



	if( !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['type_user']) )
	{
		echo "test2";


		$type_user = $_POST['type_user'];

		if ($type_user == "Etudiant")
		{
			$result = get_user_id_type_mainPage('etudiant','ID_ETU',
												'Find_Offre_Etu.php','Etudiant',htmlspecialchars($_POST['username']),htmlspecialchars($_POST['password']));
			

		}
		else if ($type_user == "Responsable")
		{
			$result = get_user_id_type_mainPage('formation','ID_FORM',
										'Liste_Etudiant_Resp.php','Responsable',htmlspecialchars($_POST['username']),htmlspecialchars($_POST['password']));
		}
		else if ($type_user == "Admin")
		{
			$result = get_user_id_type_mainPage('admin','ID_ADMIN',
										'Liste_Formations_Admin.php','Admin',htmlspecialchars($_POST['username']),htmlspecialchars($_POST['password']));
		}

		var_dump($result);


		if( $result[0] != NULL )
		{
			

			if( $result[5] == 0 )
			{
				echo "asdasd";
				$_SESSION['error'] = '<div class="alert alert-warning" role="alert">
											Your account is not yet verrified !
										</div>';
				header('location: ../login.php');
		 		exit(0);
			}

			$_SESSION['user_id'] = $result[0];
			$_SESSION['main_page'] = $result[1];
			$_SESSION['user_type'] = $result[2];
			$_SESSION['user_pdp'] = strchr($result[3],'uploads') ;


			//echo "<br><br><br><br>";
			//var_dump($result[4]);
			if($result[4])
				$_SESSION['user_name'] = array('user_firstname'=>$result[4]['user_firstname'],'user_lastname'=>$result[4]['user_lastname']);;

			//echo "<br><br><br><br>";
			//var_dump($_SESSION['user_name']['user_firstname']);

		 	if(isset($_SESSION['error']))
		 	{
		 		unset($_SESSION['error']);
		 	}

		 	if(!isset($_SESSION['page']))
		 		header('location:../'.$_SESSION['main_page']);
		 	else
		 		header('location:'.$_SESSION['page']);

		}
		else
		{
		 	$_SESSION['error'] = '<div class="alert alert-danger" role="alert">
			 							Incorrect login !
									</div>';
		 	header('location: ../login.php');
		 	echo "alo";
		}
		
	}
?>