document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('registration-modal');
    const openBtn = document.getElementById('open-modal-btn');
    const closeBtn = document.querySelector('.close-button');
    const cvFileInput = document.getElementById('cv_file');
    const fileNameDisplay = document.getElementById('file-name-display');

    // Fungsi untuk membuka modal
    const openModal = () => {
        if (modal) {
            modal.classList.add('is-open');
            modal.style.display = "flex";
        }
    };

    // Fungsi untuk menutup modal
    const closeModal = () => {
        if (modal) {
            modal.classList.remove('is-open');
            modal.style.display = "none";
        }
    };

    if (openBtn) {
        openBtn.onclick = openModal;
    }

    if (closeBtn) {
        closeBtn.onclick = closeModal;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
    
    // Tampilkan nama file yang dipilih
    if (cvFileInput && fileNameDisplay) {
        cvFileInput.addEventListener('change', () => {
            if (cvFileInput.files.length > 0) {
                fileNameDisplay.textContent = `File terpilih: ${cvFileInput.files[0].name}`;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    }
});