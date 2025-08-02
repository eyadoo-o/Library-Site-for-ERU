import './bootstrap';

window.themeSwitcher = function () {
    return {
        switchOn: JSON.parse(localStorage.getItem('isDark')) || false,
        switchTheme() {
            if (this.switchOn) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.setItem('isDark', this.switchOn);
        }
    };
};

// Ensure Alpine.js is initialized
document.addEventListener('alpine:init', () => {
    Alpine.data('themeSwitcher', window.themeSwitcher);
});
