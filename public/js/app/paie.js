var paiement_date = $('#paiement_dateAt')


// champs sur la rémuneration
var paiement_net = $('#paiement_net')
var paiement_base = $('#paiement_base')
var paiement_prime_diplome = $('#paiement_primeDiplome')
var paiement_heure_supplementaire = $('#paiement_heureSupplementaire')

// champs sur les indemnités
var paiement_transport = $('#paiement_transport')
var paiement_logement = $('#paiement_logement ')
var paiement_allocation_familiale = $('#paiement_allocationFamiliale')
var paiement_autres = $('#paiement_autres')


// champs sur les déductions
var paiement_cnss = $('#paiement_cnss')
var paiement_ipr = $('#paiement_ipr')

function setData(selector) {
    
    // stocke le formulaire
    var form = paiement_cnss.closest('form')
    
    data = {}

    if(paiement_date.val() === '') {
        /**
         * cette condition vérifie si une date de paiement est vide
         * Elle va notifier l'utilisateur et ensuite va redéfinir la valeur du champ à une chaîne vide
         */
        alert('Veuillez choisir la date du paiement')
        selector.target.value = ''
        return
    }

    data[paiement_date.attr('name')] = paiement_date.val()

    // données sur la rémuneration
    data[paiement_base.attr('name')] = paiement_base.val()
    data[paiement_net.attr('name')] = paiement_net.val()
    data[paiement_prime_diplome.attr('name')] = paiement_prime_diplome.val()
    data[paiement_heure_supplementaire.attr('name')] = paiement_heure_supplementaire.val()

    // données sur les indemnités
    data[paiement_transport.attr('name')] = paiement_transport.val()
    data[paiement_logement.attr('name')] = paiement_logement.val()
    data[paiement_allocation_familiale.attr('name')] = paiement_allocation_familiale.val()
    data[paiement_autres.attr('name')] = paiement_autres.val()

    // données sur les déductions
    data[paiement_cnss.attr('name')] = paiement_cnss.val()
    data[paiement_ipr.attr('name')] = paiement_ipr.val()

    $.post(form.action, data)

}

/**
 * Ecoute l'événement change du champ paiement_cnss
 * Envoi une requête XMLHttpRequest
 */
paiement_cnss.change(setData)