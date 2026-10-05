function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('profile-image-preview');
            if (preview) {
                preview.src = e.target.result;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const btnEdit = document.getElementById('btn-edit-profil');
    const btnBatal = document.getElementById('btn-batal');
    const actionButtons = document.getElementById('action-buttons');
    const inputs = document.querySelectorAll('.profile-input');
    const fileInput = document.getElementById('gambar');

    const btnGantiFoto = document.getElementById('btn-ganti-foto');

    // Simpan nilai awal untuk tombol batal
    let initialValues = {};
    inputs.forEach(input => {
        initialValues[input.id] = input.value;
    });

    btnEdit.addEventListener('click', function () {
        btnEdit.classList.add('d-none');
        actionButtons.classList.remove('d-none');
        if (btnGantiFoto) btnGantiFoto.classList.remove('d-none'); // Tampilkan tombol ganti foto
        inputs.forEach(input => {
            input.disabled = false;
        });
        inputs[0].focus();
    });

    btnBatal.addEventListener('click', function () {
        btnEdit.classList.remove('d-none');
        actionButtons.classList.add('d-none');
        if (btnGantiFoto) btnGantiFoto.classList.add('d-none'); // Sembunyikan tombol ganti foto
        inputs.forEach(input => {
            input.disabled = true;
            input.value = initialValues[input.id]; // Kembalikan nilai awal
        });
        // Reset file input if needed 
        if (fileInput) fileInput.value = '';
    });
});
