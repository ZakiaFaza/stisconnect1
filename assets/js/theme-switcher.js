document.addEventListener('DOMContentLoaded', () => {
    const themeSwitcher = document.getElementById('theme-switcher');
    const sunIcon = document.getElementById('theme-icon-sun');
    const moonIcon = document.getElementById('theme-icon-moon');
    const currentTheme = localStorage.getItem('theme');

    // Fungsi untuk menerapkan tema
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
            sunIcon.style.display = 'none';
            moonIcon.style.display = 'block';
        } else {
            document.body.classList.remove('dark-mode');
            sunIcon.style.display = 'block';
            moonIcon.style.display = 'none';
        }
    };

    // Terapkan tema yang tersimpan saat halaman dimuat
    if (currentTheme) {
        applyTheme(currentTheme);
    }

    // Event listener untuk tombol
    themeSwitcher.addEventListener('click', () => {
        let theme = 'light';
        if (!document.body.classList.contains('dark-mode')) {
            theme = 'dark';
        }
        localStorage.setItem('theme', theme);
        applyTheme(theme);
    });
});