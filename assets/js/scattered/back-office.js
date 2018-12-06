$(function () {

    $(document).on('click', '#account', function () {

        $("#topbar-menu").toggleClass("show");
        // $("#topbar-menu").toggleClass("hide");
    });
    $(document).on('click', '.submenu', function () {
        $(this).find("#sub-content").toggleClass("show");
        $(this).find("#sub-content").toggleClass("hide");
    });

    new autoComplete({
        selector: 'input[id="corporateName"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    corporateName: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="legalForm"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    legalForm: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="companyName"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    companyName: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="siret"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    siret: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="address"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    address: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="postalCode"]',
        minChars: 1,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    postalCode: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="city"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    city: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="location"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    location: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="phone"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    phone: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="fax"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    fax: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="contact"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    contact: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

    new autoComplete({
        selector: 'input[id="webSite"]',
        minChars: 3,
        source: function (term, response) {
            $.getJSON('/autocomplete/all', {
                    webSite: term
                },
                function (data) {
                    response(data);
                }
            );
        }
    });

});


