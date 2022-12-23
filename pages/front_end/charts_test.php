<?php
    require('../back_end/connexion.php');

    $Smt = $bdd->prepare("SELECT ID_FORM,count(*) as NBR_ETU FROM etudiant WHERE ID_ETU IN ( SELECT ID_ETU FROM postuler) GROUP BY ID_FORM");
	$Smt -> execute();
    $postule_etu = $Smt->fetchAll();
    $Smt->closeCursor();//vider le curseur (free)

    $Smt = $bdd->prepare("SELECT ID_FORM,count(*) as NBR_ETU FROM etudiant GROUP BY ID_FORM");
	$Smt -> execute();
    $all_etu = $Smt->fetchAll();
    $Smt->closeCursor();//vider le curseur (free)

    $Smt = $bdd->prepare("SELECT FILIERE FROM formation WHERE ID_FORM IN (SELECT ID_FORM FROM etudiant)");
	$Smt -> execute();
    $all_form = $Smt->fetchAll();
    $Smt->closeCursor();//vider le curseur (free)

    // var_dump($all_form);
    // echo "<BR><BR><BR>";
    // var_dump($postule_etu);

    //$array = array(12, 19, 3, 5, 2, 3, 2,3,1,2,4,5);
    //$labels = array('Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange');
/*
    $array = array($postule_etu[0]['NBR_ETU'],$postule_etu[1]['NBR_ETU'],$postule_etu[2]['NBR_ETU']);  
    $labels = array($postule_etu[0]['ID_FORM'],$postule_etu[1]['ID_FORM'],$postule_etu[2]['ID_FORM']);
*/

    $array1 = array();
    $array2 = array();
    $labels = array();

    foreach ($all_form as $value) {
        //echo $value[0];
        array_push($labels,$value[0]);

    }

    foreach ($all_etu as $value) {

        array_push($array1,$value[1]);

    }

    foreach ($postule_etu as $value) {

        array_push($array2,$value[1]);

    }

    $json_all_etu = json_encode($array1);
    $json_postule_etu = json_encode($array2);
    $json_labels = json_encode($labels);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div style="width:50%; height:50vh; ">
        <canvas id="myChart" width="100%" height="50vh"></canvas>
    </div>
<script>
const ctx = document.getElementById('myChart');
const myChart = new Chart(ctx, {
    //type: 'pie',
    type: 'bar',
    data: {
        //the number of labels must match the number of elements inside the data attribute in the datasets structure
        //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        labels: <?php echo $json_labels; ?>,
        datasets: [
            //label: '# of Votes',
            /*
            this is how u can specify x and y axes
            data: [{x:'2016-12-25', y:20}, {x:'2016-12-26', y:10}]
            */


            {
                label: '# Tous',
            data: <?php echo $json_all_etu; ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        },
        {
            label: '# Postulé',
            data: <?php echo $json_postule_etu; ?>,
            
        },
        
            /**/
        ]
        
    
        
    
        /*datasets: [{
            type: 'bar',
            label: 'Bar Dataset',
            data: [10, 20, 30, 40]
        }, {
            type: 'line',
            label: 'Line Dataset',
            data: [50, 50, 50, 50],
        }],*/
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            title: {
                // title for the chart
                display: true,//show
                text: 'Statistique sur les taux de postulation',
                font: {//font attributes
                        size: 40
                    }
            },
            legend: {
                labels: {
                font: {
                        size: 20
                    }
            },
            }
        }
    }
});
</script>
</body>
</html>