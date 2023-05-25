const fonctionModal = document.getElementById('fonctionModal')

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function delBtn(item, url){
    
    var id = item.dataset.id

    item.classList.add("disabled")

    var request = new XMLHttpRequest()
    
    request.open('get', `${window.origin}/api/fonctions/${id}`)
    request.setRequestHeader("Accept", "application/json")
    
    request.onload = (data) => {
        var json_data = JSON.parse(data.target.response)
        var titre =json_data.titre
    

            
        if (confirm(`Voulez-vous supprimer cette fonction ? \r ${capitalizeFirstLetter(titre)} `)) {
            window.location.href = url
        }
        item.classList.remove("disabled")
    }

    request.send()

}

function updateBtn(item, url){
    
    var id = item.dataset.id

    window.location.href = url
}

if (window.location.pathname.match("^/configuration/fonction/[0-9]/update$")) {

    new bootstrap.Modal(fonctionModal, {}).show()
}

fonctionModal.addEventListener('hide.bs.modal', event => {

    if (window.location.pathname.match("^/configuration/fonction/[0-9]/update$")) {
        const url = "/configuration"+window.location.search

        window.location.href = url
    }
})

