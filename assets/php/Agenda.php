
<div class="edt-week">
    <?php
    function height_calculator($element){
        $dtStart = new DateTime($element['DTSTART']);
        $dtEnd = new DateTime($element['DTEND']);
        $diff = $dtStart->diff($dtEnd);
        $diff = $diff->format('%s') + $diff->format('%i')*60 + $diff->format('%h')*3600;
        return $diff*0.00227;

    }
    function parse_event_data($docstring) {
        $data = explode("\n", trim($docstring));
        $event_data = [];
        $tempKey = '';
    
        foreach ($data as $line) {
            $line = explode("(Exporté le:", $line)[0];
            if (strpos($line, ":") !== false) {
                list($key, $value) = explode(":", $line, 2);
                $tempKey = $key;
                $event_data[trim($key)] = trim($value);
            } else {
                $event_data[trim($tempKey)] .= ' ' . trim($line);
            }
        }
    
        return $event_data;
    }
    function pos_calculator($time){
        $time = new DateTime($time['DTSTART']);
        $time = $time->format('H:i:s');
        $time = new DateTime($time);
        $diff = $time->diff(new DateTime('06:00:00'));
        $diff = $diff->format('%s') + $diff->format('%i')*60 + $diff->format('%h')*3600;
        
        return ($diff)*0.00227;
    }
    function parseTime($time){
        $time = new DateTime($time);
        $time->modify('+2 hours');
        return $time->format('H\hi\'');
    }
    function day_spliting($response, $actualday) {
        $list = array_fill(0, 16, []);
        $timeday = [];
        for ($i = 0; $i < 16; $i++) {
            $timeday[date('Y-m-d', strtotime($actualday . " +$i days"))] = $i;
        }
        $events = explode("BEGIN:VEVENT", $response);
        foreach ($events as $index => $value) {
            if ($index == 0) continue; // Skip the first split part if it's not an event
            $i = parse_event_data($value);
            $get_start_time_day = date('Y-m-d', strtotime($i['DTSTART']));
            
            $list[$timeday[$get_start_time_day]][] = $i;
        }
        return $list;
    }
    
    $jours_francais = [
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    ];
    $color = [
        '1' => 'yellow',
        '2' => 'red',
        '3' => 'green',
        '4' => 'blue',
        '5' => 'orange',
        '6' => 'purple',
        '7' => 'pink',
        '8' => 'brown',
        '9' => 'cyan',
        '10' => 'magenta',
        '11' => 'maroon',
        '12' => 'navy',
        '13' => 'olive',
        '14' => 'teal',
        '15' => 'silver'
    ];
    $ade_univ = "http://ade.unicaen.fr/jsp/custom/modules/plannings/anonymous_cal.jsp";
    $date = new DateTime();     
    $adeResources = $_GET['adeRessources'];
    $adeBase = $_GET['adeBase']; 
    $date2 = new DateTime();
    $date2 = $date2->modify('+15 day');
    $params = [
        "resources" => strval($adeResources),
        "projectId" => strval($adeBase),
        "firstDate" => $date->format('Y-m-d'),
        "lastDate" => $date2->format('Y-m-d')
    ];
    $query = http_build_query($params);
    $request = $ade_univ . "?" . $query;
    $response = file_get_contents($request);
    if (isset($http_response_header) && $http_response_header[0] != 'HTTP/1.1 200 ') {
        error_log("Error: received non-200 response code");
    } else {
        if (strpos($response, "Le projet est invalide") !== false || strpos($response, "BEGIN:VEVENT") === false) {
            error_log("$adeBase:$adeResources return anything");
        }
    }

    $response = day_spliting($response, $date->format('Y-m-d'));    
    for ($i=0; $i < 16; $i++) { 
        
        if ($response != null && count($response[$i]) != 0) {
            
            echo '<div class="edt-day">';
            echo '<div class="edt-header">';
                    //set date to Actual Year, Month, Date but 0h0m0s 
                    $date1 = new DateTime(date('Y-m-d', strtotime($date->format('Y-m-d'))));     
                    $date2 = new DateTime(date('Y-m-d')); 
                    if ($date1 == $date2) {
                        echo "<h2>Aujourd'hui</h2>";
                        echo "<p>".($date1->format("d/m/Y"))."</p>";
                    }
                    else{
                        
                        $jour = date('l', strtotime($date->format('Y-m-d')));
                        $jour = $jours_francais[$jour];
                        echo "<h2>".$jour."</h2>";
                        echo "<p>".$date1->format("d/m/Y")."</p>";
                    }
            
                    
                    
    
                    
            echo '</div>';
            echo '<div class="edt-content">';
            $colorUsed = [];
    
            foreach ($response[$i] as $index => $value) {
                $rdmcolor = generateColor($colorUsed, $color, $value);
                echo '<div style="top:'.pos_calculator($value).'%;height:'.height_calculator($value).'%;" class="edt-event '.$rdmcolor.'"> ';
                echo '<div class="edt-event-content">';
                echo '<h3 class="'.$rdmcolor.'">'.$value['SUMMARY'].'</h3>';
                echo '<p class="'.$rdmcolor.'">'.$value['LOCATION'].'</p>';
                echo '<p class="'.$rdmcolor.'">'.parseTime($value['DTSTART']).' - '.parseTime($value['DTEND']).'</p>';
                echo '<p class="'.$rdmcolor.'">'.str_replace('\ n' , '' ,str_replace('\n', PHP_EOL, $value['DESCRIPTION'])).'</p>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        $date = ($date)->modify('+1 day');
    }
    function generateColor($colorUsed, $color, $element) {
        $date = new DateTime($element['DTEND']);
        $now = new DateTime();
        if ($date < $now) {
            return 'gray';
        }
        


        else if (strpos($element['SUMMARY'], ' CC') !== false) {
            return 'red';
        }
        else if (strpos($element['SUMMARY'], ' CTP') !== false) {
            return 'red';
        }
        else if (strpos($element['SUMMARY'], ' CTD') !== false) {
            return 'red';
        }
        else if (strpos($element['SUMMARY'], ' TP') !== false) {
            return 'green';
        }
        else if (strpos($element['SUMMARY'], ' TD') !== false) {
            return 'blue';
        }
        else if (strpos($element['SUMMARY'], ' CM') !== false) {
            return 'yellow';
        }
        else if (strpos($element['DESCRIPTION'], ' CC') !== false) {
            return 'red';
        }
        else if (strpos($element['DESCRIPTION'], ' CTP') !== false) {
            return 'red';
        }
        else if (strpos($element['DESCRIPTION'], ' CTD') !== false) {
            return 'red';
        }
        else if (strpos($element['DESCRIPTION'], ' TP') !== false ){
            return 'green';
        }
        else if (strpos($element['DESCRIPTION'], ' TD') !== false) {
            return 'blue';
        }
        else if (strpos($element['DESCRIPTION'], ' CM') !== false) {
            return 'yellow';
        }
        else{
            return 'cyan';
        }
    }
    ?>
</div>
<script src="assets/js/agenda.js"></script>