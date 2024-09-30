<p>DESIGN EN COURS DE CREATION ! CECI EST UNE VERSION BETA !</p>
<p>ATTENTION LES COURS SUPPLEMENTAIRE NE SONT PAS NECESSAIREMENT ECRIT SUR L'EMPLOI DU TEMPS</p>
<div class="search-bar">
    <input  placeholder="Rechercher" type="text" id="search-bar">
</div>
<div class="fav-div" id="fav-div">
    <?

    ?>
</div>
<div class="content-div">
    <div onclick="toggleDiv(this)" class="dropdown-menu">
        <a href="#"><i class="fa-solid fa-play"></i>Salle</a>
        <ul>
            <?php
            $json = file_get_contents("assets/json/salle.json");
            $data = json_decode($json, true);
            foreach ($data['salle'] as $salle) {
                $name = htmlspecialchars($salle['descTT']);
                $adeProjectID = htmlspecialchars($salle['adeProjectId']);
                $adeResources = htmlspecialchars($salle['adeResources']);
                $key = $adeProjectID . '-' . $adeResources;
                $iClass = isset($_COOKIE[$key]) ? 'fa-solid yellow fa-star' : 'fa-regular fa-star';
                echo "<div class=\"dropdown-element\" onclick='loadAgenda($adeProjectID,$adeResources,null)'><a href='#' ><i onclick=\"toggleFav(this); event.stopPropagation();\" id=\"$adeProjectID-$adeResources\" class=\"$iClass\"></i>$name</a></div>";
            }
            ?>
        </ul>
    </div>
    <div onclick="toggleDiv(this)" class="dropdown-menu">
        <a href="#"><i class="fa-solid fa-play"></i>Professeur</a>
        <ul>
            <?php
            $json = file_get_contents("assets/json/prof.json");
            $data = json_decode($json, true);
            foreach ($data['prof'] as $prof) {
                $name = $prof['descTT'];
                $adeProjectID = $prof['adeProjectId'];
                $adeResources = $prof['adeResources'];
                $key = $prof['adeProjectId'] . '-' . $prof['adeResources'];
                $iClass = isset($_COOKIE[$key]) ? 'fa-solid yellow fa-star' : 'fa-regular fa-star';
                echo "<div class=\"dropdown-element\" onclick='loadAgenda($adeProjectID,$adeResources,null)'><a href='#' ><i onclick=\"toggleFav(this); event.stopPropagation();\" id=\"$adeProjectID-$adeResources\" class=\"$iClass\"></i>$name</a></div>";
            }
            ?>
        </ul>
    </div>
    <?php
    $json = file_get_contents("assets/json/univ.json");
    $data = json_decode($json, true);
    
    foreach ($data['univ'] as $univ) {
        $name = $univ['nameUniv'];
        echo '<div onclick="toggleDiv(this)" class="dropdown-menu">';
        echo "<a href='#'><i class=\"fa-solid fa-play\"></i>$name</a>";
        echo '<ul>';
        foreach ($univ["timetable"] as $timetable) {
            $adeProjectID = $timetable['adeProjectId'];
            $adeResources = $timetable['adeResources'];
            $descTT = $timetable['descTT'];
            $key = $timetable['adeProjectId'] . '-' . $timetable['adeResources'];
            $iClass = isset($_COOKIE[$key]) ? 'fa-solid yellow fa-star' : 'fa-regular fa-star';
            echo "<div class=\"dropdown-element\" onclick='loadAgenda($adeProjectID,$adeResources,null)'><a href='#' ><i onclick=\"toggleFav(this); event.stopPropagation();\" id=\"$adeProjectID-$adeResources\" class=\"$iClass\"></i>$descTT</a></div>";

        }
        echo '</ul>';
        echo '</div>';
    }
    
    
    ?>
</div>
<script src="assets/js/search.js"></script>