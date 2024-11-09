const themeToggle = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const body = document.body;

// Cargar el tema guardado en localStorage
if (localStorage.getItem('theme') === 'dark') {
    body.classList.add('dark-theme');
    themeIcon.classList.replace('bi-moon', 'bi-sun');
}

themeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-theme');

    if (body.classList.contains('dark-theme')) {
        themeIcon.classList.replace('bi-moon', 'bi-sun');
        localStorage.setItem('theme', 'dark');
    } else {
        themeIcon.classList.replace('bi-sun', 'bi-moon');
        localStorage.setItem('theme', 'light');
    }
});
