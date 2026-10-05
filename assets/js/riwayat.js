document.addEventListener('DOMContentLoaded', function () {
    // Lazy element fetching wrapper
    const modalElement = document.getElementById('modalDetailHistory');
    let modalDetailHistory = null;

    // EVENT DELEGATION: Gunakan document body agar bisa menangkap klik 
    // pada elemen yang dibuat dinamis oleh DataTables (paginasi/search)
    document.body.addEventListener('click', function (e) {
        // Cari elemen terdekat dengan class .btn-detail-history (handle klik di icon dalam button)
        const btn = e.target.closest('.btn-detail-history');

        if (btn) {
            e.preventDefault(); // Mencegah navigasi jika ada href '#'

            // Pastikan Bootstrap sudah load
            if (typeof bootstrap === 'undefined') {
                console.error("Bootstrap JS belum dimuat!");
                return;
            }

            // Init modal jika belum
            if (!modalDetailHistory && modalElement) {
                modalDetailHistory = new bootstrap.Modal(modalElement);
            }

            if (!modalDetailHistory) {
                console.error("Modal element not found!");
                return;
            }

            const id = btn.getAttribute('data-id');
            const tgl = btn.getAttribute('data-tgl');

            // Parse JSON dengan error handling
            let items = [];
            try {
                // Ambil string JSON
                const rawItems = btn.getAttribute('data-items');
                if (rawItems) {
                    items = JSON.parse(rawItems);
                }
            } catch (err) {
                console.error("Gagal parse items:", err);
                return;
            }

            // 1. Header
            const elTgl = document.getElementById('histModalTgl');
            const elId = document.getElementById('histModalId');
            if (elTgl) elTgl.textContent = tgl;
            if (elId) elId.textContent = '#' + id;

            // 2. Items
            const container = document.getElementById('histModalContainer');
            if (container) {
                container.innerHTML = '';

                items.forEach(item => {
                    const card = `
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center p-3 border rounded-3 shadow-sm bg-white hover-shadow transition-all">
                                <div class="flex-shrink-0 me-3">
                                    <img src="${item.gambar}" class="rounded-3 object-fit-cover shadow-sm" 
                                         alt="${item.nama}" style="width: 70px; height: 70px;"
                                         onerror="this.src='../assets/img/default-alat.jpg'">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">${item.nama}</h6>
                                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        <i class="fas fa-box me-1"></i> Jumlah: ${item.jumlah}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', card);
                });
            }

            // 3. Show Modal
            modalDetailHistory.show();
        }
    });
});
