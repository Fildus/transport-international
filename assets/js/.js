function toggleOpening(self, ul) {
    if (self.innerHTML.trim() === '<i class="fas fa-plus"></i>') {
        self.innerHTML = '<i class="fas fa-minus"></i>';
    } else {
        self.innerHTML = '<i class="fas fa-plus"></i>';
    }
    let ulElt = document.getElementById(ul);
    if (!ulElt.hasAttribute('hidden')) {
        ulElt.setAttribute('hidden', 'hidden');
    } else {
        ulElt.removeAttribute('hidden')
    }
}

function toggleSelection(input, ul) {
    !input.getAttribute('checked') ? check(input) & toggle(1, ul) : uncheck(input) & toggle(0, ul);

    function toggle(toggle, ul) {
        document.getElementById(ul).querySelectorAll('input').forEach(function (Elt) {
            toggle === 0 ? uncheck(Elt) : check(Elt);
        });
    }

    function check(attr) {
        console.log('ok');
        attr.setAttribute('checked', 'checked');
        attr['checked'] = true;
    }

    function uncheck(attr) {
        attr.removeAttribute('checked');
        attr['checked'] = false;
    }
}

/**
 * When application ready. We check attributres of checkbox to construcu a proper tree, checked and unchecked
 */
document.onreadystatechange = function () {
    if (document.readyState === "complete") {
        function setCheckedUlElts(name) {
            let Elt = document.getElementsByClassName(name);
            for (let i = 0; i < Elt.length; i++) {
                [].slice.call(Elt[i].getElementsByTagName('ul')).forEach(function (e) {
                    let isFull = true;
                    [].slice.call(e.getElementsByClassName('checkbox-form')).forEach(function (eB) {
                        if (!eB.hasAttribute('checked')) {
                            isFull = false;
                        }
                    });
                    if (isFull) {
                        if (e.parentNode.getElementsByClassName('structure-form')[0] !== undefined) {
                            let parent = e.parentNode.getElementsByClassName('structure-form')[0];
                            parent.setAttribute('checked', 'checked');
                            parent['checked'] = true;
                        }
                    } else {
                        let parent = e.parentNode.getElementsByClassName('structure-form')[0];
                        parent.removeAttribute('checked');
                        parent['checked'] = false;
                    }
                });
            }
        }

        setCheckedUlElts('checkboxCascade');

        /**
         * To be sure checkebox are really checked we change property for each one
         */
        [].slice.call(document.getElementsByClassName('checkbox-form')).forEach(function (e) {
            e.addEventListener('click', function (e) {
                if (e.srcElement.hasAttribute('checked')) {
                    e.srcElement.removeAttribute('checked');
                    e.srcElement['checked'] = false;
                } else {
                    e.srcElement.setAttribute('checked', 'checked');
                    e.srcElement['checked'] = true;
                }
            })
        });

        /**
         * For each change we check the whole tree
         */
        [].slice.call(document.getElementsByClassName('checkboxCascade')).forEach(function (e) {
            [].slice.call(e.getElementsByTagName('input')).forEach(function (e) {
                e.addEventListener('click', function () {
                    setCheckedUlElts('checkboxCascade');
                });
            })
        });
    }
};