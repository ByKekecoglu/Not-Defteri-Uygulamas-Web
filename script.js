function confirmDelete() {
    return confirm("Bu notu silmek istediğinizden emin misiniz?");
}

const themeToggle = document.getElementById('theme-toggle');
const body = document.body;

const savedTheme = localStorage.getItem('theme') || 'light-theme';
body.classList.add(savedTheme);
updateThemeButton(savedTheme);

themeToggle.addEventListener('click', () => {
    if (body.classList.contains('light-theme')) {
        body.classList.replace('light-theme', 'dark-theme');
        localStorage.setItem('theme', 'dark-theme');
        updateThemeButton('dark-theme');
    } else {
        body.classList.replace('dark-theme', 'light-theme');
        localStorage.setItem('theme', 'light-theme');
        updateThemeButton('light-theme');
    }
});

function updateThemeButton(theme) {
    themeToggle.textContent = theme === 'light-theme' ? '🌙 Karanlık Tema' : '☀️ Açık Tema';
}

document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = e.target.parentElement.querySelector('.preview');
        if (previewContainer) {
            previewContainer.remove();
        }
        if (file) {
            const preview = document.createElement('div');
            preview.className = 'preview';
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (file.type.startsWith('image/') && ['gif', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '100px';
                preview.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.style.maxWidth = '100px';
                preview.appendChild(video);
            } else if (file.type.startsWith('audio/')) {
                const audio = document.createElement('audio');
                audio.src = URL.createObjectURL(file);
                audio.controls = true;
                preview.appendChild(audio);
            } else if (['pdf', 'doc', 'docx'].includes(fileExtension)) {
                const text = document.createElement('p');
                text.textContent = `Seçilen dosya: ${file.name}`;
                preview.appendChild(text);
            }
            e.target.parentElement.appendChild(preview);
        }
    });
});