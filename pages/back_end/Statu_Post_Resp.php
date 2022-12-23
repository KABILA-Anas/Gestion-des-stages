<?php 
    ob_start();
    session_start();
    require('connexion.php');
    
    if( empty($_SESSION['user_id']) || empty($_SESSION['user_type']) )
        header('location:../login.php');

    else{

        if( $_SESSION['user_type'] == "Responsable" )
        {
            if((isset($_POST['Post_Retenue']) || isset($_POST['Post_Non_Retenue'])) && (isset($_POST['id_etu'])) )
            {
                
                $Etu=$_POST['id_etu'];
                
                $timestamp = time()+60*60;
                $curdate = date("Y-m-d h:i:s",$timestamp);
    
                if(isset($_POST['Post_Non_Retenue'])){
                    
                    $Offre_ID = $_POST['Post_Non_Retenue'] ;  
                    $Smt=$bdd->prepare("UPDATE postuler SET STATU=?,DATEREPONS=? WHERE ID_ETU=? AND ID_OFFRE=? ");
                    $Smt->execute(array('Non Retenue',$curdate,$Etu,$Offre_ID));

                }else if(isset($_POST['Post_Retenue'])){
                    
                    $Offre_ID = $_POST['Post_Retenue'] ;      
                    
                    /// ***Nbr de Candidats
                    $Smt1 =$bdd->prepare("SELECT o.NBRCANDIDAT-count(*) AS NbrReste FROM postuler p,offre O WHERE o.ID_OFFRE=p.ID_OFFRE AND o.ID_OFFRE=? AND o.STATUOFFRE=?  AND (p.STATU=? OR p.STATU=? OR p.STATU=?)");
                    $Smt1->execute(array($Offre_ID,'Nouveau','Retenue','Acceptée','Fini'));
                    $row1 = $Smt1->fetch(PDO::FETCH_ASSOC);
                    
                    if(!empty($row1))
                    {
                        
                        $NbrReste = $row1['NbrReste'];

                        if($NbrReste == 1)
                        {
                            $Smt2=$bdd->prepare("UPDATE offre SET STATUOFFRE=? WHERE ID_OFFRE=? ");
                            $Smt2->execute(array('Completée',$Offre_ID) );
                        }
                    }


                    $Smt=$bdd->prepare("UPDATE postuler SET STATU=?,DATEREPONS=? WHERE ID_ETU=? AND ID_OFFRE=? ");
                    $Smt->execute(array('Retenue',$curdate,$Etu,$Offre_ID));

                
                }
                
                
                header('location:../Soumis_Resp.php?id_etu='.$Etu);
            }
        }
        else
        {
            header('location:../'.$_SESSION['main_page']);
        }
    }

?>