const agentModal = document.getElementById('agentModal')

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function delBtn(item, url){
    
    var id = item.dataset.id

    item.classList.add("disabled")
    $(`#${item.id}`).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Supprimer')

    var request = new XMLHttpRequest()
    
    request.open('get', `${window.origin}/api/agents/${id}`)
    request.setRequestHeader("Accept", "application/json")
    
    request.onload = (data) => {
        var json_data = JSON.parse(data.target.response)
        var nom =json_data.nom
        var postnom = json_data.postnom
        var prenom =json_data.prenom

            
        if (confirm(`Voulez-vous supprimer cet agent ? \r ${capitalizeFirstLetter(nom)} ${capitalizeFirstLetter(postnom)} ${capitalizeFirstLetter(prenom)}`)) {
            window.location.href = url
        }
        item.classList.remove("disabled")
        $(`#${item.id}`).html('Supprimer')
    }

    request.send()

}

function updateBtn(item, url, isCreatingAgent){
    
    var id = item.dataset.id

    window.location.href = url
}

if (window.location.pathname.match("^/agent/[0-9]/update$")) {

    new bootstrap.Modal(agentModal, {}).show()
}

agentModal.addEventListener('hide.bs.modal', event => {

    if (window.location.pathname.match("^/agent/[0-9]/update$")) {

        window.location.href = "/agent"
    }
})

