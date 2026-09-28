import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const darkModeEnabled = localStorage.getItem('office-stock-theme') === 'dark';
document.documentElement.classList.toggle('dark-mode', darkModeEnabled);

Alpine.store('theme', {
    dark: darkModeEnabled,
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark-mode', this.dark);
        localStorage.setItem('office-stock-theme', this.dark ? 'dark' : 'light');
    },
});

Alpine.start();

const updateCambodiaClock = () => {
    const now = new Date();
    const date = new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Phnom_Penh',
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(now);
    const time = new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Phnom_Penh',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
    }).format(now);

    document.querySelectorAll('[data-cambodia-date]').forEach((element) => {
        element.textContent = date;
    });
    document.querySelectorAll('[data-cambodia-time]').forEach((element) => {
        element.textContent = time;
    });
};

updateCambodiaClock();
window.setInterval(updateCambodiaClock, 60_000);

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
