const agentModal = document.getElementById('agentModal')

const fonctionAgent = $('#agent_salaire_agent_fonction')

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

            
        if (confirm(`Voulez-vous vraiment supprimer cet agent ? \r ${capitalizeFirstLetter(nom)} ${capitalizeFirstLetter(postnom)} ${capitalizeFirstLetter(prenom)}`)) {
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

if (window.location.pathname.match("^/agent/[0-9]+/update$")) {

    new bootstrap.Modal(agentModal, {}).show()
}

const totalErreur = $('.invalid-feedback .d-block').length

if (totalErreur > 0) {
    let tabs = []
    for (let i = 0; i < totalErreur; i++) {
        const tab = $('.invalid-feedback .d-block')[i].parentElement.dataset.tab

        if (tabs.find(tabExistant => tabExistant === tab) === undefined) {
            tabs.push(tab)
        }
    }

    tabs.forEach((tab, index) => {

        $(`#${tab}`).addClass('text-danger')
        if (index == 0 ) {
            $('.nav-link').removeClass('active')
            $('.tab-pane').removeClass('show active')

            $(`#${tab}`).addClass('active text-white bg-danger')
            $(`#${tab.replace('-tab','')}`).addClass('show active')
        }
    })

    new bootstrap.Modal(agentModal, {}).show()

    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', (evt) => {

        const tabCourant = $(`#${evt.target.id}`)
        const tabPrecedent = $(`#${evt.relatedTarget.id}`)

        if (tabs.find(tabExistant => tabExistant === evt.relatedTarget.id) !== undefined) {
            tabPrecedent.addClass('text-danger').removeClass('text-white bg-danger')
        }

        if (tabs.find(tabExistant => tabExistant === evt.target.id) !== undefined) {
            tabCourant.addClass('active text-white bg-danger')
        }
    })

}

agentModal.addEventListener('hide.bs.modal', event => {

    if (window.location.pathname.match("^/agent/[0-9]+/update$") || totalErreur > 0) {

        window.location.href = "/agent"
    }
})

// remplir le salaire de base selon le choix de la fonction
fonctionAgent.change((item) => {
    const id = fonctionAgent.val()
    $.get(`${window.origin}/api/fonctions/${id}`).then((data) => {
        $('#agent_salaire_remuneration_base').val(data.baseSalarial)
    })
})
