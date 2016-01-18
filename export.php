<?php

require_once('./init.php');
if (isset($_GET['semaine']))
    $week = $_GET['semaine'];    

$sql = 'SELECT * FROM chatools_jour_v3 WHERE DATE_FORMAT(timestamp,"%u")=' . (int) $week . ' AND DATE_FORMAT(timestamp,"%Y")=' . (int) $_GET['annee'] . " ORDER BY timestamp";

$req = mysql_query($sql);
 
$content .= '<div class="content">';
while ($row = mysql_fetch_object($req)) {
    $ssql = 'SELECT *, t.libelle as lib, t.id as tid FROM chatools_task_v3 t WHERE id_jour=' . $row->id . ' ORDER BY timestamp DESC';
    $sreq = mysql_query($ssql);

    $date = $row->date;
    $content .= '<div class="date">' . $date . '</div>';
    $content .= '<div class="task_labels">';
    while ($srow = mysql_fetch_object($sreq)) {        
        $content .= '<div class="task_label">' . $srow->lib . '</div>';
    }
    $content .= '</div>';

}
$content .= '</div>';

$info='<div class="info">Semaine ' . $week . "</div>";
if (isset($_GET['jour'])) $info='<p>Date : '. $date .'</p>';

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
    <head>	
        <title>Chatools export</title>

        <link type="text/css" rel="stylesheet" media="all" href="css/main.css" />
        <link type="text/css" rel="stylesheet" media="all" href="css/modules.css" />
        <link type="text/css" rel="stylesheet" media="all" href="css/export.css" />


        <link href="favicon.png" rel="icon" type="image/png" />

        <link rel="alternate" type="application/rss+xml" title="actu" href="flux.php" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    </head>
    <body>
<?php echo $info ?>
<?php echo $content ?>
    </body>
</html>
