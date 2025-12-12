let personas = {};
let segments = [];
let filteredPersonas = [];

// wczytanie ustawień z localStorage
function loadSettings() {
    const settings = localStorage.getItem('personas_settings');
    if (settings) {
        const parsed = JSON.parse(settings);
        document.getElementById('personCount').value = parsed.personCount || 10;
        document.getElementById('seed').value = parsed.seed || '';
        document.getElementById('maskData').checked = parsed.maskData !== false;
    }
}

// сохр настроек
function saveSettings() {
    const settings = {
        personCount: document.getElementById('personCount').value,
        seed: document.getElementById('seed').value,
        maskData: document.getElementById('maskData').checked
    };
    localStorage.setItem('personas_settings', JSON.stringify(settings));
}

// wczytanie cache
function loadCache() {
    const cache = localStorage.getItem('personas_cache');
    if (cache) {
        const parsed = JSON.parse(cache);
        if (Array.isArray(parsed)) {
            // convert old array-based cache to object keyed by id
            personas = {};
            parsed.forEach(p => {
                const id = p.id || (p.email ? p.email : (Date.now().toString(36) + Math.random().toString(36).slice(2)));
                p.id = id;
                personas[id] = p;
            });
        } else {
            personas = parsed;
        }
        filteredPersonas = Object.values(personas);
        displayPersonas();
        updateFilters();
        showStatus('Wczytano dane z cache', 'success');
    }
}

// cache записать
function saveCache() {
    localStorage.setItem('personas_cache', JSON.stringify(personas));
}

// спрятать мейл
function maskEmail(email) {
    if (!document.getElementById('maskData').checked) return email;
    const [local, domain] = email.split('@');
    const [domainName, ext] = domain.split('.');
    return `${local[0]}***@${domainName[0]}***.${ext}`;
}

// Walidacja reguły segmentu
function validateRule(persona, rule) {
    try {
        // Obsługa prostych reguł typu: wiek>=30, wiek<50, kraj==PL
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

        const countryMatch = rule.match(/kraj\s*==\s*['"]?([A-Z]{2})['"]?/i);
        if (countryMatch) {
            return persona.country.toUpperCase() === countryMatch[1].toUpperCase();
        }

        return true;
    } catch (e) {
        console.error('Błąd walidacji reguły:', rule, e);
        return false;
    }
}

// Przypisanie segmentu do persony
function assignSegment(persona) {
    for (let segment of segments) {
        if (validateRule(persona, segment.rule)) {
            return segment.name;
        }
    }
    return 'Brak segmentu';
}

// Import segmentów z CSV
document.getElementById('segmentsFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        const content = event.target.result;
        const lines = content.split('\n').filter(line => line.trim());
        
        segments = [];
        for (let i = 1; i < lines.length; i++) {
            const [name, rule] = lines[i].split(';');
            if (name && rule) {
                segments.push({ 
                    name: name.trim(), 
                    rule: rule.trim() 
                });
            }
        }

        // przypisz segmenty do istniejących person
        if (Object.keys(personas).length > 0) {
            Object.values(personas).forEach(p => p.segment = assignSegment(p));
            filteredPersonas = [...personas];
            displayPersonas();
            updateFilters();
        }

        showStatus(`Zaimportowano ${segments.length} segmentów`, 'success');
    };
    reader.readAsText(file);
});

