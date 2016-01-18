<?php

/**

 * tables : 

  # Afficher chatools_jour_v3

  # Afficher chatools_task_v3

  # Afficher chatools_task_category_v3

  # Afficher chatool_task_state

 */


function getFirstYear(){
    $sql = 'SELECT * FROM chatools_jour_v3 ORDER BY timestamp ASC LIMIT 1';
    $req = mysql_query($sql);
    $row = mysql_fetch_object($req);
    $firstYear = date('Y', strtotime($row->timestamp));
    return $firstYear;
}

function buildHistory(){
    $history = "";
    $currentYear = date("Y");
    $firstYear = getFirstYear();
    for ($year = $firstYear; $year <= $currentYear; $year++){
        $delimitor = "-";
        if ($year == $currentYear){
            $delimitor = "";
        }
        $timestampCurrentYear = mktime(0, 0, 0, 1, 1, $year);
        $timestampNextYear = mktime(0, 0, 0, 1, 1, $year + 1);
        $history .= ' <a href="index.php?minTimestamp=' . $timestampCurrentYear . '&maxTimestamp=' . $timestampNextYear . '">' . $year . '</a> ' . $delimitor;
    }
    return $history;
}

function convertDate($date){
   // Lundi 04 Janvier 2016 => 2016-01-04 00:00:00
   $months= array(
     "Janvier" => "01",
     "Février" => "02",
     "Mars" => "03",
     "Avril" => "04",
     "Mai" => "05",
     "Juin" => "06",
     "Juillet" => "07",
     "Août" => "08",
     "Septembre" => "09",
     "Octobre" => "10",
     "Novembre" => "11",
     "Décembre" => "12",
   );
   $parts = split(" ", $date);
   $day = "01";
   if (isset($parts[1]))
       $day = $parts[1];
   $month = "01";
   if (isset($parts[2]))
       $month = $parts[2];
   $year = "1970";
   if (isset($parts[3]))
       $year = $parts[3];
   return $year . "-" . $months[$month] . "-" . $day . " 00:00:00";

}

function renderListePost() {
    $content = '';
    // Get current year and next year
    $currentYear = date("Y");
    $nextYear = $currentYear + 1;
    // Check if user is asking history
    if (isset($_GET['minTimestamp']))
        $minTimestamp = $_GET['minTimestamp'];
    else 
        $minTimestamp = mktime(0, 0, 0, 1, 1, $currentYear);
    if (isset($_GET['maxTimestamp']))
        $maxTimestamp = $_GET['maxTimestamp'];
    else 
        $maxTimestamp = mktime(0, 0, 0, 1, 1, $nextYear);
    $minDate = date('Y-d-m H:i:s', $minTimestamp);
    $maxDate = date('Y-d-m H:i:s', $maxTimestamp);

    $listeDuree = array('0.25', '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5', '5.5', '6', '6.5', '7', '7.5', '8');

    $sql = 'SELECT * FROM chatools_jour_v3 WHERE timestamp > "' . $minDate . '" AND timestamp < "' . $maxDate . '"   ORDER BY timestamp DESC';

    //echo $sql;

    $req = mysql_query($sql);

    $nWeekPrec = 0;

    $nWeekNext = 0;

    $createWeek = true;

    $cptWeek = 0;

    $createSemaine1 = false;

    $displayWeek = '';

    $displayBtnDWeek = 'display:none';

    $displayBtnHWeek = '';

    while ($row = mysql_fetch_object($req)) {
        $createWeek = false;

        //	var_dump($row->timestamp);

        $timestamp = strtotime($row->timestamp);

        //	var_dump($timestamp);
        //	var_dump(date('d / m / Y  --- sem : W',$timestamp));


        $nYear = date('Y', $timestamp);

        $nJour = date('d', $timestamp);

        $nMois = date('m', $timestamp);

        $nWeek = date('W', $timestamp);

        $nWeekAct = date('W');

     /*   var_dump(($nWeek));
        var_dump(($createSemaine1));
        var_dump((int)$nWeek,(int)$nWeekNext );
        var_dump((int)$nWeek == (int)$nWeekNext );*/
		
        if (!$nWeekPrec || (int)$nWeek < (int)$nWeekPrec || ((int)$nWeek == 1 && !$createSemaine1) || !$createSemaine1) {

            

            $nWeekNext = (int)$nWeek-1;

            $createWeek = true;

            if (!$createSemaine1)
                $createSemaine1 = true;
        }
		$nWeekPrec = $nWeek;
//var_dump($createWeek);
        if ($createWeek) {

            if ($cptWeek > 0)
                $content.='</div>';

            $content.='<h3>Semaine : ' . $nWeek . ' 

							<a href="#" id="w' . $nWeek . '" class="actionShowWeek btnDw' . $nWeek . '" style="' . $displayBtnDWeek . '"><img src="images/plusS.png" alt="" /></a>

							<a href="#" id="w' . $nWeek . '" class="actionHideWeek btnHw' . $nWeek . '" style="' . $displayBtnHWeek . '" ><img src="images/minusS.png" alt="" /></a>

							 - <a href="export.php?semaine=' . $nWeek . '&annee=' . $nYear . '" onclick="window.open(this.href);return false;">Export</a>

						</h3>';

            $content.='';

            $content.='<div class="contentWeek w' . $nWeek . '" style="' . $displayWeek . '" >';

            $displayWeek = 'display:none';

            $displayBtnDWeek = '';

            $displayBtnHWeek = 'display:none';
        }

        $cptWeek++;

        $content.='<div id="j' . $row->id . '" class="post">		

		<p>Date : ' . $row->date . ' - Semaine : ' . $nWeek . ' 
<a class="floatRight" onclick="return confirm(\'Chapine, es-tu sur de vouloir supprimer ce jour ?\');" title="supprimer le jour  ' . $row->date . ' " href="index.php?action=supprime_jour&amp;id=' . $row->id . '">
					<img alt="supprimer" src="images/b_drop.png">
				</a>
		</p>
                <p class="floatRight">
                        </p>
';

        $cpt = 0;

        $content.='<form action="index.php" method="post">';

        $content.='<table summary="">';

        $ssql = 'SELECT *,t.libelle as lib,t.id as tid FROM chatools_task_v3 t WHERE  id_jour=' . $row->id . ' ORDER BY timestamp DESC';

        $sreq = mysql_query($ssql);

        $totalDuree = 0;

        $totalDureeCat = array();

        while ($srow = mysql_fetch_object($sreq)) {

            $cpt++;

            $totalDuree+=$srow->duree;

            $totalDureeCat[$srow->id_category]['duree']+=$srow->duree;

            $totalDureeCat[$srow->id_category]['lib'] = $srow->categorie;

            if ($cpt % 2 == 0)
                $class_li = 'zebra'; else
                $class_li = 'zebro';

            $content.='

			<tr class="' . $class_li . ' state' . $srow->id_state . '">
								

				<td><span class="libelle">' . $srow->lib . '</span>

				<input  type="hidden" name="category_task[' . ($cpt) . ']" value="' . $srow->id_category . '" />

				<input  type="hidden" name="libelle_task[' . ($cpt) . ']" value="' . $srow->lib . '" />

                                     </td>

				

				<td><a href="index.php?action=supprime_task&amp;id=' . $srow->tid . '" title="supprimer la tache ' . $srow->lib . '" onclick="return confirm(\'Chapine, es-tu sur de vouloir supprimer cette tâche ?\');">

					<img src="images/b_drop.png" alt="supprimer" />

				</a>

				<input type="hidden" name="task[' . $cpt . ']" value="' . $srow->tid . '"/></td>

			</tr>

		';
        }

        $content.='

			<tr><td><span>Nouveau:</span></td></tr>				

			<tr>


				<td>

                                    <input type="text" name="libelle_task[' . ($cpt + 1) . ']" size="50" class=""/>

                                  

                                </td>

				

				<td><input type="hidden" name="task[' . ($cpt + 1) . ']" value="nouveau"/></td>

			</tr>

		';

        $content.='</table>';

        $content.='<input type="hidden" value="modif_jour" name="action" />';

        $content.='<input type="hidden" value="' . $row->id . '" name="id_jour" />';

        $content.='<input type="submit" value="Confirmer" name="submit" />';

        $content.='</form>';

      //  $content.='<!--<p>Total : ' . $totalDuree . 'h | ' . $totalDureeCat[2]['lib'] . ' : ' . $totalDureeCat[2]['duree'] . 'h</p>-->';



        $content.='<hr/></div>';
    }

    //fermeture derniere semaine

    $content.='</div>';

    return $content;
}

