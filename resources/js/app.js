import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const initializePinInputs = () => {
    const groups = document.querySelectorAll('[data-pin-group]');

    groups.forEach((group) => {
        const inputs = group.querySelectorAll('[data-pin-input]');
        const hiddenField = group.querySelector('[data-pin-hidden]');

        if (!inputs.length || !hiddenField) {
            return;
        }

        const syncHiddenValue = () => {
            hiddenField.value = Array.from(inputs)
                .map((input) => input.value.replace(/\D/g, ''))
                .join('');
        };

        inputs.forEach((input, index) => {
            input.maxLength = 1;
            input.inputMode = 'numeric';
            input.autocomplete = 'one-time-code';
            input.pattern = '[0-9]*';

            input.addEventListener('input', (event) => {
                const value = event.target.value.replace(/\D/g, '').slice(-1);
                event.target.value = value;

                if (value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                syncHiddenValue();
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                    syncHiddenValue();
                }

                if (event.key === 'ArrowLeft' && index > 0) {
                    inputs[index - 1].focus();
                }

                if (event.key === 'ArrowRight' && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', (event) => {
                event.preventDefault();

                const pasted = (event.clipboardData || window.clipboardData)
                    .getData('text')
                    .replace(/\D/g, '')
                    .slice(0, inputs.length);

                if (!pasted) {
                    return;
                }

                pasted.split('').forEach((char, position) => {
                    if (inputs[position]) {
                        inputs[position].value = char;
                    }
                });

                const targetIndex = Math.min(pasted.length, inputs.length - 1);
                inputs[targetIndex].focus();
                syncHiddenValue();
            });

            input.addEventListener('focus', () => input.select());
        });

        syncHiddenValue();
    });
};

window.addEventListener('DOMContentLoaded', initializePinInputs);
