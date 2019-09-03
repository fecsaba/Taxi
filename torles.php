<?php 
$mysqli=new mysqli('localhost', 'root', '');
$mysqli->query('SET NAMES UTF8');
$mysqli->select_db('utak');

if (isset ($_GET)) {
    $q='DELETE FROM utadatok
            
            WHERE id = ?';
    $result = $mysqli->prepare($q);
    $result->bind_param('i', $_GET['utadat_id']);
    $res=$result->execute();
    }
    header('Location:index.php');
?>