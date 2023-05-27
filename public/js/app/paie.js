var paiement_cnss = $('#paiement_cnss')

var paiement_net = $('#paiement_net')
//var paiement_date = $('#paiement_date')

/**
 * Ecoute l'événement change du champ paiement_cnss
 * Envoi une requête XMLHttpRequest
 */
paiement_cnss.change(function() {
    
    // stocke le formulaire
    var form = paiement_cnss.closest('form')
    
    data = {}

    data[paiement_cnss.attr('name')] = paiement_cnss.val()
    data[paiement_net.attr('name')] = paiement_net.val()
    //data[paiement_date.attr('name')] = paiement_date.val()

    $.post(form.action, data)

})