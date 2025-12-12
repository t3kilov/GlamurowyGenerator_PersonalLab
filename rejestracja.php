<?php
session_start();

$error_messages = array(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wszystko_OK = true;

    if (empty($_POST['user'])) {
        $error_messages['user'] = "Login jest wymagany";
        $wszystko_OK = false;
    } elseif (strlen($_POST['user']) < 3 || strlen($_POST['user']) > 20) {
        $error_messages['user'] = "Login musi mieć od 3 do 20 znaków";
        $wszystko_OK = false;
    }

    if (empty($_POST['pass'])) {
        $error_messages['pass'] = "Hasło jest wymagane";
        $wszystko_OK = false;
    } elseif (strlen($_POST['pass']) < 8) {
        $error_messages['pass'] = "Hasło musi mieć minimum 8 znaków";
        $wszystko_OK = false;
    } elseif (!preg_match('/[A-Z]/', $_POST['pass'])) {
        $error_messages['pass'] = "Hasło musi zawierać przynajmniej jedną dużą literę";
        $wszystko_OK = false;
    } elseif (!preg_match('/[a-z]/', $_POST['pass'])) {
        $error_messages['pass'] = "Hasło musi zawierać przynajmniej jedną małą literę";
        $wszystko_OK = false;
    } elseif (!preg_match('/[0-9]/', $_POST['pass'])) {
        $error_messages['pass'] = "Hasło musi zawierać przynajmniej jedną cyfrę";
        $wszystko_OK = false;
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $_POST['pass'])) {
        $error_messages['pass'] = "Hasło musi zawierać przynajmniej jeden znak specjalny";
        $wszystko_OK = false;
    }

    if ($_POST['pass'] !== $_POST['pass_confirm']) {
        $error_messages['pass_confirm'] = "Hasła nie są identyczne";
        $wszystko_OK = false;
    }

    if (empty($_POST['email'])) {
        $error_messages['email'] = "Email jest wymagany";
        $wszystko_OK = false;
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error_messages['email'] = "Podaj poprawny adres email";
        $wszystko_OK = false;
    }

    if (empty($_POST['telefon'])) {
        $error_messages['telefon'] = "Numer telefonu jest wymagany";
        $wszystko_OK = false;
    } elseif (!preg_match('/^[0-9]{9}$/', $_POST['telefon'])) {
        $error_messages['telefon'] = "Numer telefonu musi mieć 9 cyfr";
        $wszystko_OK = false;
    }

    if (empty($_POST['imie'])) {
        $error_messages['imie'] = "Imię jest wymagane";
        $wszystko_OK = false;
    } elseif (strlen($_POST['imie']) < 2) {
        $error_messages['imie'] = "Imię musi mieć minimum 2 znaki";
        $wszystko_OK = false;
    }

    if (empty($_POST['nazwisko'])) {
        $error_messages['nazwisko'] = "Nazwisko jest wymagane";
        $wszystko_OK = false;
    } elseif (strlen($_POST['nazwisko']) < 2) {
        $error_messages['nazwisko'] = "Nazwisko musi mieć minimum 2 znaki";
        $wszystko_OK = false;
    }

    if ($wszystko_OK) {
        $polaczenie = new mysqli('localhost', 'root', '', 'glamurowygenerator');
        
        if ($polaczenie->connect_error) {
            die("Connection failed: " . $polaczenie->connect_error);
        }

        $user = $_POST['user'];
        $email = $_POST['email'];
        
        $check_user = $polaczenie->prepare("SELECT nick FROM user WHERE nick = ? OR email = ?");
        $check_user->bind_param("ss", $user, $email);
        $check_user->execute();
        $result = $check_user->get_result();

        if ($result->num_rows > 0) {
            $error_messages['user'] = "Użytkownik o takim loginie lub emailu już istnieje";
            $wszystko_OK = false;
        }
        
        $check_user->close();
        $polaczenie->close();
    }

    if ($wszystko_OK) {
        $hashed_pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        
        $user = $_POST['user'];
        $email = $_POST['email'];
        $telefon = $_POST['telefon'];
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        

        $polaczenie = new mysqli('localhost', 'root', '', 'glamurowygenerator');
        
        if ($polaczenie->connect_error) {
            die("Connection failed: " . $polaczenie->connect_error);
        }

        $stmt = $polaczenie->prepare("INSERT INTO user (nick, pass, email, telefon, imie, nazwisko) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $user, $hashed_pass, $email, $telefon, $imie, $nazwisko);

        if ($stmt->execute()) {
            $_SESSION['zalogowany'] = true;
            $_SESSION['nick'] = $user;
        
            header('Location: index.php');
            exit();
        } else {
            $error_messages['stmt'] = "Błąd podczas rejestracji: " . $stmt->error;
        }
        $stmt->close();
        $polaczenie->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - Glamurowy Generator</title>
    <link rel="stylesheet" href="rejestracja.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&family=Cinzel:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --gold: #D4AF37;
    --rose-gold: #B76E79;
    --deep-purple: #6A0DAD;
    --dark-velvet: #2C003E;
    --light-gold: #FFD700;
    --champagne: #F7E7CE;
    --blush: #F8C8DC;
    --platinum: #E5E4E2;
    --border: rgba(212, 175, 55, 0.3);
}

body {
    font-family: 'Raleway', sans-serif;
    background: linear-gradient(135deg, var(--dark-velvet) 0%, var(--deep-purple) 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    color: var(--champagne);
}

header {
    background: linear-gradient(135deg, 
        rgba(44, 0, 62, 0.9), 
        rgba(107, 13, 173, 0.9));
    padding: 20px 0;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 1200px;
    border-radius: 20px;
    margin-bottom: 30px;
    border: 2px solid var(--border);
}

header h1 {
    font-family: 'Cinzel', serif;
    text-align: center;
    font-size: 2.2em;
    color: var(--light-gold);
    margin-bottom: 15px;
    font-weight: 600;
    text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
}

nav ul {
    list-style: none;
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

nav a {
    text-decoration: none;
    color: var(--champagne);
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.95em;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
}

nav a:hover {
    background: rgba(212, 175, 55, 0.15);
    color: var(--light-gold);
    transform: translateY(-2px);
}

main {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.auth-container {
    max-width: 900px;
    width: 100%;
    margin: 30px auto;
    background: linear-gradient(145deg, 
        rgba(44, 0, 62, 0.9), 
        rgba(107, 13, 173, 0.9));
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
    border: 2px solid var(--border);
}

.auth-title {
    text-align: center;
    color: var(--light-gold);
    font-size: 2em;
    margin-bottom: 35px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-family: 'Playfair Display', serif;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group input {
    padding: 14px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1em;
    transition: all 0.3s ease;
    font-family: 'Raleway', sans-serif;
    background: rgba(44, 0, 62, 0.6);
    color: var(--champagne);
}

.form-group input:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
    background: rgba(44, 0, 62, 0.8);
}

.field-error {
    color: #ffb6c1;
    font-size: 0.85em;
    margin-top: 6px;
    padding-left: 4px;
}

.submit-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--gold), var(--rose-gold));
    color: var(--dark-velvet);
    border: none;
    border-radius: 12px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 25px;
    font-family: 'Raleway', sans-serif;
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
}

.submit-btn:hover {
    background: linear-gradient(135deg, var(--light-gold), var(--blush));
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
}

.auth-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: var(--light-gold);
    text-decoration: none;
    font-weight: 500;
    font-size: 1em;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: var(--gold);
    text-decoration: underline;
    transform: translateX(5px);
}

