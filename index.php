<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Defteri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="light-theme">
    <div class="container">
        <div class="header">
            <h1>Not Defteri</h1>
            <button id="theme-toggle" class="theme-btn">🌙 Karanlık Tema</button>
        </div>

        
        <form action="ekle.php" method="POST" enctype="multipart/form-data" class="note-form">
            <input type="text" name="baslik" placeholder="Not Başlığı" required>
            <textarea name="icerik" placeholder="Not İçeriği" required></textarea>
            <label for="dosya">Dosya (isteğe bağlı):</label>
            <input type="file" name="dosya" accept="image/gif,image/png,image/jpeg,video/mp4,audio/mpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            <button type="submit" class="submit-btn">Not Ekle</button>
        </form>

        
        <h2>Notlarım</h2>
        <div class="notlar">
            <?php
            include 'config.php';
            $stmt = $conn->query("SELECT * FROM notlar ORDER BY olusturma_tarihi DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="not">';
                echo '<h3>' . htmlspecialchars($row['baslik']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['icerik']) . '</p>';
                if ($row['dosya_yolu']) {
                    $dosya_uzantisi = strtolower(pathinfo($row['dosya_yolu'], PATHINFO_EXTENSION));
                    if (in_array($dosya_uzantisi, ['gif', 'png', 'jpg', 'jpeg'])) {
                        echo '<img src="' . $row['dosya_yolu'] . '" alt="Resim" class="not-medya">';
                    } elseif ($dosya_uzantisi == 'mp4') {
                        echo '<video controls class="not-medya"><source src="' . $row['dosya_yolu'] . '" type="video/mp4"></video>';
                    } elseif ($dosya_uzantisi == 'mp3') {
                        echo '<audio controls class="not-medya"><source src="' . $row['dosya_yolu'] . '" type="audio/mpeg"></audio>';
                    } elseif (in_array($dosya_uzantisi, ['pdf', 'doc', 'docx'])) {
                        echo '<a href="' . $row['dosya_yolu'] . '" download class="download-link">Dosyayı İndir (.' . $dosya_uzantisi . ')</a>';
                    }
                }
                echo '<small>' . $row['olusturma_tarihi'] . '</small>';
                echo '<div class="actions">';
                echo '<a href="duzenle.php?id=' . $row['id'] . '" class="edit-btn">Düzenle</a>';
                echo '<a href="sil.php?id=' . $row['id'] . '" onclick="return confirmDelete();" class="delete-btn">Sil</a>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>