new autoComplete({
    selector: 'input[id="location_location"]',
    source: function (term, response) {
        $.getJSON('/autocomplete/', {
                q: term
            },
            function (data) {
                response(data);
            }
        );
    }
});

function fromCountryElt() {
    let fromDeptElt = document.getElementById('fromDept');
    let fromDeptHtmlAll = document.getElementById('fromDept')[1];
    let fromCountryElt = document.getElementById('fromCountry');
    if (fromCountryElt !== null) {
        fromCountryElt.addEventListener('click', function (e) {
            if (e.srcElement.value !== 'null' && e.srcElement.value !== 'all') {
                fetchDataCountry(e)
            } else if (e.srcElement.value === 'all') {
                fromDeptElt.innerHTML = "";
                fromDeptElt.appendChild(fromDeptHtmlAll);
            }
        });

        function fetchDataCountry(e) {
            $.getJSON('/autocomplete?fromCountry=' + e.srcElement.value, {},
                function (data) {
                    fromDeptElt.innerHTML = "";
                    fromDeptElt.appendChild(fromDeptHtmlAll);
                    data.forEach(function (e) {
                        let liElt = document.createElement('option');
                        liElt.value = e.id;
                        liElt.innerText = e.department;
                        fromDeptElt.appendChild(liElt);
                    });
                    if (dataInjection !== null) {
                        if (dataInjection.fromDept !== null) {
                            fromDeptElt.value = dataInjection.fromDept;
                            fromDeptElt.click();
                        }
                    }
                }
            );
        }

        if (dataInjection !== null) {
            if (dataInjection.fromCountry !== null) {
                fromCountryElt.value = dataInjection.fromCountry;
                fromCountryElt.click();
            }
        }
    }
}

fromCountryElt();

function toCountryElt() {
    let toDeptElt = document.getElementById('toDept');
    let toDeptHtmlAll = document.getElementById('toDept')[1];
    let toCountryElt = document.getElementById('toCountry');
    if (toCountryElt !== null) {
        toCountryElt.addEventListener('click', function (e) {
            if (e.srcElement.value !== 'null' && e.srcElement.value !== 'all') {
                fetchDataCountry(e)
            } else if (e.srcElement.value === 'all') {
                toDeptElt.innerHTML = "";
                toDeptElt.appendChild(toDeptHtmlAll);
            }
        });

        function fetchDataCountry(e) {
            $.getJSON('/autocomplete?toCountry=' + e.srcElement.value, {},
                function (data) {
                    toDeptElt.innerHTML = "";
                    toDeptElt.appendChild(toDeptHtmlAll);
                    data.forEach(function (e) {
                        let liElt = document.createElement('option');
                        liElt.value = e.id;
                        liElt.innerText = e.department;
                        toDeptElt.appendChild(liElt);
                    });
                    if (dataInjection !== null) {
                        if (dataInjection.toDept !== null) {
                            toDeptElt.value = dataInjection.toDept;
                            toDeptElt.click();
                        }
                    }
                }
            );
        }

        if (dataInjection !== null) {
            if (dataInjection.toCountry !== null) {
                toCountryElt.value = dataInjection.toCountry;
                toCountryElt.click();
            }
        }
    }
}

toCountryElt();

function typeElt() {
    let typeBElt = document.getElementById('typeB');
    let typeBHtmlAll = document.getElementById('typeB')[1];
    let typeAElt = document.getElementById('typeA');
    if (typeAElt !== null) {
        typeAElt.addEventListener('click', function (e) {
            console.log(e.srcElement.value);
            if (e.srcElement.value !== 'null' && e.srcElement.value !== 'all') {
                fetch(e)
            } else if (e.srcElement.value === 'all') {
                typeBElt.innerHTML = "";
                typeBElt.appendChild(typeBHtmlAll);
            }
        });

        function fetch(e) {
            $.getJSON('/autocomplete?typeA=' + e.srcElement.value, {},
                function (data) {
                    typeBElt.innerHTML = "";
                    typeBElt.appendChild(typeBHtmlAll);
                    data.forEach(function (e) {
                        let liElt = document.createElement('option');
                        liElt.value = e.id;
                        liElt.innerText = e.activity;
                        typeBElt.appendChild(liElt);
                    });
                    if (dataInjection !== null) {
                        if (dataInjection.typeB !== null) {
                            typeBElt.value = dataInjection.typeB;
                            typeBElt.click();
                        }
                    }
                }
            );
        }

        if (dataInjection !== null) {
            if (dataInjection.typeA !== null) {
                typeAElt.value = dataInjection.typeA;
                typeAElt.click();
            }
        }
    }
}

typeElt();