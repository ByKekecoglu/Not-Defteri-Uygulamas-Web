<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM notlar WHERE id = ?");
    $stmt->execute([$id]);
    $not = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $baslik = $_POST['baslik'];
        $icerik = $_POST['icerik'];
        $dosya_yolu = $not['dosya_yolu'];

       
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
                    if ($dosya_yolu && file_exists($dosya_yolu)) {
                        unlink($dosya_yolu); 
                    }
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

        
        $stmt = $conn->prepare("UPDATE notlar SET baslik = ?, icerik = ?, dosya_yolu = ? WHERE id = ?");
        $stmt->execute([$baslik, $icerik, $dosya_yolu, $id]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Düzenle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="light-theme">
    <div class="container">
        <div class="header">
            <h1>Not Düzenle</h1>
            <button id="theme-toggle" class="theme-btn">🌙 Karanlık Tema</button>
        </div>
        <form action="duzenle.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="note-form">
            <input type="text" name="baslik" value="<?php echo htmlspecialchars($not['baslik']); ?>" required>
            <textarea name="icerik" required><?php echo htmlspecialchars($not['icerik']); ?></textarea>
            <label for="dosya">Dosya (isteğe bağlı):</label>
            <input type="file" name="dosya" accept="image/gif,image/png,image/jpeg,video/mp4,audio/mpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            <?php if ($not['dosya_yolu']) { ?>
                <p>Mevcut Dosya: 
                    <?php 
                    $dosya_uzantisi = strtolower(pathinfo($not['dosya_yolu'], PATHINFO_EXTENSION));
                    if (in_array($dosya_uzantisi, ['gif', 'png', 'jpg', 'jpeg'])) {
                        echo '<img src="' . $not['dosya_yolu'] . '" alt="Resim" class="not-medya">';
                    } elseif ($dosya_uzantisi == 'mp4') {
                        echo '<video controls class="not-medya"><source src="' . $not['dosya_yolu'] . '" type="video/mp4"></video>';
                    } elseif ($dosya_uzantisi == 'mp3') {
                        echo '<audio controls class="not-medya"><source src="' . $not['dosya_yolu'] . '" type="audio/mpeg"></audio>';
                    } elseif (in_array($dosya_uzantisi, ['pdf', 'doc', 'docx'])) {
                        echo '<a href="' . $not['dosya_yolu'] . '" download class="download-link">Dosyayı İndir (.' . $dosya_uzantisi . ')</a>';
                    }
                    ?>
                </p>
            <?php } ?>
            <button type="submit" class="submit-btn">Kaydet</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>