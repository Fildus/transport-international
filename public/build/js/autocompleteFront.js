// const completeIds = [
//     'sr'
// ];
//
// completeIds.forEach(function (e) {
//     if (document.getElementById(e)) {
//         new autoComplete({
//             selector: 'input[id="' + e + '"]',
//             minChars: 3,
//             source: function (term, response) {
//                 $.getJSON('/autocomplete/all?' + e + '=' + term, {},
//                     function (data) {
//                         response(data);
//                     }
//                 );
//             }
//         });
//     }
// });