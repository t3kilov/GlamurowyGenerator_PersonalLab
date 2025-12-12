<?php
session_start();

$error_messages = array(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $polaczenie = new mysqli('localhost', 'root', '', 'glamurowygenerator');

    if ($polaczenie->connect_error) {
        die("Connection failed: " . $polaczenie->connect_error);
    }

    $login = $polaczenie->real_escape_string($_POST['login']);
    $haslo = $_POST['haslo'];

    if (empty($login)) {
        $error_messages[] = "Pole login jest wymagane";
    }
    if (empty($haslo)) {
        $error_messages[] = "Pole hasło jest wymagane";
    }

    if (empty($error_messages)) {
        $query = "SELECT * FROM user WHERE nick = '$login'";
        $wynik = $polaczenie->query($query);

        if ($wynik->num_rows > 0) {
            $wiersz = $wynik->fetch_assoc();
            if (password_verify($haslo, $wiersz['pass'])) {
                $_SESSION['zalogowany'] = true;
                $_SESSION['user_id'] = $wiersz['id'];
                $_SESSION['nick'] = $wiersz['nick'];
                header('Location: panel.php');
                exit();
            } else {
                $error_messages[] = "Nieprawidłowe hasło";
            }
        } else {
            $error_messages[] = "Nie znaleziono użytkownika";
        }
    }

    $polaczenie->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie - Glamurowy Generator</title>
    <link rel="stylesheet" href="logowanie.css">
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
    max-width: 500px;
    width: 100%;
    margin: 40px auto;
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

.form-group {
    margin-bottom: 20px;
}

.form-group input {
    width: 100%;
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
    margin-top: 15px;
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
        margin: 25px 20px;
        padding: 25px;
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
    from { opacity: 0; }
    to { opacity: 1; }
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
        <h1 class="auth-title"><i class="fas fa-sign-in-alt"></i> Logowanie</h1>

        <?php if (!empty($error_messages)): ?>
            <div class="error">
                <?php foreach ($error_messages as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <input type="text" name="login" placeholder="Login" 
                       value="<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>">
            </div>
            
            <div class="form-group">
                <input type="password" name="haslo" placeholder="Hasło">
            </div>
            
            <button type="submit" class="submit-btn"><i class="fas fa-sign-in-alt"></i> Zaloguj się</button>
        </form>
        
        <a href="rejestracja.php" class="auth-link"><i class="fas fa-user-plus"></i> Nie masz konta? Zarejestruj się</a>
    </div>
    </main>
</body>
</html>