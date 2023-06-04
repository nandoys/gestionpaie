var paiement_date = $('#paiement_dateAt')

// notification
var toast_notification = $('#notification')
var toast_notification_body = $('#notification-body')


// champs sur la rémuneration
var paiement_net = $('#paiement_net')
var paiement_deduction = $('#paiement_precedente_deduction')

var paiement_base = $('#paiement_base')
var paiement_prime_diplome = $('#paiement_primeDiplome')
var paiement_heure_supplementaire = $('#paiement_heureSupplementaire')
var remunerations = new Array(paiement_base, paiement_prime_diplome, paiement_heure_supplementaire);

// champs sur les indemnités
var paiement_transport = $('#paiement_transport')
var paiement_logement = $('#paiement_logement ')
var paiement_allocation_familiale = $('#paiement_allocationFamiliale')
var paiement_autres = $('#paiement_autres')
var indemnites = new Array(paiement_transport, paiement_logement, paiement_allocation_familiale, paiement_autres);


// champs sur les déductions
var paiement_cnss = $('#paiement_cnss')
var paiement_ipr = $('#paiement_ipr')
var paiement_avance_salaire = $('#paiement_avanceSalaire')
var paiement_pret_logement = $('#paiement_pretLogement')
var paiement_pret_frais_scolaire = $('#paiement_pretFraisScolaire')
var paiement_pret_deuil = $('#paiement_pretDeuil')
var paiement_pret_autre = $('#paiement_pretAutre')
var paiement_abscence = $('#paiement_abscence')

function calculBrutImposable() {
    var result = 0

    remunerations.forEach(remuneration => {
        result += Number.parseFloat(remuneration.val())
    })

    return result
}

function calculTotalIndemnite() {
    var result = 0

    indemnites.forEach(indemnite => {
        result += Number.parseFloat(indemnite.val())
    })

    return result
}

function calculSalaireBrut() {
    const gains = new Array(calculBrutImposable(), calculTotalIndemnite());

    var result = 0

    gains.forEach(gain => {
        result += Number.parseFloat(gain)
    })

    return result
}

function calculDeduction(selector) {

    var deductions = new Array(paiement_cnss, paiement_ipr, paiement_avance_salaire, paiement_pret_logement, paiement_pret_frais_scolaire,
        paiement_pret_deuil, paiement_pret_autre, paiement_abscence);

    var result = 0

    selector = $(`#${selector.target.id}`)

    console.log(selector.attr('data-deducted'))

    deductions.forEach(deduction => {

        /* vérifier si le montant a déjà été déduit pour le sauter dans le calcul,
         * parce que cela fait partie du montant déduit précédemment

        if(parseFloat(selector.attr('data-deducted')) === 0) {
            if(deduction.attr('id') !== 'paiement_precedente_deduction') {
                result += parseFloat(deduction.val())
            }
        } else {
            const montantDeduit = parseFloat(selector.attr('data-deducted'))
            const montantADeduire = parseFloat(selector.val())

            if(deduction.attr('id') === 'paiement_precedente_deduction') {
                let montantDeduction = parseFloat(deduction.attr('data-deduction'))

                if (montantDeduit > montantADeduire) {
                    const difference = montantDeduit - montantADeduire
                    montantDeduction -= difference
                }
                else {
                    const addition =  montantADeduire - montantDeduit
                    montantDeduction += addition
                }
                deduction.attr('data-deduction-update', montantDeduction)
                deduction.text(`${montantDeduction.toLocaleString()} FC`)

                result += montantDeduction
            }
        }
        */
        result += parseFloat(deduction.val())
    })

    return result
}

function calculNetAPayer(selector) {
    const salaire_net = calculSalaireBrut() - calculDeduction(selector)

    if (isNaN(salaire_net)) {
        toast_notification.addClass('text-bg-danger')

        toast_notification_body.text("Impossible de calculer le salaire net à payer, il y a des valeurs manquantes et/ou non valides")

        new bootstrap.Toast(toast_notification, { delay: 5000 }).show()

        return
    }
    return salaire_net
}


/**
 * Définit les données et envoi une requête XMLHttpRequest
 * @param {*} selector 
 * @returns 
 */
function setData(selector) {

    const salaireNet = calculNetAPayer(selector)

    if (paiement_date.val() === '') {
        /**
         * cette condition vérifie si une date de paiement est vide
         * Elle va notifier l'utilisateur et ensuite va redéfinir la valeur du champ à une chaîne vide
         */

        toast_notification.addClass('text-bg-danger')

        toast_notification_body.text("Veuillez choisir la date du paiement")

        new bootstrap.Toast(toast_notification, { delay: 4000 }).show()

        selector.target.value = ''
        return
    }

    salaireNet !== undefined ? $('#paiement_net').text(`${salaireNet.toLocaleString()} FC`) : $('#paiement_net').text(`??? FC`)

}

/**
 * Ecoute l'événement change des champ et délenche une fonction
 */

paiement_base.change(setData)
paiement_prime_diplome.change(setData)
paiement_heure_supplementaire.change(setData)

// champs sur les indemnités
paiement_transport.change(setData)
paiement_logement.change(setData)
paiement_allocation_familiale.change(setData)
paiement_autres.change(setData)


// champs sur les déductions
paiement_cnss.change(setData)
paiement_ipr.change(setData)
paiement_avance_salaire.change(setData)
paiement_pret_logement.change(setData)
paiement_pret_frais_scolaire.change(setData)
paiement_pret_deuil.change(setData)
paiement_pret_autre.change(setData)
paiement_abscence.change(setData)


// infinite scroll

let ias = new InfiniteAjaxScroll('#offcanvasRight', {
    item: '.list-group-item',
    next: '.page-item:last-child .page-link',
    pagination: '#pagination',
    spinner: '.spinner-border',
});