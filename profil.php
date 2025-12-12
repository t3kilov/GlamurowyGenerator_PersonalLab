<?php
session_start();

$login = $_SESSION['nick'] ?? '';

if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true || $login === 'Admin') {
    header('Location: logowanie.php');
    exit();
}
$nick = $_SESSION['nick'];
$polaczenie = new mysqli('localhost', 'root', '', 'glamurowygenerator');
if ($polaczenie->connect_error) {
    die("Connection failed: " . $polaczenie->connect_error);
}

$hashQuery = $polaczenie->query("SELECT pass FROM user WHERE nick = '$nick'");
$hashRow   = $hashQuery ? $hashQuery->fetch_assoc() : null;
$aktualnyHash = $hashRow['pass'] ?? '';

$imie = $nazwisko = $email = $telefon = '';
$error_messages  = [];
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['imie']) || strlen($_POST['imie']) < 2) {
        $error_messages[] = "Imię musi mieć minimum 2 znaki";
    }
    if (empty($_POST['nazwisko']) || strlen($_POST['nazwisko']) < 2) {
        $error_messages[] = "Nazwisko musi mieć minimum 2 znaki";
    }
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Podaj prawidłowy adres email";
    }
    if (empty($_POST['telefon']) || !preg_match('/^[0-9]{9}$/', $_POST['telefon'])) {
        $error_messages[] = "Numer telefonu musi składać się z 9 cyfr";
    }

    $stare_haslo    = $_POST['stare_haslo']    ?? '';
    $nowe_haslo     = $_POST['nowe_haslo']     ?? '';
    $powtorz_haslo  = $_POST['powtorz_haslo']  ?? '';

    if ($nowe_haslo !== '' || $powtorz_haslo !== '') {
        if ($stare_haslo === '') {
            $error_messages[] = "Aby ustawić nowe hasło, podaj najpierw stare.";
        }
        elseif (!password_verify($stare_haslo, $aktualnyHash)) {
            $error_messages[] = "Stare hasło jest niepoprawne.";
        }

        if (strlen($nowe_haslo) < 6) {
            $error_messages[] = "Nowe hasło musi mieć minimum 6 znaków";
        } elseif ($nowe_haslo !== $powtorz_haslo) {
            $error_messages[] = "Hasła nie są zgodne";
        }
    }

    if (empty($error_messages)) {
        $imie     = $polaczenie->real_escape_string($_POST['imie']);
        $nazwisko = $polaczenie->real_escape_string($_POST['nazwisko']);
        $email    = $polaczenie->real_escape_string($_POST['email']);
        $telefon  = $polaczenie->real_escape_string($_POST['telefon']);

        $check = $polaczenie->query(
            "SELECT id FROM user WHERE (email = '$email' OR telefon = '$telefon') AND nick != '$nick'"
        );
        if ($check->num_rows > 0) {
            $error_messages[] = "Email lub telefon już istnieje u innego użytkownika.";
        }

        if (empty($error_messages)) {
            $query = "UPDATE user SET Imie='$imie', Nazwisko='$nazwisko', email='$email', telefon='$telefon'";

            if ($nowe_haslo !== '') {
                $haslo_hash = password_hash($nowe_haslo, PASSWORD_DEFAULT);
                $query .= ", pass='$haslo_hash'";
            }

            $query .= " WHERE nick='$nick'";

            if ($polaczenie->query($query)) {
                $success_message = "Dane zostały zaktualizowane pomyślnie!";
            } else {
                $error_messages[] = "Błąd podczas aktualizacji danych: " . $polaczenie->error;
            }
        }
    }
}

$result = $polaczenie->query(
    "SELECT Imie, Nazwisko, email, telefon FROM user WHERE nick = '$nick'"
);
if ($result && $result->num_rows > 0) {
    $row      = $result->fetch_assoc();
    $imie     = $row['Imie'];
    $nazwisko = $row['Nazwisko'];
    $email    = $row['email'];
    $telefon  = $row['telefon'];
} else {
    echo "Błąd pobierania danych.";
}

$similar_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_submit'])) {
    $input = [
        'imie'=>'','nazwisko'=>'','email'=>'','telefon'=>'',
        'country'=>'','age'=>null
    ];

    $input['imie'] = $_POST['check_imie'] ?? '';
    $input['nazwisko'] = $_POST['check_nazwisko'] ?? '';
    $input['email'] = $_POST['check_email'] ?? '';
    $input['telefon'] = $_POST['check_telefon'] ?? '';
    $input['country'] = $_POST['check_country'] ?? '';
    $input['age'] = ($_POST['check_age'] !== '' ? intval($_POST['check_age']) : null);
    $input['segment'] = $_POST['check_segment'] ?? '';

    $in_country = trim(mb_strtolower($input['country'] ?? ''));
    $in_age = is_numeric($input['age']) ? intval($input['age']) : null;
    $in_segment = trim($input['segment'] ?? '');

    $escapedNick = $polaczenie->real_escape_string($nick);
    $q = $polaczenie->query("SELECT id, Imie, Nazwisko, email, telefon, nick FROM user WHERE nick != '$escapedNick'");
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $similar_results[] = [
                'nick' => $r['nick'] ?? '',
                'Imie' => $r['Imie'] ?? '',
                'Nazwisko' => $r['Nazwisko'] ?? '',
                'email' => $r['email'] ?? '',
                'telefon' => $r['telefon'] ?? '',
                'picture' => '',
                'country' => '',
                'age' => null,
                'segment' => ''
            ];
        }
    }
}

