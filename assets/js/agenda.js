window.onload = function() {
    if (window.location.search) {
        var urlParams = new URLSearchParams(window.location.search);
        var adeBase = urlParams.get('adeBase');
        var adeRessources = urlParams.get('adeRessources');
        var date = urlParams.get('date');
        if (adeBase && adeRessources) {
            loadAgenda(adeBase, adeRessources, date);
        }
    }
}
