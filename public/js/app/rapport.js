// infinite scroll

let ias = new InfiniteAjaxScroll('#tbody', {
    item: '.tr-item',
    next: '.page-item:last-child .page-link',
    pagination: '.pagination',
    spinner: '#spinner1',
});

$('.js-datepicker').datepicker({
    format: 'M-yyyy',
    clearBtn: true,
    language: 'fr-FR',
    viewMode: "months",
    minViewMode: "months"
});
$('#filtre_mois_filtreMois').change(evt => {
    console.log($("form[name='filtre_mois']").submit())
})
