function toggle(e) {
    let el = e.nextElementSibling;
    console.log(el.hasAttribute('hidden'));
    if (el.hasAttribute('hidden')) {
        el.removeAttribute('hidden');
    } else {
        el.setAttribute('hidden', 'hidden');
    }
}

const completeIds = [
    'corporateName',
    'legalForm',
    'companyName',
    'siret',
    'address',
    'postalCode',
    'city',
    'location',
    'phone',
    'fax',
    'contact',
    'webSite',
    'location_location',
    'mail'
];

completeIds.forEach(function (e) {
    if (document.getElementById(e)) {
        new autoComplete({
            selector: 'input[id="' + e + '"]',
            minChars: 1,
            source: function (term, response) {
                $.getJSON('/autocomplete/all?'+e+'='+term, {},
                    function (data) {
                        response(data);
                    }
                );
            }
        });
    }
});