.error {
    background: linear-gradient(135deg, 
        rgba(220, 20, 60, 0.2), 
        rgba(255, 160, 122, 0.1));
    color: #ffb6c1;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 25px;
    border: 2px solid #ffb6c1;
    animation: fadeIn 0.3s ease;
    backdrop-filter: blur(5px);
}

@media (max-width: 768px) {
    .auth-container {
        margin: 20px;
        padding: 25px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    header h1 {
        font-size: 1.8em;
    }
    
    nav ul {
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }
    
    nav a {
        width: 200px;
        justify-content: center;
    }
    
    body {
        padding: 10px;
    }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>
<main>
<header>
        <h1><i class="fas fa-gem"></i> Glamurowy Generator</h1>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="similarity.php"><i class="fas fa-search"></i> Znajdź podobnego</a></li>
                <li><a href="panel.php"><i class="fas fa-user"></i> Panel użytkownika</a></li>
            </ul>
        </nav>
    </header>

    <div class="auth-container">
        <h1 class="auth-title"><i class="fas fa-user-plus"></i> Rejestracja</h1>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" id="user" name="user" 
                        placeholder="Login" 
                        value="<?= isset($_POST['user']) ? htmlspecialchars($_POST['user']) : '' ?>" 
                        required>
                    <?php if(isset($error_messages['user'])): ?>
                        <div class="field-error"><?= $error_messages['user'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="text" id="imie" name="imie" 
                        placeholder="Imię" 
                        value="<?= isset($_POST['imie']) ? htmlspecialchars($_POST['imie']) : '' ?>" 
                        required>
                    <?php if(isset($error_messages['imie'])): ?>
                        <div class="field-error"><?= $error_messages['imie'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="text" id="nazwisko" name="nazwisko" 
                        placeholder="Nazwisko" 
                        value="<?= isset($_POST['nazwisko']) ? htmlspecialchars($_POST['nazwisko']) : '' ?>" 
                        required>
                    <?php if(isset($error_messages['nazwisko'])): ?>
                        <div class="field-error"><?= $error_messages['nazwisko'] ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <input type="password" id="pass" name="pass" 
                        placeholder="Hasło" required>
                    <?php if(isset($error_messages['pass'])): ?>
                        <div class="field-error"><?= $error_messages['pass'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="password" id="pass_confirm" name="pass_confirm" 
                        placeholder="Powtórz hasło" required>
                    <?php if(isset($error_messages['pass_confirm'])): ?>
                        <div class="field-error"><?= $error_messages['pass_confirm'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <input type="text" id="telefon" name="telefon" 
                        placeholder="Telefon" 
                        value="<?= isset($_POST['telefon']) ? htmlspecialchars($_POST['telefon']) : '' ?>" 
                        required>
                    <?php if(isset($error_messages['telefon'])): ?>
                        <div class="field-error"><?= $error_messages['telefon'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" 
                        placeholder="Email" 
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" 
                        required>
                    <?php if(isset($error_messages['email'])): ?>
                        <div class="field-error"><?= $error_messages['email'] ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <button type="submit" class="submit-btn"><i class="fas fa-user-plus"></i> Zarejestruj się</button>
        </form>
        
        <a href="logowanie.php" class="auth-link"><i class="fas fa-sign-in-alt"></i> Masz już konto? Zaloguj się</a>
    </div>
    </main>
</body>
</html>