function getListSelectOption($table, $id = 0) {

    $content = '';

    $sql = 'SELECT * FROM ' . $table . ' ORDER BY libelle ';

    $req = mysql_query($sql);

    while ($row = mysql_fetch_object($req)) {

        if ($id == $row->id)
            $selected = 'selected="selected"'; else
            $selected = '';

        $content.='<option value="' . $row->id . '" ' . $selected . '>' . $row->libelle . '</option>';
    }



    return $content;
}

function controler($params) {



    if (is_array($params)) {



        switch ($params['action']) {



            case 'ajout_jour':

                

                $sql = 'INSERT INTO chatools_jour_v3 (date,timestamp,heure_embauche) VALUES ("' . $params['date_jour'] . '","' . convertDate($params['date_jour']) . '", "9h00")';

                mysql_query($sql);

                return true;

                break;

            case 'ajout_cat':

                $sql = 'INSERT INTO chatools_task_category_v3 (libelle) VALUES ("' . $params['titre_cat'] . '")';

                mysql_query($sql);

                return true;

                break;

            case 'modif_jour':

                //   var_dump($params);exit();

                foreach ($params['task'] as $key => $value) {

                    if ($params['libelle_task'][$key]) {



                        if ($value == 'nouveau') {

                            $sql = 'INSERT INTO chatools_task_v3 (id_jour,libelle) VALUES ("' . $params['id_jour'] . '","' . $params['libelle_task'][$key] . '")';
                        } else {

                            //$sql='UPDATE chatools_task_v3 SET id_category="'.$params['category_task'][$key].'",libelle="'.$params['libelle_task'][$key].'",id_state="'.$params['state_task'][$key].'" WHERE id='.$value;

                            $sql = 'UPDATE chatools_task_v3 SET libelle="' . $params['libelle_task'][$key] . '" WHERE id=' . $value;

                            //	echo $sql.'<br/>'; 
                        }



                        mysql_query($sql);
                    }
                }



                return true;

                break;

            case 'supprime_task':

                if ($params['id']) {

                    $sql = 'DELETE FROM chatools_task_v3 WHERE id=' . $params['id'];

                    mysql_query($sql);
                }

                return true;

                break;
            case 'supprime_jour':

                if ($params['id']) {

                    $sql = 'DELETE FROM chatools_jour_v3 WHERE id=' . $params['id'];

                    mysql_query($sql);
                }

                return true;

                break;

            case 'modifie_heure_embauche':

                $val = $_GET['heure'];

                $id = $_GET['j'];

                $sql = 'UPDATE chatools_jour_v3 SET heure_embauche="' . $val . '" WHERE id=' . $id;

                mysql_query($sql);

                echo $val;

                exit;

                break;

            default:

                return false;

                break;
        }
    }
}