$polaczenie->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj Profil - Glamurowy Generator</title>
    <link rel="stylesheet" href="profil.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <h1 id="konto"><a href="index.php" class="home-button"><i class="fas fa-crown"></i> Konto</a></h1>
    <a href="index.php" class="menu-item"><i class="fas fa-home"></i> Główna</a>
    <a href="profil.php" class="menu-item active"><i class="fas fa-cog"></i> Ustawienia</a>
    <a href="#sprawdz" class="menu-item"><i class="fas fa-search"></i> Znajdź podobnego</a>
    <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Wyloguj się</a>
</div>

<div class="content">
    <div class="settings-section">
        <?php if (!empty($error_messages)): ?>
            <div class="alert alert-danger">
                <?php foreach ($error_messages as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <p><?= $success_message ?></p>
            </div>
        <?php endif; ?>

        <h3><i class="fas fa-user-edit"></i> Twoje dane</h3>
        <form method="POST" class="settings-form">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Imię</label>
                    <input type="text" name="imie" value="<?= htmlspecialchars($imie) ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nazwisko</label>
                    <input type="text" name="nazwisko" value="<?= htmlspecialchars($nazwisko) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> E-mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Telefon</label>
                    <div class="phone-input">
                        <input type="text" class="phone-prefix" value="+48" disabled>
                        <input type="text" name="telefon" class="phone-number" value="<?= htmlspecialchars($telefon) ?>">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Obecne hasło</label>
                    <input type="password" name="stare_haslo">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-key"></i> Nowe hasło</label>
                    <input type="password" name="nowe_haslo">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-key"></i> Powtórz hasło</label>
                    <input type="password" name="powtorz_haslo">
                </div>
            </div>

            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Zapisz zmiany</button>
        </form>
    </div>

    <div class="check-section" id="sprawdz">
        <h3><i class="fas fa-search"></i> Znajdź podobnego w API</h3>
        <p class="small-note">Wprowadź swoje dane i znajdź podobne persony w naszej bazie API</p>
        <form method="POST" class="check-form">
            <div style="display:flex; gap:12px; margin-bottom:12px; flex-wrap:wrap;">
                <input id="check_imie" type="text" name="check_imie" placeholder="Imię" style="flex:1; min-width:200px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
                <input id="check_nazwisko" type="text" name="check_nazwisko" placeholder="Nazwisko" style="flex:1; min-width:200px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
            </div>
            <div style="display:flex; gap:12px; margin-bottom:12px; flex-wrap:wrap;">
                <input id="check_email" type="email" name="check_email" placeholder="Email" style="flex:1; min-width:200px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
                <input id="check_telefon" type="text" name="check_telefon" placeholder="Telefon" style="flex:1; min-width:200px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
            </div>
            <div style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
                <input id="check_country" type="text" name="check_country" placeholder="Kraj" style="flex:1; min-width:200px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
                <input id="check_age" type="number" name="check_age" placeholder="Wiek" style="width:120px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
                <select id="check_segment" name="check_segment" style="flex:1; min-width:200px; padding:12px; border:2px solid #e0d6f0; border-radius:12px; background:rgba(255,255,255,0.9);">
                    <option value="">-- Segment --</option>
                    <option value="Młodzież">Młodzież</option>
                    <option value="Student">Student</option>
                    <option value="Profesjonalista">Profesjonalista</option>
                    <option value="Rodzic">Rodzic</option>
                    <option value="Senior">Senior</option>
                </select>
            </div>
            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button id="check_submit_btn" type="submit" name="check_submit" style="padding:12px 24px; border-radius:12px; background:linear-gradient(135deg, #9370db, #8a2be2); color:#fff; border:none; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-search"></i> Znajdź podobnego
                </button>
            </div>
        </form>

        <div id="apiResults" style="margin-top:25px;"></div>

        <?php if (!empty($similar_results)): ?>
            <div style="margin-top:25px;">
                <h4><i class="fas fa-users"></i> Podobni użytkownicy w bazie</h4>
                <p style="margin:10px 0; color:#7a6994;">
                    Znaleziono: <strong><?= count($similar_results) ?></strong> podobnych użytkowników
                </p>

                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="text-align:left; border-bottom:2px solid #e0d6f0;">
                                <th style="padding:12px">#</th>
                                <th style="padding:12px">Nick</th>
                                <th style="padding:12px">Imię</th>
                                <th style="padding:12px">Nazwisko</th>
                                <th style="padding:12px">Email</th>
                                <th style="padding:12px">Telefon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach($similar_results as $sr): ?>
                                <tr style="border-bottom:1px solid #f3f6fb;">
                                    <td style="padding:12px; vertical-align:middle;"><?= $i++ ?></td>
                                    <td style="padding:12px"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($sr['nick']) ?></td>
                                    <td style="padding:12px"><?= htmlspecialchars($sr['Imie']) ?></td>
                                    <td style="padding:12px"><?= htmlspecialchars($sr['Nazwisko']) ?></td>
                                    <td style="padding:12px"><i class="fas fa-envelope"></i> <?= htmlspecialchars($sr['email']) ?></td>
                                    <td style="padding:12px"><i class="fas fa-phone"></i> <?= htmlspecialchars($sr['telefon']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php elseif (isset($_POST['check_submit'])): ?>
            <div style="margin-top:25px; color:#8a6bb8; text-align:center; padding:20px; background:rgba(147, 112, 219, 0.05); border-radius:12px;">
                <i class="fas fa-info-circle fa-2x" style="margin-bottom:10px;"></i>
                <p>Brak podobnych użytkowników w bazie danych</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.check-form');
    const apiResults = document.getElementById('apiResults');
    
    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                imie: formData.get('check_imie') || '',
                nazwisko: formData.get('check_nazwisko') || '',
                email: formData.get('check_email') || '',
                telefon: formData.get('check_telefon') || '',
                country: formData.get('check_country') || '',
                age: formData.get('check_age') || '',
                segment: formData.get('check_segment') || ''
            };
            
            apiResults.innerHTML = '<div style="text-align:center;padding:20px;color:#8a6bb8;"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Szukam podobnych person w API...</p></div>';
            
            try {
                const response = await fetch('https://randomuser.me/api/?results=20');
                if (!response.ok) throw new Error('Błąd API');
                
                const result = await response.json();
                const persons = result.results || [];
                
                const filteredPersons = persons.filter(person => {
                    const matches = [];
                    
                    if (data.imie && person.name.first.toLowerCase().includes(data.imie.toLowerCase())) matches.push('imię');
                    if (data.nazwisko && person.name.last.toLowerCase().includes(data.nazwisko.toLowerCase())) matches.push('nazwisko');
                    if (data.email && person.email.toLowerCase().includes(data.email.toLowerCase())) matches.push('email');
                    if (data.country && person.location.country.toLowerCase().includes(data.country.toLowerCase())) matches.push('kraj');
                    if (data.age && person.dob.age == parseInt(data.age)) matches.push('wiek');
                    
                    return matches.length > 0;
                });
                
                if (filteredPersons.length === 0) {
                    apiResults.innerHTML = '<div style="text-align:center;padding:20px;color:#8a6bb8;"><i class="fas fa-user-slash fa-2x"></i><p>Nie znaleziono podobnych person w API</p></div>';
                    return;
                }
                
                let html = `
                    <div style="margin-top:20px;">
                        <h4><i class="fas fa-user-friends"></i> Podobne persony z API (${filteredPersons.length})</h4>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:20px;margin-top:20px;">
                `;
                
                filteredPersons.forEach((person, index) => {
                    html += `
                        <div style="background:linear-gradient(145deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));padding:20px;border-radius:15px;box-shadow:0 8px 20px rgba(147,112,219,0.1);border:1px solid rgba(255,255,255,0.4);">
                            <div style="display:flex;gap:15px;align-items:center;margin-bottom:15px;">
                                <img src="${person.picture.medium}" alt="${person.name.first}" style="width:60px;height:60px;border-radius:12px;border:3px solid #9370db;">
                                <div>
                                    <h5 style="color:#5d437c;margin:0;">${person.name.first} ${person.name.last}</h5>
                                    <p style="color:#8a6bb8;margin:5px 0 0 0;font-size:0.9em;">
                                        <i class="fas fa-map-marker-alt"></i> ${person.location.country}
                                        <i class="fas fa-birthday-cake" style="margin-left:10px;"></i> ${person.dob.age} lat
                                    </p>
                                </div>
                            </div>
                            <div style="color:#7a6994;font-size:0.9em;">
                                <p><i class="fas fa-envelope"></i> ${person.email}</p>
                                <p><i class="fas fa-phone"></i> ${person.phone}</p>
                                <p><i class="fas fa-city"></i> ${person.location.city}, ${person.location.state}</p>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div></div>';
                apiResults.innerHTML = html;
                
            } catch (error) {
                apiResults.innerHTML = '<div style="text-align:center;padding:20px;color:#ff3860;"><i class="fas fa-exclamation-triangle fa-2x"></i><p>Błąd podczas łączenia z API</p></div>';
            }
        });
    }
});
</script>
</body>
</html>