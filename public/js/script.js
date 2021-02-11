/* MAIN PAGE */

if(window.location.pathname.indexOf('/specialites.vlabs') > -1 || window.location.pathname == '/') {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let specialites = JSON.parse(this.responseText)
            displayResults(specialites)
        }
    };
    xhttp.open("GET", "http://127.0.0.1:8000/api.specialites.vlabs/all_specialites", true);
    xhttp.send();

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let tags = JSON.parse(this.responseText)
            displayTags(tags)

            document.getElementById('btn-submit').addEventListener('click', function (e) {
                applyFilters()
            })
        }
    };
    xhttp.open("GET", "http://127.0.0.1:8000/api.specialites.vlabs/all_tags", true);
    xhttp.send();
}

function displayResults(array) {
    document.getElementById('liste-specialites').innerHTML = ''

    array.forEach(element => {
        var div = document.createElement('div')
        div.classList.add('specialite-card')
        var subdiv = document.createElement('div')
        div.append(subdiv)

        var p1 = document.createElement('p')
        p1.innerText += element.libelle
        subdiv.append(p1)

        if(element.region !== null) {
            var p2 = document.createElement('p')
            p2.innerText += element.region.libelle
            subdiv.append(p2)
        }

        if(element.tag.length > 0) {
            var p3 = document.createElement('p')
            element.tag.forEach(element => {
                p3.innerText += element.libelle+', '
            });
            subdiv.append(p3)
        }

        if(element.image !== 'undefined') {
            var img = document.createElement('img')
            img.classList.add('img-responsive')
            img.src += 'images/'+element.image
            div.append(img)
        }

        document.getElementById('liste-specialites').append(div)
    });
}

function displayTags(array) {
    array.forEach(element => {
        var p = document.createElement('p')

        var input = document.createElement('input')
        input.type = 'checkbox'
        input.value = element.id

        var label = document.createElement('label')
        label.innerText += element.libelle

        p.append(input, label)

        document.getElementById('liste-tags').append(p)
    });
}

function applyFilters() {
    let tagInputs = document.getElementById('liste-tags').getElementsByTagName('input')
    let textInput = document.getElementById('input-text')

    checkedTags = []
    for (var i = 0; i < tagInputs.length; i++) {
        if(tagInputs[i].checked == true)
            checkedTags.push(tagInputs[i])
    }

    if(checkedTags.length > 0 && textInput.value != "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let specialites = JSON.parse(this.responseText)
                displayResults(specialites)
            }
        };

        let tagsId = ''
        checkedTags.forEach(element => {
            tagsId += element.value+'-'
        })
        tagsId = tagsId.slice(0, -1)

        xhttp.open("GET", "http://127.0.0.1:8000/api.specialites.vlabs/specialites_by_tags_plus_libelle/"+tagsId+"/"+textInput.value, true);
        xhttp.send();
    }

    if(checkedTags.length > 0 && textInput.value == "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let specialites = JSON.parse(this.responseText)
                displayResults(specialites)
            }
        };

        let tagsId = ''
        checkedTags.forEach(element => {
            tagsId += element.value+'-'
        })
        tagsId = tagsId.slice(0, -1)

        xhttp.open("GET", "http://127.0.0.1:8000/api.specialites.vlabs/specialites_by_tags/"+tagsId, true);
        xhttp.send();
    }

    if(checkedTags.length == 0 && textInput.value != "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let specialites = JSON.parse(this.responseText)
                displayResults(specialites)
            }
        };

        xhttp.open("GET", "http://127.0.0.1:8000/api.specialites.vlabs/specialites_by_libelle/"+textInput.value, true);
        xhttp.send();
    }

    if(checkedTags.length == 0 && textInput.value == "")
        return false
}