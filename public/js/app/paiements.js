const avanceSalaireModal = $('#agentAvanceSalaireModal')
const agentPretModal = $('#agentPretModal')

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function delBtn(item, url){
    
    var id = item.dataset.id

    item.classList.add("disabled")

    $(`#${item.id}`).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Supprimer')

    var request = new XMLHttpRequest()
    
    request.open('get', `${window.origin}/api/paiements/${id}`)
    request.setRequestHeader("Accept", "application/json")
    
    request.onload = (data) => {
        var json_data = JSON.parse(data.target.response)
        
        if (confirm(`Voulez-vous supprimer vraiment ce paiement ?`)) {
            window.location.href = url
        }

        item.classList.remove("disabled")
        $(`#${item.id}`).html('Supprimer')
    }

    request.send()

}

// concerne la partie avance salaire
function delAvanceSalaireBtn (item, url) {
    var id = item.dataset.id

    item.classList.add("disabled")

    $(`#${item.id}`).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Supprimer')

    var request = new XMLHttpRequest()

    request.open('get', `${window.origin}/api/avance_salaires/${id}`)
    request.setRequestHeader("Accept", "application/json")

    request.onload = (data) => {
        var json_data = JSON.parse(data.target.response)

        if (confirm(`Voulez-vous vraiment supprimer cet avance sur salaire ?`)) {
            window.location.href = url
        }

        item.classList.remove("disabled")
        $(`#${item.id}`).html('Supprimer')
    }

    request.send()
}

if (window.location.pathname.match("^/agent/[0-9]+/paiements/avance/[0-9]+/update$")) {

    new bootstrap.Modal(avanceSalaireModal, {}).show()
}

if (window.location.search.match("page-avance-salaire=[0-9]+")) {

    new bootstrap.Modal(avanceSalaireModal, {}).show()
}

const totalAvanceSalaireErreur = $(".error-avance > div[class='invalid-feedback d-block']").length

if (totalAvanceSalaireErreur > 0) {
    new bootstrap.Modal(avanceSalaireModal, {}).show()
}

avanceSalaireModal.on('hide.bs.modal', event => {

    if (window.location.pathname.match("^/agent/[0-9]+/paiements/avance/[0-9]+/update$") || totalAvanceSalaireErreur > 0) {

        window.location.href = $('#container').data('url-agent-liste')
    }
    else if (window.location.search.match("page-avance-salaire=[0-9]+")) {
        window.location.href = $('#container').data('url-agent-liste')
    }
})


// concerne la partie prêt
function delPretBtn (item, url) {
    var id = item.dataset.id

    item.classList.add("disabled")

    $(`#${item.id}`).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Supprimer')

    var request = new XMLHttpRequest()

    request.open('get', `${window.origin}/api/pret_agents/${id}`)
    request.setRequestHeader("Accept", "application/json")

    request.onload = (data) => {
        var json_data = JSON.parse(data.target.response)

        if (confirm(`Voulez-vous vraiment supprimer ce prêt ?`)) {
            window.location.href = url
        }

        item.classList.remove("disabled")
        $(`#${item.id}`).html('Supprimer')
    }

    request.send()
}

if (window.location.pathname.match("^/agent/[0-9]+/paiements/pret/[0-9]+/update$")) {

    new bootstrap.Modal(agentPretModal, {}).show()
}

if (window.location.search.match("page-pret=[0-9]+")) {

    new bootstrap.Modal(agentPretModal, {}).show()
}

const totalPretErreur = $(".error-pret > div[class='invalid-feedback d-block']").length

if (totalPretErreur > 0) {
    new bootstrap.Modal(agentPretModal, {}).show()
}

agentPretModal.on('hide.bs.modal', event => {

    if (window.location.pathname.match("^/agent/[0-9]+/paiements/pret/[0-9]+/update$") || totalPretErreur > 0) {

        window.location.href = $('#container').data('url-agent-liste')
    }

    else if (window.location.search.match("page-pret=[0-9]+")) {
        window.location.href = $('#container').data('url-agent-liste')
    }
})

