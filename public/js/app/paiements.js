function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function delBtn(item, url){
    
    var id = item.dataset.id

    item.classList.add("disabled")

    var request = new XMLHttpRequest()
    
    request.open('get', `${window.origin}/api/paiements/${id}`)
    request.setRequestHeader("Accept", "application/json")
    
    request.onload = (data) => {
        var json_data = JSON.parse(data.target.response)
        
        if (confirm(`Voulez-vous supprimer vraiment cet paiement ?`)) {
            window.location.href = url
        }

        item.classList.remove("disabled")
    }

    request.send()

}