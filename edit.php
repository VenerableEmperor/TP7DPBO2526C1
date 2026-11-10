<?php
// 1. Pemanggilan Config & Class
require_once 'config/Database.php';
require_once 'class/Game.php';
require_once 'class/Publisher.php';
require_once 'class/Platform.php';

// 2. Inisialisasi Koneksi (Dependency Injection)
$database = new Database();
$db = $database->getConnection();

$gameObj = new Game($db);
$pubObj = new Publisher($db);
$platObj = new Platform($db);

// 3. Logika Aksi Update (Diproses ketika form disubmit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $year = $_POST['release_year'];
    $pub_id = $_POST['publisher_id'];
    $plat_id = $_POST['platform_id'];
    
    // Memanggil fungsi update dari Game.php (Prepared statement)
    if ($gameObj->update($id, $title, $year, $pub_id, $plat_id)) {
        header("Location: index.php"); // Kembali ke halaman utama setelah sukses
        exit;
    } else {
        echo "Gagal mengupdate data.";
    }
}

// 4. Mengambil data saat ini berdasarkan parameter GET ?id=...
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$currentGame = $gameObj->readOne($id);

// Jika game tidak ditemukan di database
if (!$currentGame) {
    echo "Data Game tidak ditemukan!";
    exit;
}

// Mengambil data referensi untuk Dropdown (Select)
$publishers = $pubObj->read();
$platforms = $platObj->read();

// 5. Memuat View
require_once 'view/header.php';
?>

<h3>Edit Game</h3>
<form method="POST" action="edit.php?id=<?= $id ?>">
    <input type="hidden" name="action" value="update">
    <!-- Mengirim ID game yang sedang diedit -->
    <input type="hidden" name="id" value="<?= $currentGame['id'] ?>">
    
    <label>Title</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($currentGame['title']) ?>" required><br><br>
    
    <label>Release Year</label><br>
    <input type="number" name="release_year" value="<?= htmlspecialchars($currentGame['release_year']) ?>" required><br><br>
    
    <label>Publisher</label><br>
    <select name="publisher_id" required>
        <?php while ($row = $publishers->fetch(PDO::FETCH_ASSOC)) { 
            // Cek apakah publisher_id dari loop sama dengan data saat ini, jika ya beri status 'selected'
            $selected = ($row['id'] == $currentGame['publisher_id']) ? "selected" : "";
        ?>
            <option value="<?= $row['id'] ?>" <?= $selected ?>><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
    </select><br><br>

    <label>Platform</label><br>
    <select name="platform_id" required>
        <?php while ($row = $platforms->fetch(PDO::FETCH_ASSOC)) { 
            // Cek apakah platform_id dari loop sama dengan data saat ini, jika ya beri status 'selected'
            $selected = ($row['id'] == $currentGame['platform_id']) ? "selected" : "";
        ?>
            <option value="<?= $row['id'] ?>" <?= $selected ?>><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
    </select><br><br>
    
    <button type="submit" class="btn">Update Data</button>
    <a href="index.php" class="btn" style="background: #6c757d;">Batal</a>
</form>

<?php
// Footer
require_once 'view/footer.php';
?>