// генерация персон
async function generatePersonas() {
    const count = parseInt(document.getElementById('personCount').value);
    const seed = document.getElementById('seed').value;
    
    if (count < 1 || count > 5000) {
        showStatus('Liczba person musi być między 1 a 5000', 'error');
        return;
    }

    saveSettings();
    showStatus('Generowanie person...', 'info');

    try {
        let url = `https://randomuser.me/api/?results=${count}`;
        if (seed) url += `&seed=${seed}`;

        const response = await fetch(url);
        if (!response.ok) throw new Error('Błąd HTTP: ' + response.status);

        const data = await response.json();
        
        personas = {};
        data.results.forEach(user => {
            const id = (typeof crypto !== 'undefined' && crypto.randomUUID) ? crypto.randomUUID() : (Date.now().toString(36) + Math.random().toString(36).slice(2));
            personas[id] = {
                id: id,
                avatar: user.picture.medium,
                firstName: user.name.first,
                lastName: user.name.last,
                country: user.location.country,
                countryCode: user.nat,
                age: user.dob.age,
                email: user.email,
                segment: 'Brak segmentu'
            };
        });

        // przypisz segmenty jeśli są załadowane
        if (segments.length > 0) {
            Object.values(personas).forEach(p => p.segment = assignSegment(p));
        }
        filteredPersonas = Object.values(personas);
        displayPersonas();
        updateFilters();
        saveCache();
        showStatus(`Wygenerowano ${count} person`, 'success');
    } catch (error) {
        showStatus('Błąd: ' + error.message + '. Próba wczytania z cache...', 'error');
        loadCache();
    }
}

