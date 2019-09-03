<?php
$mysqli=new mysqli('localhost', 'root', '');
$mysqli->query('SET NAMES UTF8');
$mysqli->select_db('utak');

if (isset($_POST)) {
    $result = $mysqli->prepare($_POST['lek']);
    if (isset($_POST['update_id'])) {
        $result->bind_param('isidddsi',
            $_POST['taxi_id'],
            $_POST['indulas_ideje'],
            $_POST['idotartam'],
            $_POST['megtett_ut'],
            $_POST['dij'],
            $_POST['jatt'],
            $_POST['fiz_mod_id'],
            $_POST['update_id'],
        
        );
    } else {
        $result->bind_param('isdddds',
            $_POST['taxi_id'],
            $_POST['indulas_ideje'],
            $_POST['idotartam'],
            $_POST['megtett_ut'],
            $_POST['dij'],
            $_POST['jatt'],
            $_POST['fiz_mod_id']
        
        );
    }
    $res = $result->execute();
}

header('Location:index.php');

?>