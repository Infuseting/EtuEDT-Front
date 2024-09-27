
updateFavDiv(); 
function toggleDiv(element) {
    
    element.classList.toggle("active");
}
function toggleFav(element) {
    if (element.classList.contains("fa-regular")) {
        


        element.classList.remove("fa-regular");
        element.classList.add("fa-solid");
        element.classList.add("yellow");
        let date = new Date(Date.now() + 86400e20);
        date = date.toUTCString();
        setCookie(element.id, "true");
        
    }
    else if(element.classList.contains("fa-solid")) {
        console.log(element.parentElement.parentElement.parentElement.classList);
        if(element.parentElement.parentElement.parentElement.classList.contains("fav-div")){
            var contentDiv = document.querySelector(".content-div");
            var escapedId = CSS.escape(element.id);
            var base = contentDiv.querySelector(`#${escapedId}`);
            base.classList.remove("fa-solid");
            base.classList.add("fa-regular");
            base.classList.remove("yellow");
        }
        element.classList.remove("fa-solid");
        element.classList.add("fa-regular");
        element.classList.remove("yellow");
        deleteCookie(element.id)
        
    }
    else{
        alert("ERREUR : L'élément n'est pas un element pouvant être indiqué en favoris.");
    }
    updateFavDiv();
}
document.getElementById("search-bar").addEventListener('input', (e) => {
    searchBar();
});

function containsSequence(str1, str2) {
    const words1 = str1.split(" ");
    const words2 = str2.split(" ");
    return words1.every(word1 => words2.some(word2 => word2.includes(word1)));
}
function updateFavDiv() {
    const favDiv = document.getElementById('fav-div');
    favDiv.innerHTML = '';
    const cookies = document.cookie.split('; ');
    cookies.forEach(cookie => {
        if (!cookie.includes('darkMode') && cookie.includes('1-')) {
            const [key, value] = cookie.split('=');
            if (value) {
                console.log(key);
                const element = document.getElementById(`${key}`);
                if (element) {
                    const a = element.parentElement.parentElement;
                    if (a != null) {
                        favDiv.appendChild(a.cloneNode(true));
                    }
                }
            }
        }
    });
}

function searchBar() {
    var input = document.getElementById("search-bar");
    var filter = input.value.toUpperCase();
    var dropdowns = document.getElementsByClassName("dropdown-menu");
    if (filter.length == 0) {
        for (var i = 0 ; i < dropdowns.length; i++) {
            var dropdown = dropdowns[i];
            dropdown.classList.remove('hidden');
            dropdown.classList.remove('active');
            var dropdownElements = dropdown.querySelectorAll(".dropdown-element");
            for (var j = 0; j < dropdownElements.length; j++){
                dropdownElements[j].classList.remove('hidden');
            }
        }
        return;
    }
    for (var i = 0 ; i < dropdowns.length; i++) {
        var dropdown = dropdowns[i];    
        var dropdownName = dropdown.querySelector("a").textContent.toUpperCase();
        var dropdownElements = dropdown.querySelectorAll(".dropdown-element");
        var count = 0;
        for (var j = 0; j < dropdownElements.length; j++){
            var elementName = dropdownElements[j].textContent.toUpperCase();
            const commonWords = containsSequence(filter, dropdownName + " " + elementName);
            count ++;
            if (commonWords == true || dropdownName.includes("PROFESSEUR") || dropdownName.includes("SALLE") ){
        
                if (((!dropdownName.includes("PROFESSEUR")) || (!dropdownName.includes("SALLE"))) || commonWords == true){
                
                    dropdown.classList.add('active');
                    dropdown.classList.remove('hidden');
                    if ((dropdownName.includes("PROFESSEUR") || dropdownName.includes("SALLE")) && commonWords == false)
                    {
                        dropdownElements[j].classList.add('hidden');                        
                    }                       
                    else{
                        dropdownElements[j].classList.remove('hidden');
                    }
                }
                else{
                    dropdown.classList.remove('active');
                }                
            }
            else
            {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('active');
            }
            if (count == 0){
                dropdown.classList.add('hidden');
            }
        }
    }
}