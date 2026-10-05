<?php
require_once __DIR__ . '/../config/database.php';

function seedAlat($conn) {
    echo "<h3>Seeding Alat...</h3>";

    $brands = ['Canon', 'Sony', 'Epson', 'Logitech', 'Samsung', 'LG', 'Dell', 'HP', 'Ikea', 'Ace', 'Shure', 'Yamaha'];

    $types_map = [
        1 => ['Laptop', 'Proyektor', 'TV LED', 'Printer Laser'], // Elektronik
        2 => ['Speaker Active', 'Mic Wireless', 'Headphone Studio', 'Mixer Audio'], // Audio
        3 => ['Kamera DSLR', 'Kamera Mirrorless', 'Tripod', 'Lensa', 'Ring Light', 'Green Screen'], // Visual
        4 => ['Kabel HDMI', 'Kabel VGA', 'Kabel Power', 'Kabel LAN 10m', 'Converter HDMI-VGA'], // Kabel
        5 => ['Kursi Staff', 'Meja Rapat', 'Lemari Arsip', 'Whiteboard'], // Furniture
        6 => ['Mouse Wireless', 'Keyboard Mechanical', 'Flashdisk 32GB', 'Harddisk Eksternal', 'Webcam'] // Aksesoris
    ];

    $tools_data = [];
    $total_items = 50;

    for ($i = 1; $i <= $total_items; $i++) {
        $cat_id = rand(1, 6);
        $available_types = $types_map[$cat_id];
        $type = $available_types[array_rand($available_types)];
        $brand = $brands[array_rand($brands)];
        
        $nama_alat = "$brand $type " . sprintf("%03d", rand(1, 999));
        $deskripsi = "Alat $type merk $brand kondisi baik. Inventaris #$i";
        $stok = rand(1, 20);
        
        $gambar = 'default.jpg';
        $image_map = [
            'Canon' => 'canon_700d.png', 'Sony' => 'sony_a6000.png', 'Epson' => 'projector_epson.png',
            'Proyektor' => 'projector_epson.png', 'HDMI' => 'kabel_hdmi.png', 'Kabel' => 'kabel_hdmi.png', 
            'Laptop' => 'laptop_generic.png', 'TV' => 'tv_led.png', 'Printer' => 'printer_laser.png',
            'Speaker' => 'speaker_active.png', 'Mic' => 'mic_wireless.png', 'Headphone' => 'headphone.png',
            'Tripod' => 'tripod.png', 'Kursi' => 'office_chair.png', 'Meja' => 'office_table.png',
            'Mouse' => 'mouse_wireless.png', 'Keyboard' => 'keyboard_mechanical.png', 'Flashdisk' => 'flashdisk.png',
            'Harddisk' => 'harddisk_external.png', 'Webcam' => 'webcam.png', 'Mixer' => 'mixer_audio.png',
            'Ring Light' => 'ring_light.png', 'Lens' => 'sony_a6000.png', 'Green Screen' => 'tripod.png'
        ];

        foreach ($image_map as $keyword => $filename) {
            // Check based on simple string matching
            if (stripos($nama_alat, $keyword) !== false) {
                // If we were strictly checking file existence, we'd do it here. 
                // Assuming assets logic is handled or we just use the name.
                // Replicating original logic:
                 if (file_exists(__DIR__ . '/../assets/img/' . $filename)) {
                    $gambar = $filename;
                }
                break;
            }
        }
        
        $tools_data[] = "($cat_id, '$nama_alat', '$deskripsi', $stok, '$gambar')";
    }

    $chunks = array_chunk($tools_data, 10);
    foreach ($chunks as $chunk) {
        $sql_tools = "INSERT INTO alat (id_kategori, nama_alat, deskripsi, stok, gambar) VALUES " . implode(", ", $chunk);
        if (!mysqli_query($conn, $sql_tools)) {
            echo "[GAGAL] Insert Batch Alat: " . mysqli_error($conn) . "<br>";
        }
    }
    
    echo "[BERHASIL] $total_items Data Alat berhasil digenerate.<br>";
}

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    seedAlat($conn);
}
?>
