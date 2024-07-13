function openOnglet(evt, ongletName) {
    // Déclare toutes les variables
    var i, tabcontent, tablinks;

    // Récupère tous les éléments avec la classe "tabcontent" et les cache
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Récupère tous les éléments avec la classe "tablinks" et enlève la classe "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Affiche le contenu de l'onglet courant et ajoute une classe "active" au bouton qui a ouvert l'onglet
    document.getElementById(ongletName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Ouvre par défaut le premier onglet
document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".tab button").click();
});
