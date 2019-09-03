<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Taxi</title>
</head>
<body>
    <h1>Fuvarok</h1>
    <form action="index.php" method="get">
        <label for="keres">Keresés: </label>
        <input type="number" name="keres">
        <button type="submit">Keresés</button>
    </form>
    <hr>
    <div style = "height:200px; overflow-y:scroll">
        <table border = "1">
            <tr>
                <th>Azonosító</th>
                <th>Indulás</th>
                <th>Időtartam</th>
                <th>Táv (miles)</th>
                <th>Viteldíj</th>
                <th>Jatt</th>
                <th>Fiz.mód</th>
            </tr>

<?php
if (isset($_GET['keres'])) {
    $s = $_GET['keres'];
    
} else $s=null;
$q = 'SELECT
            utadatok.id,
            utadatok.taxiid,
            utadatok.indulasideje,
            utadatok.idotartam,
            utadatok.tavolsag,
            utadatok.viteldij,
            utadatok.borravalo,
            utadatok.fizmodid,
            fizmodok.megnevezes
            FROM utadatok
            INNER JOIN fizmodok
            ON utadatok.fizmodid = fizmodok.id
            WHERE 1=1 ';

            if (is_numeric($s)) {
                $q .= 'AND utadatok.taxiid = '.$s;
            }

    
    $result = DbOpen($q);
    while ($row = $result->fetch_object()) {
        echo '<tr>';
        echo '<td>'.$row->taxiid.'</td>';
        echo '<td>'.$row->indulasideje.'</td>';
        echo '<td>'.$row->idotartam.'</td>';
        echo '<td>'.$row->tavolsag.'</td>';
        echo '<td>'.$row->viteldij.'</td>';
        echo '<td>'.$row->borravalo.'</td>';
        echo '<td>'.$row->megnevezes.'</td>';
        echo '<td><a href="index.php?utadat_id='.$row->id.
                '&fizmod_id='.$row->fizmodid.'"><button>Módosítás</button></a></td>';
        echo '<td><a href="torles.php?utadat_id='.$row->id.'">
                <button>Törlés</button></a></td>';
        echo '</tr>';
    }
?>

        </table>

    </div>
    <hr>

    <!-- Felvitel -->
    <h2>Új felvitel</h2>

<?php
$sql_insert = 'INSERT INTO utadatok (taxiid, indulasideje, idotartam, tavolsag, viteldij, borravalo, fizmodid)
                    VALUES (?, ?, ?, ?, ?, ?, ?)';



echo '<form action="felv_modosit.php" method="post">';
echo '<label for="taxi_id">Taxi id: </label>';
echo '<input type="number" name="taxi_id" placeholder="nnnn"/>';
echo '<label for="indulas_ideje">Indulás ideje: </label>';
echo '<input type="datetime-local" name="indulas_ideje" />';
echo '<label for="idotartam">Időtartam: </label>';
echo '<input type="number" name="idotartam" placeholder="másodperc">';
echo '<br>';
echo '<label for="megtett_ut">Megtett út: </label>';
echo '<input type="text" name="megtett_ut" placeholder="00.00">';
echo '<label for="dij">Viteldíj: </label>';
echo '<input type="text" name="dij" placeholder="00.00">';
echo '<label for="jatt">Borravaló: </label>';
echo '<input type="text" name="jatt" placeholder="00.00">';
echo '<br>';
echo '<label for="fiz_mod_id">Fizetési mód : </label>';
echo '<select name="fiz_mod_id" >';
$fizmodSelect = 'SELECT
                    fizmodok.id,
                    fizmodok.megnevezes
                    FROM fizmodok';
$fmreult = DbOpen($fizmodSelect);
while ($row = $fmreult->fetch_object()) {
    echo '<option value="'.$row->id.'">'.$row->megnevezes.'</option>';
}

echo '</select>';
echo '<input type="hidden" value="'.$sql_insert.'" name="lek">';
echo '<button type="submit">Ment</button>';
echo '</form>';

// Módosítás
if (isset($_GET["utadat_id"])) {
    echo '<h2>Módosítás</h2>';
    $sql_update = 'UPDATE utadatok
                    SET taxiid = ?,
                        indulasideje = ?,
                        idotartam = ?,
                        tavolsag = ?,
                        viteldij = ?,
                        borravalo = ?,
                        fizmodid = ?
                    WHERE id = ?';

    $q = 'SELECT
                utadatok.id,
                utadatok.taxiid,
                utadatok.indulasideje,
                utadatok.idotartam,
                utadatok.tavolsag,
                utadatok.viteldij,
                utadatok.borravalo,
                utadatok.fizmodid,
                fizmodok.megnevezes
                FROM utadatok
                INNER JOIN fizmodok
                ON utadatok.fizmodid = fizmodok.id
                WHERE utadatok.id = '.$_GET["utadat_id"];
    $update_result = DbOpen($q);
    $row_update=$update_result->fetch_object();
    echo '<form action="felv_modosit.php" method="post">';
    echo '<label for="taxi_id">Taxi id: </label>';
    echo '<input type="number" name="taxi_id" value = "'.$row_update->taxiid.'"./>';
    echo '<label for="indulas_ideje">Indulás ideje: </label>';
    echo '<input type="text" name="indulas_ideje" value="'.$row_update->indulasideje.'"/>'; //ne datetime
    echo '<label for="idotartam">Időtartam: </label>';
    echo '<input type="number" name="idotartam" value="'.$row_update->idotartam.'">';
    echo '<br>';
    echo '<label for="megtett_ut">Megtett út: </label>';
    echo '<input type="text" name="megtett_ut" value="'.$row_update->tavolsag.'">';
    echo '<label for="dij">Viteldíj: </label>';
    echo '<input type="text" name="dij" value="'.$row_update->viteldij.'">';
    echo '<label for="jatt">Borravaló: </label>';
    echo '<input type="text" name="jatt" value="'.$row_update->borravalo.'">';
    echo '<br>';
    echo '<label for="fiz_mod">Fizetési mód: </label>';
    echo '<select name="fiz_mod_id" id="fizmodid">';
    $fizmodSelect = 'SELECT id, megnevezes FROM fizmodok';
    $fmresult = DbOpen($fizmodSelect);
    while ($row = $fmresult->fetch_object()) {
        echo '<option value="'.$row->id.'" 
            ' .($_GET["fizmod_id"] == $row->id? "selected" : "").'>'.$row->megnevezes.'</option>';
        
        }
    echo '</select>';
    
    echo '<input type="hidden" value="'.$sql_update.'" name="lek">';
    echo '<input type="hidden" value="'.$row_update->id.'" name="update_id">';
    echo '<button type="submit">Ment</button>';
    echo '</form>';
}



function DbOpen($q)
{
    $mysqli=new mysqli('localhost', 'root', '');
    $mysqli->query('SET NAMES UTF8');
    $mysqli->select_db('utak'); 
    return $mysqli->query($q);
}
?>
</body>
</html>


