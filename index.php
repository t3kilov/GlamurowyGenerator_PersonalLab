<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glamurowy Generator - Random Personas Lab</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Raleway:wght@300;400;500;600&family=Cinzel:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gold: #D4AF37;
            --rose-gold: #B76E79;
            --deep-purple: #6A0DAD;
            --dark-velvet: #2C003E;
            --light-gold: #FFD700;
            --champagne: #F7E7CE;
            --blush: #F8C8DC;
            --platinum: #E5E4E2;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(135deg, var(--dark-velvet) 0%, var(--deep-purple) 50%, #4B0082 100%);
            min-height: 100vh;
            color: var(--champagne);
            background-attachment: fixed;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(183, 110, 121, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(107, 13, 173, 0.1) 0%, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }

        header {
            background: linear-gradient(135deg, 
                rgba(44, 0, 62, 0.95) 0%, 
                rgba(107, 13, 173, 0.95) 100%);
            padding: 25px 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid var(--gold);
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(45deg, 
                    transparent, 
                    transparent 10px, 
                    rgba(212, 175, 55, 0.1) 10px, 
                    rgba(212, 175, 55, 0.1) 20px);
            pointer-events: none;
        }

        header h1 {
            font-family: 'Cinzel', serif;
            text-align: center;
            font-size: 3.8em;
            color: var(--gold);
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            letter-spacing: 3px;
            position: relative;
            display: inline-block;
            width: 100%;
        }

        header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 3px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--gold), 
                transparent);
        }

        header h1 i {
            color: var(--light-gold);
            margin-right: 15px;
            animation: sparkle 2s infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 35px;
            flex-wrap: wrap;
            padding: 15px 0;
            position: relative;
            z-index: 1;
        }

        nav a {
            text-decoration: none;
            color: var(--champagne);
            font-weight: 500;
            transition: all 0.4s ease;
            padding: 12px 28px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            position: relative;
            overflow: hidden;
            font-family: 'Playfair Display', serif;
        }

        nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(212, 175, 55, 0.2), 
                transparent);
            transition: left 0.6s;
        }

        nav a:hover::before {
            left: 100%;
        }

        nav a:hover, nav a.active {
            background: linear-gradient(135deg, 
                rgba(212, 175, 55, 0.2), 
                rgba(183, 110, 121, 0.2));
            color: var(--light-gold);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
            border-color: var(--gold);
        }

        nav a i {
            font-size: 1.2em;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 50px 25px;
        }

        .hero-section {
            text-align: center;
            background: linear-gradient(145deg, 
                rgba(44, 0, 62, 0.85), 
                rgba(107, 13, 173, 0.85));
            padding: 60px 40px;
            border-radius: 30px;
            margin-bottom: 50px;
            box-shadow: 
                0 20px 50px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            position: relative;
            overflow: hidden;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .hero-section::before {
            content: '✦';
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2em;
            color: var(--gold);
            opacity: 0.3;
        }

        .hero-section::after {
            content: '✦';
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 2em;
            color: var(--gold);
            opacity: 0.3;
        }

        .hero-section h2 {
            font-size: 3.2em;
            color: var(--light-gold);
            margin-bottom: 25px;
            font-family: 'Playfair Display', serif;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
        }

        .hero-section p {
            font-size: 1.3em;
            color: var(--champagne);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.7;
            font-weight: 300;
        }

        .control-panel {
            display: flex;
            gap: 25px;
            margin-bottom: 35px;
            align-items: stretch;
            flex-wrap: wrap;
        }

        .settings-card {
            flex: 1;
            min-width: 350px;
            background: linear-gradient(145deg, 
                rgba(44, 0, 62, 0.9), 
                rgba(107, 13, 173, 0.9));
            border-radius: 25px;
            padding: 35px;
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.4);
            position: relative;
            overflow: hidden;
        }

        .settings-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                var(--gold), 
                var(--rose-gold), 
                var(--gold));
        }

        .settings-card h3 {
            color: var(--light-gold);
            margin: 0 0 30px 0;
            font-size: 1.8em;
            font-family: 'Playfair Display', serif;
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
        }

        .form-group {
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-group label {
            color: var(--champagne);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1em;
        }

        .form-group label i {
            color: var(--light-gold);
            width: 20px;
            text-align: center;
        }

        .form-group input[type="number"], 
        .form-group input[type="text"], 
        .form-group select, 
        .form-group input[type="file"] {
            height: 52px;
            padding: 15px 20px;
            border-radius: 15px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            background: rgba(44, 0, 62, 0.6);
            font-family: 'Raleway', sans-serif;
            font-size: 1em;
            color: var(--champagne);
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            outline: none;
            background: rgba(44, 0, 62, 0.8);
            transform: translateY(-2px);
        }

        .form-group input[type="checkbox"] {
            width: 22px;
            height: 22px;
            accent-color: var(--gold);
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, 
                var(--gold) 0%, 
                var(--rose-gold) 100%);
            color: var(--dark-velvet);
            border: none;
            padding: 16px 35px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Playfair Display', serif;
            font-size: 1.1em;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            transition: left 0.6s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.4);
            background: linear-gradient(135deg, 
                var(--light-gold) 0%, 
                var(--blush) 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, 
                rgba(107, 13, 173, 0.8) 0%, 
                rgba(75, 0, 130, 0.8) 100%);
            color: var(--champagne);
            border: 2px solid rgba(212, 175, 55, 0.4);
            padding: 16px 35px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Playfair Display', serif;
            font-size: 1.1em;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, 
                rgba(183, 110, 121, 0.8) 0%, 
                rgba(212, 175, 55, 0.8) 100%);
            border-color: var(--gold);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }

        .actions-bar {
            display: flex;
            gap: 20px;
            margin: 35px 0;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
            background: linear-gradient(145deg, 
                rgba(44, 0, 62, 0.8), 
                rgba(107, 13, 173, 0.8));
            padding: 25px;
            border-radius: 20px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-action {
            background: rgba(44, 0, 62, 0.7);
            border: 2px solid var(--gold);
            color: var(--light-gold);
            padding: 14px 28px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 500;
            font-family: 'Raleway', sans-serif;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-action::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(212, 175, 55, 0.1), 
                transparent);
            transition: left 0.6s;
        }

        .btn-action:hover::after {
            left: 100%;
        }

        .btn-action:hover {
            background: var(--gold);
            color: var(--dark-velvet);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }

        .btn-action i {
            font-size: 1.2em;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 1200px) { .card-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .card-grid { grid-template-columns: repeat(1, 1fr); } }

        .persona-card {
            background: linear-gradient(145deg, 
                rgba(44, 0, 62, 0.9), 
                rgba(107, 13, 173, 0.9));
            border-radius: 25px;
            padding: 25px;
            display: flex;
            gap: 25px;
            align-items: flex-start;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            font-size: 16px;
            position: relative;
            min-height: 140px;
            transition: all 0.4s ease;
            border: 2px solid rgba(212, 175, 55, 0.3);
            cursor: pointer;
            overflow: hidden;
        }

        .persona-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                var(--gold), 
                var(--rose-gold), 
                var(--gold));
        }

        .persona-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.5),
                0 0 30px rgba(212, 175, 55, 0.2);
            border-color: var(--gold);
        }

        .persona-id {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 12px;
            color: var(--light-gold);
            background: rgba(212, 175, 55, 0.15);
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            font-weight: 500;
            z-index: 1;
        }

        .persona-avatar {
            width: 100px;
            height: 100px;
            flex: 0 0 100px;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--gold), var(--rose-gold));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
            border: 3px solid var(--gold);
            position: relative;
            z-index: 1;
        }

        .persona-avatar::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                var(--gold), 
                var(--rose-gold), 
                var(--light-gold), 
                var(--gold));
            border-radius: 22px;
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .persona-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 17px;
        }

        .persona-meta {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .persona-meta .name {
            font-weight: 700;
            color: var(--light-gold);
            font-size: 20px;
            font-family: 'Playfair Display', serif;
            margin-bottom: 5px;
        }

        .persona-meta .sub {
            color: var(--champagne);
            font-size: 14px;
        }

        .persona-meta .line {
            color: var(--platinum);
            font-size: 14px;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .persona-meta .tag {
            background: linear-gradient(135deg, 
                rgba(212, 175, 55, 0.15), 
                rgba(183, 110, 121, 0.15));
            color: var(--light-gold);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-message {
            padding: 20px 25px;
            border-radius: 15px;
            margin: 25px 0;
            font-weight: 500;
            text-align: center;
            display: none;
            animation: slideDown 0.4s ease;
            backdrop-filter: blur(10px);
            border: 2px solid;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .status-message.success {
            background: linear-gradient(135deg, 
                rgba(46, 139, 87, 0.2), 
                rgba(152, 251, 152, 0.1));
            color: #90ee90;
            border-color: #90ee90;
        }

        .status-message.error {
            background: linear-gradient(135deg, 
                rgba(220, 20, 60, 0.2), 
                rgba(255, 160, 122, 0.1));
            color: #ffb6c1;
            border-color: #ffb6c1;
        }

        .status-message.info {
            background: linear-gradient(135deg, 
                rgba(70, 130, 180, 0.2), 
                rgba(176, 224, 230, 0.1));
            color: #add8e6;
            border-color: #add8e6;
        }

        .edit-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(145deg, 
                rgba(44, 0, 62, 0.95), 
                rgba(107, 13, 173, 0.95));
            padding: 35px;
            border-radius: 25px;
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(212, 175, 55, 0.3);
            z-index: 1000;
            min-width: 400px;
            backdrop-filter: blur(20px);
            border: 2px solid var(--gold);
            animation: modalAppear 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes modalAppear {
            from { opacity: 0; transform: translate(-50%, -60%) scale(0.9); }
            to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 900;
            backdrop-filter: blur(5px);
        }

        .chart-container {
            background: linear-gradient(145deg, 
                rgba(44, 0, 62, 0.9), 
                rgba(107, 13, 173, 0.9));
            padding: 40px;
            border-radius: 25px;
            margin-top: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.6s ease;
        }

        .chart-container h3 {
            color: var(--light-gold);
            margin-bottom: 30px;
            text-align: center;
            font-size: 2em;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2.5em;
            }
            
            .hero-section h2 {
                font-size: 2.2em;
            }
            
            .actions-bar {
                flex-direction: column;
            }
            
            .btn-action {
                width: 100%;
                min-width: auto;
            }
            
            .settings-card {
                min-width: 100%;
                padding: 25px;
            }
            
            .persona-card {
                flex-direction: column;
                gap: 20px;
            }
            
            .persona-avatar {
                width: 120px;
                height: 120px;
                margin: 0 auto;
            }
        }

        .glamour-decoration {
            position: fixed;
            pointer-events: none;
            z-index: -1;
        }

        .decoration-1 {
            top: 10%;
            left: 5%;
            font-size: 3em;
            color: rgba(212, 175, 55, 0.1);
            transform: rotate(45deg);
        }

        .decoration-2 {
            bottom: 10%;
            right: 5%;
            font-size: 3em;
            color: rgba(183, 110, 121, 0.1);
            transform: rotate(-45deg);
        }
    </style>
</head>
<body>
    <div class="glamour-decoration decoration-1">✦</div>
    <div class="glamour-decoration decoration-2">✦</div>

    <header>
        <h1><i class="fas fa-gem"></i> Glamurowy Generator</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="active"><i class="fas fa-sparkles"></i> Ekskluzywna Galeria</a></li>
                <li><a href="panel.php"><i class="fas fa-crown"></i> Prywatna Loża</a></li>
                <li><a href="similarity.php"><i class="fas fa-search-plus"></i> Znajdź Alter Ego</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="hero-section">
            <h2>Ekskluzywny Generator Osobistości</h2>
            <p>Generuj persony "glamurowe" z segmentacją VIP i analizą demograficzną w stylu haute couture</p>
        </div>

        <div class="control-panel">
            <div class="settings-card">
                <h3><i class="fas fa-sliders-h"></i> Ustawienia Ekskluzywne</h3>
                <div style="display: flex; flex-direction: column; gap: 25px;">

                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label><i class="fas fa-user-friends"></i> Limit Osobistości:</label>
                            <input type="number" id="personCount" min="1" max="1000" value="9">
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label><i class="fas fa-globe-europe"></i> Preferowany Kraj:</label>
                            <select id="filterCountry">
                                <option value="">Wszystkie Destynacje</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label>
                                <input type="checkbox" id="maskData" checked>
                                <i class="fas fa-user-secret"></i> Tryb Dyskrecji
                            </label>
                        </div>
                    </div>
                    

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end;">
                        <div style="display: flex; gap: 15px; align-items: center;">
                            <div class="form-group" style="min-width: 140px;">
                                <label><i class="fas fa-hourglass-start"></i> Wiek od:</label>
                                <input type="number" id="filterAgeMin" min="18" max="99" placeholder="18">
                            </div>
                            <div class="form-group" style="min-width: 140px;">
                                <label><i class="fas fa-hourglass-end"></i> Wiek do:</label>
                                <input type="number" id="filterAgeMax" min="18" max="99" placeholder="65">
                            </div>
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label><i class="fas fa-chess-queen"></i> Segment VIP:</label>
                            <select id="filterSegment">
                                <option value="">Wszystkie Kategorie</option>
                            </select>
                        </div>
                    </div>
                    
    
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end;">
                        <div class="form-group" style="flex: 2; min-width: 300px;">
                            <label><i class="fas fa-file-import"></i> Importuj Profil VIP (CSV):</label>
                            <input type="file" id="segmentsFile" accept=".csv" style="padding: 12px;">
                            <small style="color: var(--light-gold); font-size: 0.85em; margin-top: 5px;">
                                Format: segment;rule (np. "Elita;wiek≥30")
                            </small>
                        </div>
                        <div style="display: flex; gap: 15px; margin-left: auto;">
                            <button onclick="applyFilters()" class="btn-secondary">
                                <i class="fas fa-filter"></i> Filtruj
                            </button>
                            <button onclick="generateFromAPI()" class="btn-primary">
                                <i class="fas fa-magic"></i> Generuj Osobistości
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions-bar">
            <button onclick="exportCSV()" class="btn-action">
                <i class="fas fa-file-export"></i> Eksport VIP Listy
            </button>
            <button onclick="sortByAge()" class="btn-action">
                <i class="fas fa-sort-numeric-down-alt"></i> Sortuj po Dojrzałości
            </button>
            <button onclick="sortByName()" class="btn-action">
                <i class="fas fa-sort-alpha-down"></i> Sortuj po Nazwisku
            </button>
            <button onclick="generateChart()" class="btn-action">
                <i class="fas fa-chart-pie"></i> Statystyki Elity
            </button>
            <button onclick="generatePDF()" class="btn-action">
                <i class="fas fa-scroll"></i> Raport Ekskluzywny
            </button>
        </div>

        <div id="statusMessage" class="status-message"></div>

        <div id="personasTable" class="card-grid" aria-live="polite"></div>

        <div id="chartContainer" class="chart-container" style="display:none;">
            <h3><i class="fas fa-chart-bar"></i> Statystyki Demograficzne Elity</h3>
            <canvas id="demographicChart" width="800" height="400"></canvas>
        </div>
    </main>

    <script>
        let personas = [];
        let segments = [];
        const countries = ['PL','DE','FR','ES','US','IT','NL','GB','CA','AU','JP','KR','CH','BR','RU'];
        const defaultSegments = [
            'Młoda Elita (18-25)',
            'Profesjonalista (26-40)', 
            'Dojrzały Lider (41-55)',
            'Ekspert Senior (56-70)',
            'Ikona Stylu (70+)'
        ];

        function loadSettings() {
            const settings = localStorage.getItem('glamour_vip_settings');
            if (settings) {
                const parsed = JSON.parse(settings);
                document.getElementById('personCount').value = parsed.personCount || 9;
                document.getElementById('maskData').checked = parsed.maskData !== false;
            }
        }

        function saveSettings() {
            const settings = {
                personCount: document.getElementById('personCount').value,
                maskData: document.getElementById('maskData').checked
            };
            localStorage.setItem('glamour_vip_settings', JSON.stringify(settings));
        }

        function loadCache() {
            const cache = localStorage.getItem('glamour_vip_cache');
            if (cache) {
                try {
                    const parsed = JSON.parse(cache);
                    if (Array.isArray(parsed)) {
                        personas = parsed;
                        renderCards(personas);
                        populateFilters();
                        showStatus('Przywrócono kolekcję VIP z pamięci', 'success');
                    }
                } catch(e) {
                    console.error('Błąd cache VIP:', e);
                }
            }
        }

        function saveCache() {
            localStorage.setItem('glamour_vip_cache', JSON.stringify(personas));
        }

        function showStatus(message, type) {
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.textContent = `✨ ${message} ✨`;
            statusDiv.className = `status-message ${type}`;
            statusDiv.style.display = 'block';
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 4500);
        }

        function assignSegment(persona) {
            for (let segment of segments) {
                if (validateRule(persona, segment.rule)) {
                    return segment.name;
                }
            }
            if (persona.age >= 18 && persona.age <= 25) return 'Młoda Elita (18-25)';
            if (persona.age > 25 && persona.age <= 40) return 'Profesjonalista (26-40)';
            if (persona.age > 40 && persona.age <= 55) return 'Dojrzały Lider (41-55)';
            if (persona.age > 55 && persona.age <= 70) return 'Ekspert Senior (56-70)';
            if (persona.age > 70) return 'Ikona Stylu (70+)';
            return 'VIP Guest';
        }

        function validateRule(persona, rule) {
            try {
                const ageMatch = rule.match(/wiek\s*([><=]+)\s*(\d+)/i);
                if (ageMatch) {
                    const operator = ageMatch[1];
                    const value = parseInt(ageMatch[2]);
                    const age = persona.age;
                    
                    if (operator === '>=') return age >= value;
                    if (operator === '<=') return age <= value;
                    if (operator === '>') return age > value;
                    if (operator === '<') return age < value;
                    if (operator === '==' || operator === '=') return age === value;
                }
                return false;
            } catch (e) {
                return false;
            }
        }

        async function generateFromAPI() {
            const count = parseInt(document.getElementById('personCount').value) || 9;
            if (count < 1 || count > 1000) {
                showStatus('Limit osobistości: 1-1000', 'error');
                return;
            }

            saveSettings();
            showStatus('Tworzę ekskluzywną kolekcję osobistości...', 'info');

            try {
                const url = `https://randomuser.me/api/?results=${count}&inc=name,location,email,login,dob,phone,picture,nat&nat=us,gb,fr,de,es,it,pl`;
                const response = await fetch(url);
                if (!response.ok) throw new Error('Błąd połączenia VIP: ' + response.status);
                
                const data = await response.json();
                personas = data.results.map((user, index) => {
                    const vipId = 'VIP-' + (index + 1).toString().padStart(3, '0');
                    return {
                        id: vipId,
                        name: user.name.first,
                        surname: user.name.last,
                        age: user.dob.age,
                        country: user.location.country,
                        email: user.email,
                        phone: user.phone,
                        segment: '',
                        avatar: user.picture.large,
                        city: user.location.city,
                        state: user.location.state,
                        postcode: user.location.postcode,
                        nat: user.nat,
                        username: user.login.username,
                        title: user.name.title
                    };
                });

                personas.forEach(p => p.segment = assignSegment(p));
                
                renderCards(personas);
                populateFilters();
                saveCache();
                showStatus(`Stworzono ${personas.length} ekskluzywnych osobistości!`, 'success');
            } catch (error) {
                showStatus('Błąd API VIP. Przywracam z archiwum...', 'error');
                loadCache();
            }
        }

        function renderCards(list) {
            const container = document.getElementById('personasTable');
            container.innerHTML = '';
            
            if (list.length === 0) {
                container.innerHTML = `
                    <div style="text-align:center;padding:60px;color:var(--light-gold);grid-column:1/-1;background:linear-gradient(145deg, rgba(44,0,62,0.8), rgba(107,13,173,0.8));border-radius:25px;border:2px solid rgba(212,175,55,0.3);">
                        <i class="fas fa-users-crown fa-4x" style="margin-bottom:20px;color:var(--gold);"></i>
                        <h3 style="font-family:'Playfair Display',serif;margin-bottom:15px;">Brak Osobistości</h3>
                        <p>Kliknij "Generuj Osobistości" aby rozpocząć tworzenie ekskluzywnej kolekcji</p>
                    </div>
                `;
                return;
            }

            list.forEach(p => {
                const card = document.createElement('div');
                card.className = 'persona-card';
                card.onclick = () => openEdit(p.id);
                card.innerHTML = `
                    <div class="persona-id">${p.id}</div>
                    <div class="persona-avatar">
                        <img src="${p.avatar}" alt="${p.name} ${p.surname}" 
                             onerror="this.src='https://ui-avatars.com/api/?name=${p.name}+${p.surname}&background=2C003E&color=D4AF37&bold=true&size=256'">
                    </div>
                    <div class="persona-meta">
                        <div class="name">
                            ${p.title ? p.title + ' ' : ''}${p.name} ${p.surname}
                            <span style="color:var(--rose-gold);font-size:0.9em;"> • ${p.country}</span>
                        </div>
                        <div class="line">
                            <span class="sub"><i class="fas fa-birthday-cake"></i> Dojrzałość: <strong>${p.age}</strong> lat</span>
                            <span class="tag"><i class="fas fa-crown"></i> ${p.segment}</span>
                        </div>
                        <div class="line">
                            <span class="sub"><i class="fas fa-envelope"></i> ${p.email}</span>
                        </div>
                        <div class="line">
                            <span class="sub"><i class="fas fa-phone"></i> ${p.phone}</span>
                            <span class="sub"><i class="fas fa-map-marker-alt"></i> ${p.city}, ${p.state}</span>
                        </div>
                        <div class="line">
                            <span class="sub"><i class="fas fa-user-tag"></i> ${p.username}</span>
                            <span class="sub"><i class="fas fa-passport"></i> ${p.nat}</span>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function populateFilters() {
            const countrySelect = document.getElementById('filterCountry');
            const segmentSelect = document.getElementById('filterSegment');
            
            const uniqueCountries = [...new Set(personas.map(p => p.country))].sort();
            const uniqueSegments = [...new Set(personas.map(p => p.segment))].sort();
            
            countrySelect.innerHTML = '<option value="">Wszystkie Destynacje</option>';
            segmentSelect.innerHTML = '<option value="">Wszystkie Kategorie</option>';
            
            uniqueCountries.forEach(c => {
                const option = document.createElement('option');
                option.value = c;
                option.textContent = c;
                countrySelect.appendChild(option);
            });
            
            uniqueSegments.forEach(s => {
                const option = document.createElement('option');
                option.value = s;
                option.textContent = s;
                segmentSelect.appendChild(option);
            });
        }

        function applyFilters() {
            const country = document.getElementById('filterCountry').value;
            const min = parseInt(document.getElementById('filterAgeMin').value) || 18;
            const max = parseInt(document.getElementById('filterAgeMax').value) || 99;
            const segment = document.getElementById('filterSegment').value;
            
            const filtered = personas.filter(p => {
                if (country && p.country !== country) return false;
                if (p.age < min || p.age > max) return false;
                if (segment && p.segment !== segment) return false;
                return true;
            });
            
            renderCards(filtered);
            showStatus(`Wyświetlono ${filtered.length} osobistości z kolekcji`, 'info');
        }

        function sortByAge() {
            personas.sort((a, b) => a.age - b.age);
            renderCards(personas);
            showStatus('Posortowano według dojrzałości', 'success');
        }

        function sortByName() {
            personas.sort((a, b) => a.surname.localeCompare(b.surname));
            renderCards(personas);
            showStatus('Posortowano według nazwiska', 'success');
        }

        function exportCSV() {
            if (!personas.length) {
                showStatus('Brak danych VIP do eksportu', 'error');
                return;
            }
            
            const maskData = document.getElementById('maskData').checked;
            const headers = ['ID VIP','Tytuł','Imię','Nazwisko','Wiek','Kraj','Email','Telefon','Segment','Miasto','Username','Narodowość'];
            
            let csv = headers.join(';') + '\n';
            
            personas.forEach(p => {
                let email = p.email;
                let phone = p.phone;
                
                if (maskData) {
                    email = email.replace(/(.{1})(.*)(@.*)/, '$1***$3');
                    phone = phone.replace(/.(?=.{4})/g, '*');
                }
                
                const row = [
                    p.id,
                    p.title || '',
                    p.name,
                    p.surname,
                    p.age,
                    p.country,
                    email,
                    phone,
                    p.segment,
                    p.city,
                    p.username,
                    p.nat
                ].map(field => `"${String(field).replace(/"/g, '""')}"`).join(';');
                
                csv += row + '\n';
            });
            
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `VIP_Kolekcja_${new Date().toISOString().slice(0,10)}.csv`;
            a.click();
            URL.revokeObjectURL(url);
            
            showStatus('Ekskluzywna lista wyeksportowana do CSV', 'success');
        }

        function generateChart() {
            if (!personas.length) {
                showStatus('Brak danych VIP dla statystyk', 'error');
                return;
            }
            
            const countryData = {};
            personas.forEach(p => {
                countryData[p.country] = (countryData[p.country] || 0) + 1;
            });
            
            const countries = Object.keys(countryData)
                .sort((a,b) => countryData[b] - countryData[a])
                .slice(0, 10);
            const counts = countries.map(c => countryData[c]);
            
            const container = document.getElementById('chartContainer');
            container.style.display = 'block';
            
            const canvas = document.getElementById('demographicChart');
            const ctx = canvas.getContext('2d');
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const barWidth = (canvas.width - 100) / countries.length;
            const maxCount = Math.max(...counts);
            const scale = (canvas.height - 100) / maxCount;
            
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, '#D4AF37');
            gradient.addColorStop(0.5, '#B76E79');
            gradient.addColorStop(1, '#6A0DAD');
            
            countries.forEach((country, i) => {
                const barHeight = counts[i] * scale;
                const x = 50 + i * barWidth;
                const y = canvas.height - 50 - barHeight;
                
                ctx.fillStyle = gradient;
                ctx.fillRect(x, y, barWidth - 15, barHeight);
                
                ctx.fillStyle = '#F7E7CE';
                ctx.font = 'bold 14px "Raleway", sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                ctx.save();
                ctx.translate(x + barWidth/2 - 7.5, canvas.height - 20);
                ctx.rotate(-Math.PI/4);
                ctx.fillText(country.length > 10 ? country.substring(0,10)+'...' : country, 0, 0);
                ctx.restore();
                
                ctx.fillStyle = '#FFD700';
                ctx.fillText(counts[i], x + barWidth/2 - 7.5, y - 10);
            });
            
            ctx.fillStyle = '#FFD700';
            ctx.font = 'bold 20px "Playfair Display", serif';
            ctx.textAlign = 'center';
            ctx.fillText('Rozkład Osobistości VIP według Kraju', canvas.width/2, 40);
            
            canvas.toBlob(blob => {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = `VIP_Statystyki_${new Date().toISOString().slice(0,10)}.png`;
                a.click();
            });
            
            showStatus('Statystyki elity wygenerowane i zapisane', 'success');
        }

        function generatePDF() {
            if (!personas.length) {
                showStatus('Brak danych dla raportu VIP', 'error');
                return;
            }
            
            const avgAge = (personas.reduce((sum, p) => sum + p.age, 0) / personas.length).toFixed(1);
            const uniqueCountries = [...new Set(personas.map(p => p.country))];
            const segmentsData = {};
            personas.forEach(p => {
                segmentsData[p.segment] = (segmentsData[p.segment] || 0) + 1;
            });
            
            const reportWindow = window.open('', '_blank');
            const reportDate = new Date().toLocaleDateString('pl-PL', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            reportWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Glamurowy Generator - Raport VIP</title>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Raleway:wght@300;400;500;600&display=swap');
                        
                        body { 
                            font-family: 'Raleway', sans-serif; 
                            padding: 50px; 
                            background: linear-gradient(135deg, #2C003E 0%, #6A0DAD 100%);
                            color: #F7E7CE;
                            min-height: 100vh;
                        }
                        
                        .vip-header { 
                            background: linear-gradient(135deg, #D4AF37, #B76E79);
                            color: #2C003E; 
                            padding: 40px; 
                            border-radius: 25px; 
                            margin-bottom: 40px; 
                            text-align: center;
                            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
                        }
                        
                        .vip-header h1 { 
                            font-family: 'Playfair Display', serif; 
                            font-size: 3em; 
                            margin-bottom: 20px;
                            letter-spacing: 2px;
                        }
                        
                        .stat-box { 
                            background: rgba(255, 255, 255, 0.1); 
                            padding: 30px; 
                            border-radius: 20px; 
                            margin: 25px 0; 
                            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                            border: 2px solid rgba(212, 175, 55, 0.3);
                            backdrop-filter: blur(10px);
                        }
                        
                        .stat-box h3 { 
                            color: #FFD700; 
                            font-family: 'Playfair Display', serif; 
                            margin-bottom: 20px;
                            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
                            padding-bottom: 10px;
                        }
                        
                        .highlight { 
                            color: #D4AF37; 
                            font-weight: 600; 
                            font-size: 1.2em;
                        }
                        
                        table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin: 25px 0; 
                        }
                        
                        th { 
                            background: linear-gradient(135deg, #D4AF37, #B76E79);
                            color: #2C003E; 
                            padding: 18px; 
                            text-align: left; 
                            font-weight: 600;
                            font-family: 'Playfair Display', serif;
                        }
                        
                        td { 
                            padding: 15px; 
                            border-bottom: 1px solid rgba(212, 175, 55, 0.2); 
                        }
                        
                        tr:hover { 
                            background: rgba(212, 175, 55, 0.1); 
                        }
                        
                        .vip-footer { 
                            text-align: center; 
                            margin-top: 50px; 
                            color: #B76E79; 
                            font-style: italic;
                            padding: 30px;
                            border-top: 2px solid rgba(212, 175, 55, 0.3);
                        }
                        
                        .badge { 
                            display: inline-block; 
                            background: rgba(212, 175, 55, 0.2); 
                            color: #FFD700; 
                            padding: 8px 16px; 
                            border-radius: 20px; 
                            margin: 5px;
                            border: 1px solid rgba(212, 175, 55, 0.3);
                        }
                        
                        @media print {
                            body { 
                                background: white !important; 
                                color: #2C003E !important;
                            }
                            .vip-header { 
                                background: #D4AF37 !important; 
                                color: #2C003E !important; 
                            }
                            .stat-box { 
                                background: #f9f9f9 !important; 
                                border: 1px solid #ddd !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="vip-header">
                        <h1><i class="fas fa-crown"></i> Raport VIP Kolekcji</h1>
                        <p style="font-size: 1.2em;">Wygenerowano: ${reportDate}</p>
                        <p style="font-size: 1.1em;">Glamurowy Generator v2.0</p>
                    </div>
                    
                    <div class="stat-box">
                        <h3><i class="fas fa-chart-pie"></i> Podsumowanie Statystyczne</h3>
                        <p><span class="highlight">Łączna liczba osobistości:</span> ${personas.length}</p>
                        <p><span class="highlight">Średnia dojrzałość:</span> ${avgAge} lat</p>
                        <p><span class="highlight">Unikalne destynacje:</span> ${uniqueCountries.length}</p>
                        <p><span class="highlight">Zakres dojrzałości:</span> ${Math.min(...personas.map(p => p.age))} - ${Math.max(...personas.map(p => p.age))} lat</p>
                    </div>
                    
                    <div class="stat-box">
                        <h3><i class="fas fa-tags"></i> Segmentacja VIP</h3>
                        ${Object.entries(segmentsData).map(([segment, count]) => `
                            <span class="badge">${segment}: ${count}</span>
                        `).join('')}
                        <p style="margin-top: 20px; color: #B76E79;">
                            Dominujący segment: <strong>${Object.keys(segmentsData).reduce((a, b) => segmentsData[a] > segmentsData[b] ? a : b)}</strong>
                        </p>
                    </div>
                    
                    <div class="stat-box">
                        <h3><i class="fas fa-users-crown"></i> Top 12 Osobistości</h3>
                        <table>
                            <tr>
                                <th>ID VIP</th>
                                <th>Imię i Nazwisko</th>
                                <th>Dojrzałość</th>
                                <th>Destynacja</th>
                                <th>Segment</th>
                            </tr>
                            ${personas.slice(0,12).map(p => `
                                <tr>
                                    <td><strong>${p.id}</strong></td>
                                    <td>${p.name} ${p.surname}</td>
                                    <td>${p.age} lat</td>
                                    <td>${p.country}</td>
                                    <td><span class="badge">${p.segment}</span></td>
                                </tr>
                            `).join('')}
                        </table>
                    </div>
                    
                    <div class="vip-footer">
                        <p>© ${new Date().getFullYear()} Glamurowy Generator | Dokument ekskluzywny</p>
                        <p style="font-size: 0.9em; margin-top: 10px;">Raport wygenerowany automatycznie przez system VIP</p>
                    </div>
                    
                    <script>
                        setTimeout(() => {
                            window.print();
                            setTimeout(() => window.close(), 1000);
                        }, 1000);
                    <\/script>
                </body>
                </html>
            `);
            
            showStatus('Ekskluzywny raport PDF wygenerowany', 'success');
        }

        document.getElementById('segmentsFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const content = event.target.result;
                const lines = content.split('\\n').filter(line => line.trim());
                
                segments = [];
                for (let i = 0; i < lines.length; i++) {
                    const [name, rule] = lines[i].split(';');
                    if (name && rule) {
                        segments.push({
                            name: name.trim(),
                            rule: rule.trim()
                        });
                    }
                }
                
                if (personas.length > 0) {
                    personas.forEach(p => p.segment = assignSegment(p));
                    renderCards(personas);
                    populateFilters();
                }
                
                showStatus(`Zaimportowano ${segments.length} profili VIP`, 'success');
            };
            reader.readAsText(file);
        });

        function openEdit(id) {
            const p = personas.find(x => x.id === id);
            if (!p) return;
            
            const overlay = document.createElement('div');
            overlay.className = 'overlay';
            overlay.onclick = () => {
                overlay.remove();
                modal.remove();
            };
            document.body.appendChild(overlay);
            
            const modal = document.createElement('div');
            modal.className = 'edit-modal';
            modal.innerHTML = `
                <h4 style="color:var(--light-gold);margin-bottom:25px;font-family:'Playfair Display',serif;font-size:1.5em;">
                    <i class="fas fa-edit"></i> Edytuj Profil VIP
                </h4>
                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Imię</label>
                        <input id="edit_name" value="${p.name}" style="width:100%;padding:15px;background:rgba(44,0,62,0.6);border:2px solid rgba(212,175,55,0.3);border-radius:12px;color:var(--champagne);">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nazwisko</label>
                        <input id="edit_surname" value="${p.surname}" style="width:100%;padding:15px;background:rgba(44,0,62,0.6);border:2px solid rgba(212,175,55,0.3);border-radius:12px;color:var(--champagne);">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-birthday-cake"></i> Dojrzałość</label>
                        <input id="edit_age" type="number" value="${p.age}" style="width:100%;padding:15px;background:rgba(44,0,62,0.6);border:2px solid rgba(212,175,55,0.3);border-radius:12px;color:var(--champagne);">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-globe"></i> Destynacja</label>
                        <select id="edit_country" style="width:100%;padding:15px;background:rgba(44,0,62,0.6);border:2px solid rgba(212,175,55,0.3);border-radius:12px;color:var(--champagne);">
                            ${countries.map(c => `<option ${c === p.country ? 'selected' : ''} value="${c}">${c}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-crown"></i> Segment VIP</label>
                        <select id="edit_segment" style="width:100%;padding:15px;background:rgba(44,0,62,0.6);border:2px solid rgba(212,175,55,0.3);border-radius:12px;color:var(--champagne);">
                            ${defaultSegments.map(s => `<option ${s === p.segment ? 'selected' : ''} value="${s}">${s}</option>`).join('')}
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:15px; justify-content:flex-end; margin-top:30px;">
                    <button id="saveBtn" style="padding:15px 30px;border-radius:12px;background:linear-gradient(135deg, var(--gold), var(--rose-gold));color:var(--dark-velvet);border:none;font-weight:600;cursor:pointer;font-family:'Playfair Display',serif;letter-spacing:1px;">
                        <i class="fas fa-save"></i> Zapisz Zmiany
                    </button>
                    <button id="cancelBtn" style="padding:15px 30px;border-radius:12px;background:rgba(107,13,173,0.8);color:var(--champagne);border:2px solid rgba(212,175,55,0.4);font-weight:600;cursor:pointer;font-family:'Playfair Display',serif;letter-spacing:1px;">
                        <i class="fas fa-times"></i> Anuluj
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            
            document.getElementById('cancelBtn').onclick = () => {
                overlay.remove();
                modal.remove();
            };
            
            document.getElementById('saveBtn').onclick = () => {
                p.name = document.getElementById('edit_name').value || p.name;
                p.surname = document.getElementById('edit_surname').value || p.surname;
                p.age = parseInt(document.getElementById('edit_age').value) || p.age;
                p.country = document.getElementById('edit_country').value || p.country;
                p.segment = document.getElementById('edit_segment').value || p.segment;
                
                overlay.remove();
                modal.remove();
                renderCards(personas);
                showStatus('Profil VIP zaktualizowany', 'success');
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadSettings();
            loadCache();
            generateFromAPI();
            
            setTimeout(() => {
                const style = document.createElement('style');
                style.textContent = `
                    .persona-card {
                        animation: cardAppear 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    
                    @keyframes cardAppear {
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    
                    .persona-card:nth-child(1) { animation-delay: 0.1s; }
                    .persona-card:nth-child(2) { animation-delay: 0.2s; }
                    .persona-card:nth-child(3) { animation-delay: 0.3s; }
                    .persona-card:nth-child(4) { animation-delay: 0.4s; }
                    .persona-card:nth-child(5) { animation-delay: 0.5s; }
                    .persona-card:nth-child(6) { animation-delay: 0.6s; }
                    .persona-card:nth-child(7) { animation-delay: 0.7s; }
                    .persona-card:nth-child(8) { animation-delay: 0.8s; }
                    .persona-card:nth-child(9) { animation-delay: 0.9s; }
                `;
                document.head.appendChild(style);
            }, 500);
        });
    </script>
</body>
</html>