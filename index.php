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

// 3. Logika Aksi CRUD (Create & Delete untuk contoh)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    $title = $_POST['title'];
    $year = $_POST['release_year'];
    $pub_id = $_POST['publisher_id'];
    $plat_id = $_POST['platform_id'];
    
    $gameObj->create($title, $year, $pub_id, $plat_id);
    header("Location: index.php"); // Refresh
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $gameObj->delete($_GET['id']);
    header("Location: index.php"); // Refresh
    exit;
}

// 4. Data Fetching
$games = $gameObj->read();
$publishers = $pubObj->read();
$platforms = $platObj->read();

// 5. Memuat View
require_once 'view/header.php';
?>

<h3>Tambahkan Game Baru</h3>
<form method="POST" action="index.php">
    <input type="hidden" name="action" value="create">
    <input type="text" name="title" placeholder="Game Title" required>
    <input type="number" name="release_year" placeholder="Release Year" required>
    
    <select name="publisher_id" required>
        <option value="">-- Select Publisher --</option>
        <?php while ($row = $publishers->fetch(PDO::FETCH_ASSOC)) { ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
    </select>

    <select name="platform_id" required>
        <option value="">-- Select Platform --</option>
        <?php while ($row = $platforms->fetch(PDO::FETCH_ASSOC)) { ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
    </select>
    
    <button type="submit" class="btn">Simpan</button>
</form>

<h3>Daftar Game</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Tahun Rilis</th>
            <th>Publisher</th>
            <th>Platform</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $games->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['release_year']) ?></td>
            <td><?= htmlspecialchars($row['publisher']) ?></td>
            <td><?= htmlspecialchars($row['platform']) ?></td>
            <td>
                <!-- Action Update -->
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn" style="background: #007bff;">Edit</a>
                
                <!-- Action Delete -->
                <a href="index.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php
// Footer
require_once 'view/footer.php';
?>