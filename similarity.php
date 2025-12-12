<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Znajdź podobnego - Glamurowy Generator</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@400;500;600&display=swap" rel="stylesheet">
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
    --border: rgba(212, 175, 55, 0.3);
}

.similarity-container {
    max-width: 1000px;
    margin: 40px auto;
    background: linear-gradient(145deg, 
        rgba(44, 0, 62, 0.9), 
        rgba(107, 13, 173, 0.9));
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
    border: 2px solid var(--border);
}

.similarity-title {
    color: var(--light-gold);
    text-align: center;
    margin-bottom: 30px;
    font-size: 2em;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    font-family: 'Playfair Display', serif;
}

.similarity-description {
    text-align: center;
    color: var(--champagne);
    margin-bottom: 40px;
    font-size: 1.1em;
    line-height: 1.6;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.similarity-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.form-group-sim {
    display: flex;
    flex-direction: column;
}

.form-group-sim label {
    margin-bottom: 10px;
    color: var(--champagne);
    font-weight: 500;
    font-size: 0.95em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group-sim label i {
    color: var(--light-gold);
}

.form-group-sim input,
.form-group-sim select {
    padding: 14px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1em;
    transition: all 0.3s ease;
    font-family: 'Raleway', sans-serif;
    background: rgba(44, 0, 62, 0.6);
    color: var(--champagne);
}

.form-group-sim input:focus,
.form-group-sim select:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
    background: rgba(44, 0, 62, 0.8);
}

.similarity-btn {
    grid-column: 1 / -1;
    padding: 16px;
    background: linear-gradient(135deg, var(--gold), var(--rose-gold));
    color: var(--dark-velvet);
    border: none;
    border-radius: 12px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
}

.similarity-btn:hover {
    background: linear-gradient(135deg, var(--light-gold), var(--blush));
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
}

.results-container {
    margin-top: 40px;
}

.results-title {
    color: var(--light-gold);
    margin-bottom: 25px;
    font-size: 1.5em;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border);
    font-family: 'Playfair Display', serif;
}

.match-card {
    background: linear-gradient(145deg, 
        rgba(44, 0, 62, 0.8), 
        rgba(107, 13, 173, 0.8));
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    border: 2px solid var(--border);
    transition: all 0.3s ease;
    display: flex;
    gap: 25px;
    align-items: flex-start;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.match-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    border-color: var(--gold);
}

.match-avatar {
    width: 90px;
    height: 90px;
    border-radius: 15px;
    overflow: hidden;
    flex-shrink: 0;
    border: 3px solid var(--gold);
    background: linear-gradient(135deg, var(--gold), var(--rose-gold));
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
}

.match-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.match-info {
    flex: 1;
}

.match-name {
    color: var(--light-gold);
    font-size: 1.2em;
    font-weight: 600;
    margin-bottom: 10px;
    font-family: 'Playfair Display', serif;
}

.match-details {
    color: var(--champagne);
    font-size: 0.95em;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.match-details span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.match-similarity {
    background: linear-gradient(135deg, 
        rgba(212, 175, 55, 0.15), 
        rgba(183, 110, 121, 0.15));
    color: var(--light-gold);
    padding: 8px 16px;
    border-radius: 15px;
    font-size: 0.9em;
    border: 1px solid rgba(212, 175, 55, 0.3);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.similarity-bar {
    width: 100px;
    height: 8px;
    background: var(--border);
    border-radius: 4px;
    overflow: hidden;
    margin-left: 10px;
}

.similarity-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--gold), var(--rose-gold));
    border-radius: 4px;
    transition: width 0.6s ease;
}

.no-results {
    text-align: center;
    padding: 60px 40px;
    color: var(--champagne);
    font-size: 1.1em;
    background: rgba(44, 0, 62, 0.6);
    border-radius: 15px;
    border: 2px dashed var(--border);
}

