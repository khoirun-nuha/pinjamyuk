/* Global Main JS */

$(document).ready(function () {
    // 1. Init Datatable globally
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            }
        });
    }

    // 2. Global SweetAlert Confirmation Handler
    $(document).on('click', '.confirm-action', function (e) {
        e.preventDefault();
        const link = $(this).attr('href');
        const form = $(this).closest('form');
        const message = $(this).data('message') || 'Apakah Anda yakin ingin melakukan tindakan ini?';
        const title = $(this).data('title') || 'Konfirmasi';
        const icon = $(this).data('icon') || 'warning';

        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if (link && link !== '#' && link !== '') {
                    window.location.href = link;
                } else if (form.length > 0) {
                    // Support form submit
                    form.removeAttr('onsubmit');
                    form.off('submit');
                    // Handle hidden input for button name if needed
                    const btnName = $(this).attr('name');
                    if (btnName) {
                        form.append('<input type="hidden" name="' + btnName + '" value="1">');
                    }
                    form.submit();
                }
            }
        });
    });
});

/**
 * Validasi Input Gambar Global
 * @param {HTMLInputElement} input 
 */
function validateImageInput(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Salah!',
                text: 'Harap hanya upload file gambar (JPG, PNG, GIF).',
                confirmButtonColor: '#d33'
            });
            input.value = ''; // Reset input

            // Reset preview jika ada (untuk profil)
            if (typeof previewImage === 'function') {
                // Cari elemen img preview terkait jika perlu, 
                // tapi karena previewImage biasanya dipanggil onchange, 
                // reset value di atas sudah cukup mencegah previewImage jalan selanjutnya 
                // jika urutan event benar, atau kita reset src manual:
                const imgPreview = document.getElementById('profile-image-preview');
                if (imgPreview) {
                    // Kembalikan ke default atau biarkan gambar lama (reload page required untuk gambar lama perfect, 
                    // tapi minimal jangan tampilkan file salah)
                }
            }
            return false;
        }
    }
    return true;
}
