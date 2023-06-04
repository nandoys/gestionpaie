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