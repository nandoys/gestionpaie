const recherche = $('#recherche')

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

recherche.keyup((evt) =>{
    if (recherche.val() !== '') {
        var resultatRecherche = $('#resultat-recherche').html('' +
            '<a class="list-group-item list-group-item-action border-bottom border-dark link-primary px-3 py-2" href="#"> list 1</a>')
    } else {
        $('#resultat-recherche').html('')
    }

})

recherche.focusout((evt) => {
    $('#resultat-recherche').html('')
})

