document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"], input[name="phone"]');

    phoneInputs.forEach(input => {
        if (input.readOnly || input.disabled) return;

        input.addEventListener('input', function(e) {
            let val = e.target.value.replace(/\D/g, '');
            
            if (!val) {
                e.target.value = '';
                return;
            }

            if (val[0] === '8') {
                val = '7' + val.substring(1);
            } else if (val[0] !== '7') {
                val = '7' + val;
            }

            if (val.length > 1 && val[1] !== '9') {
                val = '7';
            }

            val = val.substring(0, 11);

            let formatted = '+7 ';
            
            if (val.length > 1) {
                formatted += '(' + val.substring(1, 4);
            }
            if (val.length >= 5) {
                formatted += ') ' + val.substring(4, 7);
            }
            if (val.length >= 8) {
                formatted += '-' + val.substring(7, 9);
            }
            if (val.length >= 10) {
                formatted += '-' + val.substring(9, 11);
            }

            e.target.value = formatted;
        });

        input.addEventListener('focus', function(e) {
            const val = e.target.value.replace(/\D/g, '');
            if (!val || val === '7') {
                e.target.value = '+7 (9';
            }
        });

        input.addEventListener('blur', function(e) {
            const val = e.target.value.replace(/\D/g, '');
            
            if (val === '7' || val === '79') {
                e.target.value = '';
            } else if (val.length > 0 && val.length < 11) {
                e.target.classList.add('error');
            } else {
                e.target.classList.remove('error');
            }
        });
    });
});