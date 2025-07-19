document.addEventListener('DOMContentLoaded', () => {

    const eventListContainer = document.getElementById('event-list');
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('search-suggestions');
    let debounceTimer;

    /**
     * Fungsi utama untuk mengambil dan menampilkan event di grid utama.
     * @param {string} searchTerm - Kata kunci untuk memfilter event. Kosongkan untuk menampilkan semua.
     */
    async function fetchAndDisplayEvents(searchTerm = '') {
        eventListContainer.innerHTML = '<div class="loader"></div>';
        const apiUrl = `api/events.php?search=${encodeURIComponent(searchTerm)}`;

        try {
            const response = await fetch(apiUrl);
            const events = await response.json();
            displayEventsInGrid(events);
        } catch (error) {
            console.error("Gagal mengambil data event:", error);
            eventListContainer.innerHTML = '<p>Gagal memuat event. Coba lagi nanti.</p>';
        }
    }

    /**
     * Fungsi untuk mengambil saran dan menampilkannya di suggestion box.
     * @param {string} searchTerm - Kata kunci dari input pengguna.
     */
    async function fetchAndDisplaySuggestions(searchTerm) {
        if (searchTerm.length < 2) { // Hanya cari jika input lebih dari 1 karakter
            suggestionsContainer.style.display = 'none';
            return;
        }
        const apiUrl = `api/events.php?search=${encodeURIComponent(searchTerm)}`;
        try {
            const response = await fetch(apiUrl);
            const events = await response.json();
            
            suggestionsContainer.innerHTML = ''; // Kosongkan saran sebelumnya
            if (events.length > 0) {
                events.forEach(event => {
                    const item = document.createElement('div');
                    item.className = 'suggestion-item';
                    
                    // Highlight bagian yang cocok
                    const regex = new RegExp(`(${searchTerm})`, 'gi');
                    item.innerHTML = event.name.replace(regex, '<strong>$1</strong>');

                    item.addEventListener('click', () => {
                        searchInput.value = event.name; // Isi search bar dengan nama event
                        suggestionsContainer.style.display = 'none'; // Sembunyikan suggestion box
                        fetchAndDisplayEvents(event.name); // Filter grid utama
                    });
                    suggestionsContainer.appendChild(item);
                });
                suggestionsContainer.style.display = 'block';
            } else {
                suggestionsContainer.style.display = 'none';
            }
        } catch (error) {
            console.error("Gagal mengambil saran:", error);
        }
    }

    /**
     * Fungsi pembantu untuk render event ke dalam grid.
     * @param {Array} events - Array objek event.
     */
    function displayEventsInGrid(events) {
        eventListContainer.innerHTML = '';
        if (events.length === 0) {
            eventListContainer.innerHTML = '<p>Tidak ada event yang cocok ditemukan.</p>';
            return;
        }
        events.forEach(event => {
            const eventDate = new Date(event.event_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
            const shortDescription = event.description.length > 100 ? event.description.substring(0, 100) + '...' : event.description;
            const recruitmentStatus = event.recruitment_open ? '<span class="status open">Rekrutmen Dibuka</span>' : '<span class="status closed">Rekrutmen Ditutup</span>';
            const eventCard = document.createElement('div');
            eventCard.className = 'event-card';
            eventCard.innerHTML = `<h3>${event.name}</h3><div class="date">${eventDate}</div><p>${shortDescription}</p>${recruitmentStatus}`;
            eventCard.addEventListener('click', () => { window.location.href = `event_detail.php?id=${event.id}`; });
            eventListContainer.appendChild(eventCard);
        });
    }

    // Event listener untuk input pencarian
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            debounceTimer = setTimeout(() => fetchAndDisplaySuggestions(searchTerm), 300);
        } else {
            suggestionsContainer.style.display = 'none';
            fetchAndDisplayEvents(); // Jika input kosong, tampilkan semua event lagi
        }
    });

    // Sembunyikan suggestion box jika mengklik di luar
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });

    // Panggil fungsi untuk memuat semua event saat halaman pertama kali dibuka
    fetchAndDisplayEvents();
});