.no-results i {
    font-size: 3em;
    margin-bottom: 20px;
    color: var(--border);
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-box {
    background: linear-gradient(145deg, 
        rgba(44, 0, 62, 0.8), 
        rgba(107, 13, 173, 0.8));
    padding: 25px;
    border-radius: 15px;
    border: 2px solid var(--border);
    text-align: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.stat-number {
    font-size: 2.5em;
    font-weight: 700;
    color: var(--light-gold);
    margin-bottom: 10px;
}

.stat-label {
    color: var(--champagne);
    font-size: 0.95em;
    font-weight: 500;
}

@media (max-width: 768px) {
    .similarity-container {
        margin: 20px;
        padding: 25px;
    }
    
    .similarity-form {
        grid-template-columns: 1fr;
    }
    
    .match-card {
        flex-direction: column;
        gap: 20px;
    }
    
    .match-avatar {
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    
    .match-details {
        flex-direction: column;
        gap: 10px;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>
<body>

    <main class="container">
        <div class="similarity-container">
            <h1 class="similarity-title"><i class="fas fa-user-friends"></i> Znajdź swojego bliźniaka</h1>
            <p style="text-align:center;color:#7a6994;margin-bottom:30px;">Wprowadź swoje dane i znajdź personę najbardziej podobną do Ciebie</p>
            
            <form id="similarityForm" class="similarity-form">
                <div class="form-group-sim">
                    <label><i class="fas fa-user"></i> Imię</label>
                    <input type="text" id="simName" placeholder="Twoje imię">
                </div>
                
                <div class="form-group-sim">
                    <label><i class="fas fa-user"></i> Nazwisko</label>
                    <input type="text" id="simSurname" placeholder="Twoje nazwisko">
                </div>
                
                <div class="form-group-sim">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="simEmail" placeholder="Twój email">
                </div>
                
                <div class="form-group-sim">
                    <label><i class="fas fa-phone"></i> Telefon</label>
                    <input type="text" id="simPhone" placeholder="Twój telefon">
                </div>
                
                <div class="form-group-sim">
                    <label><i class="fas fa-globe"></i> Kraj</label>
                    <select id="simCountry">
                        <option value="">Wybierz kraj</option>
                        <option value="PL">Polska</option>
                        <option value="DE">Niemcy</option>
                        <option value="FR">Francja</option>
                        <option value="ES">Hiszpania</option>
                        <option value="US">USA</option>
                        <option value="IT">Włochy</option>
                        <option value="GB">Wielka Brytania</option>
                    </select>
                </div>
                
                <div class="form-group-sim">
                    <label><i class="fas fa-birthday-cake"></i> Wiek</label>
                    <input type="number" id="simAge" min="1" max="120" placeholder="Twój wiek">
                </div>
                
                <div class="form-group-sim">
                    <label><i class="fas fa-tag"></i> Segment</label>
                    <select id="simSegment">
                        <option value="">Wybierz segment</option>
                        <option value="Młodzież">Młodzież</option>
                        <option value="Student">Student</option>
                        <option value="Profesjonalista">Profesjonalista</option>
                        <option value="Rodzic">Rodzic</option>
                        <option value="Senior">Senior</option>
                    </select>
                </div>
                
                <button type="button" onclick="findSimilar()" class="similarity-btn">
                    <i class="fas fa-search"></i> Znajdź podobnego
                </button>
            </form>
            
            <div class="results-container" id="resultsContainer">
                <div class="no-results" id="noResults">
                    <i class="fas fa-search fa-3x" style="margin-bottom:15px;"></i>
                    <p>Wprowadź dane i znajdź swojego bliźniaka!</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        let allPersons = [];
        
        async function loadPersons() {
            try {
                const response = await fetch('https://randomuser.me/api/?results=50');
                const data = await response.json();
                allPersons = data.results.map(person => ({
                    id: person.login.uuid,
                    name: person.name.first,
                    surname: person.name.last,
                    email: person.email,
                    phone: person.phone,
                    country: person.location.country,
                    age: person.dob.age,
                    city: person.location.city,
                    avatar: person.picture.large,
                    segment: getSegment(person.dob.age)
                }));
            } catch (error) {
                console.error('Błąd ładowania osób:', error);
            }
        }
        
        function getSegment(age) {
            if (age < 18) return 'Młodzież';
            if (age >= 18 && age <= 25) return 'Student';
            if (age > 25 && age <= 40) return 'Profesjonalista';
            if (age > 40 && age <= 60) return 'Rodzic';
            return 'Senior';
        }
        
        function calculateSimilarity(person, input) {
            let score = 0;
            let matches = [];
            
            if (input.name && person.name.toLowerCase().includes(input.name.toLowerCase())) {
                score += 30;
                matches.push('imię');
            }
            
            if (input.surname && person.surname.toLowerCase().includes(input.surname.toLowerCase())) {
                score += 30;
                matches.push('nazwisko');
            }
            
            if (input.email && person.email.toLowerCase().includes(input.email.toLowerCase())) {
                score += 25;
                matches.push('email');
            }
            
            if (input.country && person.country.toLowerCase().includes(input.country.toLowerCase())) {
                score += 20;
                matches.push('kraj');
            }
            
            if (input.age && Math.abs(person.age - parseInt(input.age)) <= 5) {
                score += 15;
                matches.push('wiek (±5 lat)');
            }
            
            if (input.segment && person.segment === input.segment) {
                score += 20;
                matches.push('segment');
            }
            
            return { score, matches };
        }
        
        async function findSimilar() {
            const input = {
                name: document.getElementById('simName').value,
                surname: document.getElementById('simSurname').value,
                email: document.getElementById('simEmail').value,
                phone: document.getElementById('simPhone').value,
                country: document.getElementById('simCountry').value,
                age: document.getElementById('simAge').value,
                segment: document.getElementById('simSegment').value
            };
            
            if (!allPersons.length) {
                await loadPersons();
            }
            
            const results = allPersons.map(person => {
                const similarity = calculateSimilarity(person, input);
                return { ...person, similarity: similarity.score, matches: similarity.matches };
            }).filter(p => p.similarity > 0)
              .sort((a, b) => b.similarity - a.similarity)
              .slice(0, 10);
            
            displayResults(results);
        }
        
        function displayResults(results) {
            const container = document.getElementById('resultsContainer');
            const noResults = document.getElementById('noResults');
            
            if (results.length === 0) {
                container.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-user-slash fa-3x" style="margin-bottom:15px;"></i>
                        <p>Nie znaleziono podobnych person. Spróbuj zmienić kryteria wyszukiwania.</p>
                    </div>
                `;
                return;
            }
            
            noResults.style.display = 'none';
            
            let html = `
                <h3 class="results-title"><i class="fas fa-user-friends"></i> Najbardziej podobne persony (${results.length})</h3>
            `;
            
            results.forEach((person, index) => {
                const similarityPercent = Math.min(person.similarity, 100);
                const similarityColor = similarityPercent >= 80 ? '#2ecc71' : 
                                      similarityPercent >= 60 ? '#f39c12' : 
                                      similarityPercent >= 40 ? '#e74c3c' : '#95a5a6';
                
                html += `
                    <div class="match-card">
                        <div class="match-avatar">
                            <img src="${person.avatar}" alt="${person.name}">
                        </div>
                        <div class="match-info">
                            <div class="match-name">${person.name} ${person.surname}</div>
                            <div class="match-details">
                                <span><i class="fas fa-globe"></i> ${person.country}</span>
                                <span><i class="fas fa-birthday-cake"></i> ${person.age} lat</span>
                                <span><i class="fas fa-tag"></i> ${person.segment}</span>
                                <span><i class="fas fa-envelope"></i> ${person.email}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div class="match-similarity">
                                    <i class="fas fa-heart"></i> Podobieństwo: ${similarityPercent}%
                                    ${person.matches.length > 0 ? `(${person.matches.join(', ')})` : ''}
                                </div>
                                <div style="width:100px;height:8px;background:#e0d6f0;border-radius:4px;overflow:hidden;">
                                    <div style="width:${similarityPercent}%;height:100%;background:${similarityColor};"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        document.addEventListener('DOMContentLoaded', loadPersons);
    </script>
</body>
</html>