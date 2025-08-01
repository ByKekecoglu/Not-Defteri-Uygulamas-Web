<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];
    $dosya_yolu = null;

   
    if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] == UPLOAD_ERR_OK) {
        $dosya_uzantisi = strtolower(pathinfo($_FILES['dosya']['name'], PATHINFO_EXTENSION));
        $gecerli_uzantilar = ['gif', 'png', 'jpg', 'jpeg', 'mp4', 'mp3', 'pdf', 'doc', 'docx'];
        
        if (in_array($dosya_uzantisi, $gecerli_uzantilar)) {
            $yeni_dosya_adi = uniqid() . '.' . $dosya_uzantisi;
            $hedef_yol = __DIR__ . '/uploads/' . $yeni_dosya_adi;
            
            
            if (!is_dir(__DIR__ . '/uploads/')) {
                mkdir(__DIR__ . '/uploads/', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['dosya']['tmp_name'], $hedef_yol)) {
                $dosya_yolu = 'uploads/' . $yeni_dosya_adi;
            } else {
                echo "Dosya yüklenemedi! Hata: ";
                print_r(error_get_last());
                exit;
            }
        } else {
            echo "Sadece GIF, PNG, JPG, JPEG, MP4, MP3, PDF, DOC ve DOCX dosyaları yüklenebilir!";
            exit;
        }
    } elseif (isset($_FILES['dosya']) && $_FILES['dosya']['error'] != UPLOAD_ERR_NO_FILE) {
        echo "Dosya yükleme hatası: " . $_FILES['dosya']['error'];
        exit;
    }

   
    $stmt = $conn->prepare("INSERT INTO notlar (baslik, icerik, dosya_yolu) VALUES (?, ?, ?)");
    $stmt->execute([$baslik, $icerik, $dosya_yolu]);

    header("Location: index.php");
    exit;
}
?>