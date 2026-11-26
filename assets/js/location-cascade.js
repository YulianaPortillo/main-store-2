// selects de pais, departamento y ciudad
var country = document.getElementById('country_id');
var department = document.getElementById('department_id');
var city = document.getElementById('city_id');

// llena un select con lo que devuelve la api
function fill(select, url, text, selected) {
    select.innerHTML = '<option value="">' + text + '</option>';
    select.disabled = true;
    if (!url) {
        return Promise.resolve();
    }
    return fetch(url)
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            data.forEach(function (item) {
                var option = new Option(item.name, item.id);
                if (item.id == selected) {
                    option.selected = true;
                }
                select.add(option);
            });
            select.disabled = false;
        });
}

country.addEventListener('change', function () {
    fill(city, null, 'Seleccione un departamento');
    if (country.value) {
        fill(department, BASE_URL + 'api/departments.php?country_id=' + country.value, 'Seleccione');
    } else {
        fill(department, null, 'Seleccione un país');
    }
});

department.addEventListener('change', function () {
    if (department.value) {
        fill(city, BASE_URL + 'api/cities.php?department_id=' + department.value, 'Seleccione');
    } else {
        fill(city, null, 'Seleccione un departamento');
    }
});

// cuando se edita hay que cargar lo que ya estaba
if (country.value) {
    fill(department, BASE_URL + 'api/departments.php?country_id=' + country.value, 'Seleccione', department.dataset.selected)
        .then(function () {
            if (department.value) {
                fill(city, BASE_URL + 'api/cities.php?department_id=' + department.value, 'Seleccione', city.dataset.selected);
            }
        });
}
