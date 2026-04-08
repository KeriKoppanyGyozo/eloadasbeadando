<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// --- 1. JAVÍTÁS: A tanár által kért PDO Adatbázis kapcsolat ---
// Ide majd a tárhelyes regisztráció után be kell írnod a kapott adataidat!
try {
    $dbh = new PDO('mysql:host=localhost;dbname=adatb', 'adatbf', '****', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    die(json_encode(["error" => "Adatbázis hiba: " . $e->getMessage()]));
}

// --- 2. JAVÍTÁS: JSON fájl helyett MySQL Tábla létrehozása ---
$dbh->exec("CREATE TABLE IF NOT EXISTS elo_kinalat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    kat VARCHAR(100) NOT NULL
)");

// --- 3. JAVÍTÁS: Kezdőadatok betöltése a MySQL táblába ---
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

// Bemenet beolvasása a JavaScriptből
$input = json_decode(file_get_contents('php://input'), true);
$metodus = $_SERVER['REQUEST_METHOD'];

// --- 4. JAVÍTÁS: Hozzáadás és Törlés MySQL parancsokkal (INSERT és DELETE) ---
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

// --- 5. JAVÍTÁS: Adatok lekérése a MySQL-ből (SELECT) és visszaküldése ---
$pizzak = $dbh->query("SELECT nev, kat FROM elo_kinalat ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($pizzak);
?>