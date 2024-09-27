let days = 365;
if (!getCookie("darkMode")) {
    setCookie("darkMode", "false");
}
if (getCookie("darkMode") == "true") {
    var html = document.querySelector("html");
    html.classList.add("black");
}


function setCookie(name, value) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return "false";

}

function deleteCookie(name) {
    document.cookie = name + "=; Max-Age=-99999999;";
}
function toggleCookie(name) {
    if (getCookie(name) == "true") {
        setCookie(name, "false"); 
    } else {
        setCookie(name, "true");    
    }
}
function toggleLightMode(){
    var html = document.querySelector("html");
    html.classList.toggle("black");
    toggleCookie("darkMode");

}
function getQueryStringParameter(key) {
    const url = new URL(window.location);
    return url.searchParams.get(key);
}
function updateQueryStringParameter(key, value) {
    const url = new URL(window.location);
    url.searchParams.set(key, value); 
    window.history.pushState({}, '', url);
}
function removeQueryStringParameter(key) {
    const url = new URL(window.location);
    url.searchParams.delete(key);
    window.history.pushState({}, '', url);
}
function loadAgenda(adeBase, adeRessources, dateParam){
    const date = new Date();
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();
    let currentDate = `${year}-${month}-${day}`;
    if (dateParam != null) {
        currentDate = dateParam;
    }
    updateQueryStringParameter('adeBase', adeBase);
    updateQueryStringParameter('adeRessources', adeRessources);
    updateQueryStringParameter('date', currentDate);
    var agendaSelector = document.querySelector('.agenda-selector');
    var agenda = document.querySelector('.agenda');
    agendaSelector.classList.add('hidden');
    agenda.classList.remove('hidden');
    var searchBar = document.querySelector('.search-bar');
    if (searchBar) {
        location.reload();
    }
}
