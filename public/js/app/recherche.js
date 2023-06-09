const recherche = $('#recherche')

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function rechercheAgent(nom) {
    $.get(`${window.origin}/api/agents?nom=${nom}`).then(data => {
        let lien_agents = []
        if(data['hydra:totalItems'] > 0 && data['hydra:totalItems'] < 5) {
            data['hydra:member'].forEach(agent => {
                lien_agents.push(`
                        <div class="list-group-item list-group-item-action border-bottom border-dark link-primary px-3 py-2">
                            ${agent.nomComplet} 
                            <a href="/agent/${agent.id}/update" class="btn btn-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">modifier</a>
                            <a href="/agent/${agent.id}/paiements" class="btn btn-secondary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">paiements</a>
                        </div>
                    `)
            })
        }
        else if(data['hydra:totalItems'] === 0) {
            lien_agents = '<span>Aucun résultat ne correspond à votre recherche</span>'
        }

        $('#resultat-recherche').html(lien_agents)
    })
}

recherche.keyup((evt) =>{
    if (recherche.val() !== '') {
        rechercheAgent(recherche.val())
    } else {
        $('#resultat-recherche').html('')
    }

})

$('#resultat-recherche').mouseout((evt) => {
   //$('#resultat-recherche').html('')
})

recherche.focusin((evt) => {
    if (recherche.val() !== '') {
        rechercheAgent(recherche.val())
    }
})