// wyświetlenie person
function displayPersonas() {
    const container = document.getElementById('personasTable');
    
    if (filteredPersonas.length === 0) {
        container.innerHTML = '<p class="no-data">Brak danych do wyświetlenia</p>';
        return;
    }

    let html = `
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Kraj</th>
                    <th>Wiek</th>
                    <th>Email</th>
                    <th>Segment</th>
                </tr>
            </thead>
            <tbody>
    `;

    filteredPersonas.forEach(p => {
        html += `
            <tr>
                <td><img src="${p.avatar}" alt="Avatar" class="avatar"></td>
                <td>${p.firstName}</td>
                <td>${p.lastName}</td>
                <td>${p.country} (${p.countryCode})</td>
                <td>${p.age}</td>
                <td>${maskEmail(p.email)}</td>
                <td>${p.segment}</td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

// aktualizacja filtrów
function updateFilters() {
    const countries = [...new Set(Object.values(personas).map(p => p.country))].sort();
    const countrySelect = document.getElementById('filterCountry');
    countrySelect.innerHTML = '<option value="">Wszystkie</option>';
    countries.forEach(c => {
        countrySelect.innerHTML += `<option value="${c}">${c}</option>`;
    });

    const segmentsList = [...new Set(Object.values(personas).map(p => p.segment))].sort();
    const segmentSelect = document.getElementById('filterSegment');
    segmentSelect.innerHTML = '<option value="">Wszystkie</option>';
    segmentsList.forEach(s => {
        segmentSelect.innerHTML += `<option value="${s}">${s}</option>`;
    });
}

// zastosowanie filtrów
function applyFilters() {
    const country = document.getElementById('filterCountry').value;
    const ageMin = parseInt(document.getElementById('filterAgeMin').value) || 0;
    const ageMax = parseInt(document.getElementById('filterAgeMax').value) || 999;
    const segment = document.getElementById('filterSegment').value;

    filteredPersonas = Object.values(personas).filter(p => {
        if (country && p.country !== country) return false;
        if (p.age < ageMin || p.age > ageMax) return false;
        if (segment && p.segment !== segment) return false;
        return true;
    });

    displayPersonas();
    showStatus(`Znaleziono ${filteredPersonas.length} person`, 'info');
}

// sortowanie po wieku
function sortByAge() {
    filteredPersonas.sort((a, b) => a.age - b.age);
    displayPersonas();
    showStatus('Posortowano po wieku', 'success');
}

// sortowanie po nazwisku
function sortByName() {
    filteredPersonas.sort((a, b) => a.lastName.localeCompare(b.lastName));
    displayPersonas();
    showStatus('Posortowano po nazwisku', 'success');
}

// экспорт CSV
function exportCSV() {
    if (filteredPersonas.length === 0) {
        showStatus('Brak danych do eksportu', 'error');
        return;
    }

    let csv = 'Imię;Nazwisko;Kraj;Wiek;Email;Segment\n';
    filteredPersonas.forEach(p => {
        csv += `${p.firstName};${p.lastName};${p.country};${p.age};${maskEmail(p.email)};${p.segment}\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'persony.csv';
    link.click();

    showStatus('Wyeksportowano do CSV', 'success');
}

// демография выкрес
function generateChart() {
    if (filteredPersonas.length === 0) {
        showStatus('Brak danych do wykresu', 'error');
        return;
    }

    const countryData = {};
    filteredPersonas.forEach(p => {
        countryData[p.country] = (countryData[p.country] || 0) + 1;
    });

    const countries = Object.keys(countryData).sort((a, b) => countryData[b] - countryData[a]).slice(0, 10);
    const counts = countries.map(c => countryData[c]);

    const container = document.getElementById('chartContainer');
    container.style.display = 'block';

    const canvas = document.getElementById('demographicChart');
    const ctx = canvas.getContext('2d');
    
    canvas.width = 800;
    canvas.height = 400;

    // чистка
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // słupkowy
    const barWidth = canvas.width / countries.length - 20;
    const maxCount = Math.max(...counts);
    const scale = (canvas.height - 60) / maxCount;

    countries.forEach((country, i) => {
        const barHeight = counts[i] * scale;
        const x = i * (barWidth + 20) + 10;
        const y = canvas.height - barHeight - 30;

        ctx.fillStyle = '#667eea';
        ctx.fillRect(x, y, barWidth, barHeight);

        ctx.fillStyle = '#333';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(country, x + barWidth / 2, canvas.height - 10);
        ctx.fillText(counts[i], x + barWidth / 2, y - 5);
    });

    // zapisz jako PNG
    setTimeout(() => {
        canvas.toBlob(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'wykres_demograficzny.png';
            link.click();
        });
    }, 100);

    showStatus('Wygenerowano wykres demograficzny', 'success');
}

// PDF
function generatePDF() {
    if (filteredPersonas.length === 0) {
        showStatus('Brak danych do raportu', 'error');
        return;
    }

    const avgAge = (filteredPersonas.reduce((sum, p) => sum + p.age, 0) / filteredPersonas.length).toFixed(1);
    const countries = [...new Set(filteredPersonas.map(p => p.country))];
    const segments = [...new Set(filteredPersonas.map(p => p.segment))];

    const reportWindow = window.open('', '_blank');
    reportWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Profil Grupy - Raport</title>
            <style>
                body { font-family: Arial; padding: 40px; }
                h1 { color: #667eea; }
                .stat { margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 8px; }
            </style>
        </head>
        <body>
            <h1>Profil Grupy - Raport</h1>
            <div class="stat">
                <strong>Liczba person:</strong> ${filteredPersonas.length}
            </div>
            <div class="stat">
                <strong>Średni wiek:</strong> ${avgAge} lat
            </div>
            <div class="stat">
                <strong>Kraje:</strong> ${countries.join(', ')}
            </div>
            <div class="stat">
                <strong>Segmenty:</strong> ${segments.join(', ')}
            </div>
            <div class="stat">
                <strong>Zakres wieku:</strong> ${Math.min(...filteredPersonas.map(p => p.age))} - ${Math.max(...filteredPersonas.map(p => p.age))} lat
            </div>
            <button onclick="window.print()">Drukuj / Zapisz PDF</button>
        </body>
        </html>
    `);

    showStatus('Wygenerowano raport PDF', 'success');
}

function showStatus(message, type) {
    const statusDiv = document.getElementById('statusMessage');
    statusDiv.textContent = message;
    statusDiv.className = `status-message ${type}`;
    statusDiv.style.display = 'block';

    setTimeout(() => {
        statusDiv.style.display = 'none';
    }, 5000);
}

// инициализация (фигня)
document.addEventListener('DOMContentLoaded', function() {
    loadSettings();
    loadCache();
});