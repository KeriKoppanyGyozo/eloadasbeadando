<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");


try {
    try {
        $dbh = new PDO('mysql:host=sql311.infinityfree.com;dbname=if0_41613806_pizza', 'if0_41613806', 'Koppany6622', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
    die(json_encode(["error" => "Adatbázis hiba: " . $e->getMessage()]));
}


$dbh->exec("CREATE TABLE IF NOT EXISTS elo_kinalat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    kat VARCHAR(100) NOT NULL
)");


$check = $dbh->query("SELECT COUNT(*) FROM elo_kinalat")->fetchColumn();
if ($check == 0) {
    $kezdoAdatok = [
        ["Áfonyás", "király"], ["Babos", "lovag"], ["Barbecue chicken", "lovag"],
        ["Csupa sajt", "lovag"], ["Erdő kapitánya", "apród"], ["Gombás", "apród"],
        ["Hawaii", "főnemes"], ["Magyaros", "lovag"], ["Mexikói", "főnemes"],
        ["Sajtos", "apród"], ["Sonkás", "apród"]
    ];
    $stmt = $dbh->prepare("INSERT INTO elo_kinalat (nev, kat) VALUES (?, ?)");
    foreach ($kezdoAdatok as $p) {
        $stmt->execute([$p[0], $p[1]]);
    }
}


$input = json_decode(file_get_contents('php://input'), true);
$metodus = $_SERVER['REQUEST_METHOD'];


if ($metodus === 'POST' && isset($input['action'])) {

    if ($input['action'] === 'add') {
        $stmt = $dbh->prepare("INSERT INTO elo_kinalat (nev, kat) VALUES (?, ?)");
        $stmt->execute([htmlspecialchars($input['nev']), htmlspecialchars($input['kat'])]);
    }
    elseif ($input['action'] === 'delete') {
        $stmt = $dbh->prepare("DELETE FROM elo_kinalat WHERE nev = ?");
        $stmt->execute([$input['nev']]);
    }
}


$pizzak = $dbh->query("SELECT nev, kat FROM elo_kinalat ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($pizzak);
?>