// Ensure Bootstrap Tabs work if not standard included in footer
document.addEventListener('DOMContentLoaded', function () {
    var triggerTabList = [].slice.call(document.querySelectorAll('#verifikasiTab button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })

    // --- LOGIC MODAL DETAIL ---
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
    const btnDetails = document.querySelectorAll('.btn-detail');

    btnDetails.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const peminjam = this.getAttribute('data-peminjam');
            const items = JSON.parse(this.getAttribute('data-items'));
            const type = this.getAttribute('data-type'); // 'kembali' or undefined (pinjam)

            // 1. Set Header Info
            document.getElementById('modalPeminjamName').textContent = peminjam;
            document.getElementById('modalTransId').textContent = '#' + id;

            // 2. Render Items
            const container = document.getElementById('modalItemsContainer');
            container.innerHTML = ''; // Clear previous

            items.forEach(item => {
                const card = `
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded-3 shadow-sm bg-white hover-shadow transition-all">
                            <div class="flex-shrink-0 me-3">
                                <img src="${item.gambar}" class="rounded-3 object-fit-cover shadow-sm" 
                                     alt="${item.nama}" style="width: 70px; height: 70px;">
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

            // 3. Render Footer Buttons (Clone logic from PHP)
            const footer = document.getElementById('modalFooterActions');

            if (type === 'kembali') {
                // Logic for "Kembali" (Return) Tab
                // Note: The original PHP used a FORM for return action. We need to mimic that if we want to support it in modal.
                // However, simply closing and letting user use the table button is safer to avoid complex form injection.
                // OR we can inject a simple "Close" button since duplicate "Terima" might be redundant inside modal unless requested.
                // For now, let's keep it simple: Just Close. 
                // The prompt asked for "Detail", not necessarily "Action in Detail".
                // But for "Pinjam", the table link is simple GET, so we can replicate.

                // For "Kembali", since it's a POST form with hidden inputs, it's harder to replicate in JS without building a form.
                // We will just show "Tutup".
                footer.innerHTML = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>`;

            } else {
                // Logic for "Pinjam" (Borrow) Tab - GET Links
                // <a href="index.php?page=verifikasi&aksi=dipinjam&id=..." ...>Setuju</a>
                const btnSetuju = `
                    <a href="index.php?page=verifikasi&aksi=dipinjam&id=${id}" 
                       class="btn btn-success confirm-action-modal">
                       <i class="fas fa-check me-1"></i> Setuju
                    </a>`;

                const btnTolak = `
                    <a href="index.php?page=verifikasi&aksi=ditolak&id=${id}" 
                       class="btn btn-danger confirm-action-modal">
                       <i class="fas fa-times me-1"></i> Tolak
                    </a>`;

                footer.innerHTML = `
                    ${btnTolak}
                    ${btnSetuju}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                `;
            }

            modalDetail.show();
        });
    });

    // Re-attach confirmation for dynamically created buttons (Optional, or rely on global delegation if exists)
    // Assuming 'confirm-action' logic is global. If it uses event delegation (document.on...), it works.
    // If it binds only on load, we need to manually trigger check.
    // Let's rely on standard link behavior first.
});
