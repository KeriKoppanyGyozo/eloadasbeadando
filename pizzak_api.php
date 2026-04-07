<?php
header("Content-Type: application/json");
$fajl = 'pizzak.json';

// Adatok betöltése
$adatok = file_exists($fajl) ? json_decode(file_get_contents($fajl), true) : [];
if (!is_array($adatok)) $adatok = [];

// Bemenet beolvasása
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['action'])) {

    if ($input['action'] === 'add') {
        // HOZZÁADÁS
        $adatok[] = [
            "nev" => htmlspecialchars($input['nev']),
            "kat" => htmlspecialchars($input['kat'])
        ];
    }
    elseif ($input['action'] === 'delete') {
        // TÖRLÉS
        $torlendoNev = $input['nev'];
        $ujAdatok = [];
        foreach ($adatok as $pizza) {
            if ($pizza['nev'] !== $torlendoNev) {
                $ujAdatok[] = $pizza;
            }
        }
        $adatok = $ujAdatok;
    }

    // Mentés fájlba
    file_put_contents($fajl, json_encode(array_values($adatok)));
}

// Mindig a jelenlegi listát adjuk vissza
echo json_encode(array_values($adatok));
?>