<header>
    <h1><a href="/">EDT Unicaen</a></h1>
    <i class="fa-solid fa-lightbulb" onclick="toggleLightMode()"></i>
    
</header>
<main>
    <div class="agenda-selector">
        <?php

            if (!isset($_GET['date']) || !isset($_GET['adeRessources']) || !isset($_GET['adeBase'])) {   
                include 'assets/php/EDTChooser.php';
            }   
        ?>
    </div>
    <div class="agenda hidden">
        <?php
            if (isset($_GET['date']) || isset($_GET['adeRessources']) || isset($_GET['adeBase'])) {   
                include 'assets/php/Agenda.php';
            }
        ?>
    </div>
</main>
<footer>
    <a href="https://infuseting.github.io/"><i class="fa-brands fa-github"></i></a>
</footer>
<script src="assets/js/index.js"></script>
<script src="https://kit.fontawesome.com/f3c48cc36a.js" crossorigin="anonymous"></script>