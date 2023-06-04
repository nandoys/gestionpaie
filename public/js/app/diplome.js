const diplomeModal = document.getElementById('diplomeModal')

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

if (window.location.pathname.match("^/configuration/diplome/[0-9]/update$")) {

    new bootstrap.Modal(diplomeModal, {}).show()
}


function updateBtn(item, url){

    var id = item.dataset.id

    window.location.href = url
}

if (window.location.pathname.match("^/configuration/diplomes/[0-9]/update$")) {

    new bootstrap.Modal(diplomeModal, {}).show()
}

diplomeModal.addEventListener('hide.bs.modal', event => {

    if (window.location.pathname.match("^/configuration/diplome/[0-9]/update$")) {
        const url = "/configuration/diplome"+window.location.search

        window.location.href = url
    }
})

