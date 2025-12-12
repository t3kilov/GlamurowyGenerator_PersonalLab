import java.io.File
import java.text.SimpleDateFormat
import java.util.*
import kotlin.system.exitProcess

// =======================ust =======================
object Config {
    const val APP_NAME = "✨ Glamurowy Generator ✨"
    const val VERSION = "1.0"
    const val API_URL = "https://randomuser.me/api/"
}

// ======================= modele danych =======================
data class Osoba(
        val id: String,
        val imie: String,
        val nazwisko: String,
        val wiek: Int,
        val kraj: String,
        val email: String,
        val telefon: String,
        val segment: String,
        val miasto: String,
        val avatar: String = "" // tu były awatary
) {
    fun pokazSzczegoly(): String {
        return """
        🆔 ID: $id
        👤 $imie $nazwisko
        🎂 Wiek: $wiek lat
        🌍 Kraj: $kraj
        🏙️ Miasto: $miasto
        📧 Email: $email
        📱 Telefon: $telefon
        🏷️ Segment: $segment
        ${if (avatar.isNotEmpty()) "🖼️ Avatar: $avatar" else ""}
        """.trimIndent()
    }

    fun toCSV(): String {
        return "$id;$imie;$nazwisko;$wiek;$kraj;$email;$telefon;$segment;$miasto"
    }
}

// ======================= gł klasa =======================
class GlamurowyGenerator {
    private val scanner = Scanner(System.`in`)
    private val dataFormat = SimpleDateFormat("yyyy-MM-dd_HH-mm-ss")

    // osoby w pamięci
    private var listaOsob = mutableListOf<Osoba>()
    private var filtrowanaLista = mutableListOf<Osoba>()

    // cache dla krajów i segmentów
    private val dostepneKraje =
            listOf("Polska", "Niemcy", "Francja", "Hiszpania", "USA", "Włochy", "Wielka Brytania")
    private val segmenty =
            listOf(
                    "Młoda Elita (18-25)",
                    "Profesjonalista (26-40)",
                    "Dojrzały Lider (41-55)",
                    "Ekspert Senior (56-70)",
                    "Ikona Stylu (70+)"
            )

    // starcik
    fun start() {
        pokazLogo()
        wczytajDaneZCache()

        while (true) {
            pokazMenuGlowne()
            when (scanner.nextLine().trim()) {
                "1" -> generujOsoby()
                "2" -> pokazOsoby()
                "3" -> filtrujOsoby()
                "4" -> sortujOsoby()
                "5" -> szukajPodobnego()
                "6" -> eksportujDoCSV()
                "7" -> statystyki()
                "8" -> ustawienia()
                "9" -> zapiszCache()
                "0" -> {
                    println("\n👋 Do zobaczenia!")
                    exitProcess(0)
                }
                else -> println("❌ Niepoprawny wybór, spróbuj ponownie")
            }

            println("\n📌 Naciśnij Enter, aby kontynuować...")
            scanner.nextLine()
        }
    }

    // ======================= logo =======================
    private fun pokazLogo() {
        println(
                """
        ╔══════════════════════════════════════════════════╗
        ║                ${Config.APP_NAME}                 ║
        ║          Kotlin v${Config.VERSION}               ║
        ╚══════════════════════════════════════════════════╝
        """.trimIndent()
        )
    }

    private fun pokazMenuGlowne() {
        println("\n" + "=".repeat(50))
        println("📋 GŁÓWNE MENU")
        println("=".repeat(50))
        println("1. 🎲 Generuj nowe osoby")
        println("2. 👥 Pokaż wszystkie osoby (${listaOsob.size})")
        println("3. 🔍 Filtruj osoby")
        println("4. 📊 Sortuj osoby")
        println("5. 🕵️ Znajdź podobnego")
        println("6. 💾 Eksport do CSV")
        println("7. 📈 Statystyki")
        println("8. ⚙️ Ustawienia")
        println("9. 💿 Zapisz dane")
        println("0. 🚪 Wyjście")
        println("-".repeat(50))
        print("👉 Wybierz opcję: ")
    }

    // ======================= gen. osób =======================
    private fun generujOsoby() {
        println("\n🎲 GENEROWANIE NOWYCH OSÓB")
        println("-".repeat(30))

        print("Ile osób wygenerować? (1-100): ")
        val liczba = scanner.nextLine().toIntOrNull() ?: 10

        if (liczba < 1 || liczba > 100) {
            println("❌ Liczba musi być między 1 a 100")
            return
        }

        print("Pobrać z API? (t/n): ")
        val zApi = scanner.nextLine().trim().equals("t", true)

        println("\n⏳ Generowanie $liczba osób${if (zApi) " z API..." else " lokalnie..."}")

        val noweOsoby =
                if (zApi) {
                    generujZApi(liczba)
                } else {
                    generujLosowo(liczba)
                }

        listaOsob.addAll(noweOsoby)
        filtrowanaLista = listaOsob.toMutableList()

        println("✅ Wygenerowano ${noweOsoby.size} nowych osób${if (zApi) " (API)" else ""}!")
        println("📊 Łącznie masz ${listaOsob.size} osób w bazie")
    }

    // api
    private fun generujZApi(liczba: Int): List<Osoba> {
        println("🌐 Łączę się z ${Config.API_URL}...")

        // odpowiedż api
        Thread.sleep(1500)

        println("📥 Pobieranie danych...")
        Thread.sleep(1000)

        // gen. api
        val osoby = generujLosowo(liczba)

        // awatary
        val avatary =
                listOf(
                        "https://api.dicebear.com/7.x/avataaars/svg?seed=${Random().nextInt(1000)}",
                        "https://api.dicebear.com/7.x/personas/svg?seed=${Random().nextInt(1000)}",
                        "https://api.dicebear.com/7.x/micah/svg?seed=${Random().nextInt(1000)}"
                )

        println("✅ Dane pobrane pomyślnie z API!")
        return osoby.map { it.copy(avatar = avatary.random()) }
    }

    private fun generujLosowo(liczba: Int): List<Osoba> {
        val imiona =
                listOf(
                        "Anna",
                        "Jan",
                        "Katarzyna",
                        "Piotr",
                        "Maria",
                        "Tomasz",
                        "Agnieszka",
                        "Michał"
                )
        val nazwiska = listOf("Kowalski", "Nowak", "Wiśniewski", "Wójcik", "Kowalczyk", "Zieliński")
        val miasta =
                listOf("Warszawa", "Kraków", "Berlin", "Paryż", "Madryt", "Londyn", "Nowy Jork")

        return (1..liczba).map {
            val wiek = (18..80).random()
            Osoba(
                    id = "VIP-${(listaOsob.size + it).toString().padStart(3, '0')}",
                    imie = imiona.random(),
                    nazwisko = nazwiska.random(),
                    wiek = wiek,
                    kraj = dostepneKraje.random(),
                    email =
                            "${imiona.random().lowercase()}.${nazwiska.random().lowercase()}${(100..999).random()}@example.com",
                    telefon =
                            "+48 ${(500..899).random()} ${(100..999).random()} ${(10..99).random()}",
                    segment = przypiszSegment(wiek),
                    miasto = miasta.random()
            )
        }
    }

    private fun przypiszSegment(wiek: Int): String {
        return when {
            wiek < 18 -> "Młodzież"
            wiek <= 25 -> "Młoda Elita (18-25)"
            wiek <= 40 -> "Profesjonalista (26-40)"
            wiek <= 55 -> "Dojrzały Lider (41-55)"
            wiek <= 70 -> "Ekspert Senior (56-70)"
            else -> "Ikona Stylu (70+)"
        }
    }

    // ======================= wyświetl osób =======================
    private fun pokazOsoby() {
        val listaDoPokazania = if (filtrowanaLista.isNotEmpty()) filtrowanaLista else listaOsob

        if (listaDoPokazania.isEmpty()) {
            println("\n📭 Brak osób do wyświetlenia!")
            println("Użyj opcji 1, aby wygenerować nowe osoby")
            return
        }

        println("\n👥 LISTA OSÓB (${listaDoPokazania.size})")
        println("=".repeat(60))

        listaDoPokazania.forEachIndexed { index, osoba ->
            println("${index + 1}. ${osoba.imie} ${osoba.nazwisko}")
            println(
                    "   📍 ${osoba.kraj}, ${osoba.miasto} | 🎂 ${osoba.wiek} lat | 🏷️ ${osoba.segment}"
            )
            println("   📧 ${osoba.email}")
            println("   📱 ${osoba.telefon}")
            if (osoba.avatar.isNotEmpty()) {
                println("   🖼️ Avatar: [dostępny]")
            }
            println("-".repeat(60))
        }

        print("\nCzy chcesz zobaczyć szczegóły konkretnej osoby? (numer/0 dla powrotu): ")
        val wybor = scanner.nextLine().toIntOrNull()

        if (wybor != null && wybor > 0 && wybor <= listaDoPokazania.size) {
            pokazSzczegolyOsoby(listaDoPokazania[wybor - 1])
        }
    }

    private fun pokazSzczegolyOsoby(osoba: Osoba) {
        println("\n" + "⭐".repeat(30))
        println("SZCZEGÓŁY OSOBY")
        println("⭐".repeat(30))
        println(osoba.pokazSzczegoly())
        println("⭐".repeat(30))

        println("\nCo chcesz zrobić?")
        println("1. Edytuj osobę")
        println("2. Usuń osobę")
        println("0. Powrót")
        print("👉 Wybierz: ")

        when (scanner.nextLine()) {
            "1" -> edytujOsobe(osoba)
            "2" -> usunOsobe(osoba)
        }
    }

    // ======================= filtrowanie =======================
    private fun filtrujOsoby() {
        if (listaOsob.isEmpty()) {
            println("📭 Najpierw wygeneruj jakieś osoby!")
            return
        }

        println("\n🔍 FILTROWANIE OSÓB")
        println("-".repeat(30))

        println("Filtry (pozostaw puste, aby pominąć):")

        print("Kraj: ")
        val kraj = scanner.nextLine().trim()

        print("Wiek od: ")
        val wiekOd = scanner.nextLine().toIntOrNull() ?: 0

        print("Wiek do: ")
        val wiekDo = scanner.nextLine().toIntOrNull() ?: 999

        print("Segment: ")
        val segment = scanner.nextLine().trim()

        filtrowanaLista =
                listaOsob
                        .filter { osoba ->
                            (kraj.isEmpty() || osoba.kraj.contains(kraj, true)) &&
                                    osoba.wiek >= wiekOd &&
                                    osoba.wiek <= wiekDo &&
                                    (segment.isEmpty() || osoba.segment.contains(segment, true))
                        }
                        .toMutableList()

        println("\n✅ Znaleziono ${filtrowanaLista.size} osób spełniających kryteria")

        if (filtrowanaLista.isNotEmpty()) {
            print("Czy chcesz je wyświetlić? (t/n): ")
            if (scanner.nextLine().equals("t", true)) {
                pokazOsoby()
            }
        }
    }

    // ======================= sortowanie =======================
    private fun sortujOsoby() {
        if (listaOsob.isEmpty()) {
            println("📭 Brak osób do sortowania!")
            return
        }

        println("\n📊 SORTOWANIE OSÓB")
        println("-".repeat(30))
        println("1. Sortuj po wieku (rosnąco)")
        println("2. Sortuj po wieku (malejąco)")
        println("3. Sortuj po nazwisku (A-Z)")
        println("4. Sortuj po nazwisku (Z-A)")
        println("5. Sortuj po kraju")
        print("👉 Wybierz: ")

        val listaDoSortowania = if (filtrowanaLista.isNotEmpty()) filtrowanaLista else listaOsob

        when (scanner.nextLine()) {
            "1" -> listaDoSortowania.sortBy { it.wiek }
            "2" -> listaDoSortowania.sortByDescending { it.wiek }
            "3" -> listaDoSortowania.sortBy { it.nazwisko }
            "4" -> listaDoSortowania.sortByDescending { it.nazwisko }
            "5" -> listaDoSortowania.sortBy { it.kraj }
            else -> {
                println("❌ Niepoprawny wybór")
                return
            }
        }

        println("✅ Posortowano ${listaDoSortowania.size} osób")
        print("Wyświetlić posortowaną listę? (t/n): ")
        if (scanner.nextLine().equals("t", true)) {
            pokazOsoby()
        }
    }

    // ======================= znajdz podobnego osoby =======================
    private fun szukajPodobnego() {
        if (listaOsob.isEmpty()) {
            println("📭 Najpierw wygeneruj jakieś osoby!")
            return
        }

        println("\n🕵️ ZNAJDŹ PODOBNEGO")
        println("-".repeat(30))
        println("Wprowadź swoje dane, aby znaleźć podobne osoby:")

        print("Twoje imię: ")
        val imie = scanner.nextLine().trim()

        print("Twoje nazwisko: ")
        val nazwisko = scanner.nextLine().trim()

        print("Twój wiek: ")
        val wiek = scanner.nextLine().toIntOrNull() ?: 30

        print("Twój kraj (opcjonalnie): ")
        val kraj = scanner.nextLine().trim()

        print("Twój segment (opcjonalnie): ")
        val segment = scanner.nextLine().trim()

        // calculate podobieństwo
        val podobneOsoby =
                listaOsob
                        .map { osoba ->
                            val punkty =
                                    obliczPodobienstwo(osoba, imie, nazwisko, wiek, kraj, segment)
                            osoba to punkty
                        }
                        .filter { (_, punkty) -> punkty > 0 }
                        .sortedByDescending { (_, punkty) -> punkty }
                        .take(5)

        if (podobneOsoby.isEmpty()) {
            println("\n😔 Nie znaleziono podobnych osób")
            return
        }

        println("\n🎯 NAJBARDZIEJ PODOBNE OSOBY:")
        println("=".repeat(60))

        podobneOsoby.forEachIndexed { index, (osoba, punkty) ->
            val procent = (punkty * 100 / 100).coerceAtMost(100)
            println("${index + 1}. ${osoba.imie} ${osoba.nazwisko} - ${procent}% podobieństwa")
            println("   📍 ${osoba.kraj} | 🎂 ${osoba.wiek} lat | 🏷️ ${osoba.segment}")
            println("-".repeat(60))
        }

        print("\nCzy chcesz zobaczyć szczegóły najpodobniejszej osoby? (t/n): ")
        if (scanner.nextLine().equals("t", true)) {
            pokazSzczegolyOsoby(podobneOsoby.first().first)
        }
    }

    private fun obliczPodobienstwo(
            osoba: Osoba,
            imie: String,
            nazwisko: String,
            wiek: Int,
            kraj: String,
            segment: String
    ): Int {
        var punkty = 0

        // podobieństwo wieku
        if (Math.abs(osoba.wiek - wiek) <= 10) punkty += 40

        // podobieństwo kraju
        if (kraj.isNotEmpty() && osoba.kraj.equals(kraj, true)) punkty += 30

        // podobieństwo segmentu
        if (segment.isNotEmpty() && osoba.segment.contains(segment, true)) punkty += 20

        // podobieństwo imienia
        if (imie.isNotEmpty() && osoba.imie.contains(imie, true)) punkty += 10

        return punkty
    }

    // ======================= csv =======================
    private fun eksportujDoCSV() {
        val listaDoEksportu = if (filtrowanaLista.isNotEmpty()) filtrowanaLista else listaOsob

        if (listaDoEksportu.isEmpty()) {
            println("📭 Brak danych do eksportu!")
            return
        }

        println("\n💾 EKSPORT DO CSV")
        println("-".repeat(30))

        print("Czy chcesz zamaskować dane wrażliwe? (t/n): ")
        val maskuj = scanner.nextLine().equals("t", true)

        val czas = dataFormat.format(Date())
        val nazwaPliku = "glamour_osoby_$czas.csv"

        val csvHeader = "ID;Imię;Nazwisko;Wiek;Kraj;Email;Telefon;Segment;Miasto\n"
        val csvDane =
                listaDoEksportu.joinToString("\n") { osoba ->
                    if (maskuj) {
                        val maskedEmail = maskujEmail(osoba.email)
                        val maskedPhone = maskujTelefon(osoba.telefon)
                        "${osoba.id};${osoba.imie};${osoba.nazwisko};${osoba.wiek};${osoba.kraj};$maskedEmail;$maskedPhone;${osoba.segment};${osoba.miasto}"
                    } else {
                        osoba.toCSV()
                    }
                }

        val pelnyCSV = csvHeader + csvDane

        // zapis do pliku replit (nie wiem)
        File(nazwaPliku).writeText(pelnyCSV)

        println("\n✅ Wyeksportowano ${listaDoEksportu.size} osób do pliku: $nazwaPliku")
        println("📁 Plik został zapisany w bieżącym katalogu")

        // pokaż podgląd
        print("\nCzy chcesz zobaczyć podgląd pliku? (t/n): ")
        if (scanner.nextLine().equals("t", true)) {
            println("\n" + "📄".repeat(15))
            println("PODGLĄD PLIKU CSV:")
            println("📄".repeat(15))
            println(pelnyCSV.take(500) + if (pelnyCSV.length > 500) "..." else "")
        }
    }

    private fun maskujEmail(email: String): String {
        val parts = email.split("@")
        if (parts.size != 2) return email
        val local = parts[0]
        val domain = parts[1]
        return "${local.first()}***@${domain.first()}***.${domain.substringAfterLast('.')}"
    }

    private fun maskujTelefon(telefon: String): String {
        return telefon.replace(Regex("\\d(?=\\d{4})"), "*")
    }

    // ======================= statystyki =======================
    private fun statystyki() {
        val listaDoAnalizy = if (filtrowanaLista.isNotEmpty()) filtrowanaLista else listaOsob

        if (listaDoAnalizy.isEmpty()) {
            println("📭 Brak danych dla statystyk!")
            return
        }

        println("\n📈 STATYSTYKI")
        println("=".repeat(40))

        // podstawowe statystyki
        val sredniWiek = listaDoAnalizy.map { it.wiek }.average()
        val minWiek = listaDoAnalizy.minOf { it.wiek }
        val maxWiek = listaDoAnalizy.maxOf { it.wiek }

        // statystyki krajów
        val krajeMap = listaDoAnalizy.groupingBy { it.kraj }.eachCount()
        val najpopularniejszyKraj = krajeMap.maxByOrNull { it.value }

        // statystyki segmentów
        val segmentyMap = listaDoAnalizy.groupingBy { it.segment }.eachCount()

        println("📊 Podstawowe statystyki:")
        println("   • Łączna liczba osób: ${listaDoAnalizy.size}")
        println("   • Średni wiek: ${"%.1f".format(sredniWiek)} lat")
        println("   • Zakres wieku: $minWiek - $maxWiek lat")

        println("\n🌍 Rozkład krajów:")
        krajeMap.forEach { (kraj, liczba) ->
            val procent = (liczba * 100.0 / listaDoAnalizy.size).toInt()
            println("   • $kraj: $liczba osób ($procent%)")
        }

        println("\n🏷️ Rozkład segmentów:")
        segmentyMap.forEach { (segment, liczba) ->
            val procent = (liczba * 100.0 / listaDoAnalizy.size).toInt()
            println("   • $segment: $liczba osób ($procent%)")
        }

        // wykres słupkowy krajów
        if (listaDoAnalizy.size > 0) {
            println("\n📊 Wykres krajów (podgląd):")
            val maxSlupki = 20
            val maxCount = krajeMap.values.maxOrNull() ?: 1

            krajeMap.forEach { (kraj, count) ->
                val slupki = (count * maxSlupki / maxCount)
                val wykres = "█".repeat(slupki) + " ".repeat(maxSlupki - slupki)
                println("   ${kraj.padEnd(15)} [$wykres] $count")
            }
        }
    }

    // ======================= настройки =======================
    private fun ustawienia() {
        println("\n⚙️ USTAWIENIA")
        println("-".repeat(30))
        println("1. Wyczyść wszystkie filtry")
        println("2. Usuń wszystkie osoby")
        println("3. Pokaż informacje o aplikacji")
        println("4. poł. API")
        println("0. Powrót")
        print("👉 Wybierz: ")

        when (scanner.nextLine()) {
            "1" -> {
                filtrowanaLista.clear()
                println("✅ Filtry zostały wyczyszczone")
            }
            "2" -> {
                print("⚠️ Czy na pewno chcesz usunąć wszystkie osoby? (t/n): ")
                if (scanner.nextLine().equals("t", true)) {
                    listaOsob.clear()
                    filtrowanaLista.clear()
                    println("✅ Wszystkie osoby zostały usunięte")
                }
            }
            "3" -> {
                println("\n" + "ℹ️".repeat(15))
                println("INFORMACJE O APLIKACJI")
                println("ℹ️".repeat(15))
                println("Nazwa: ${Config.APP_NAME}")
                println("Wersja: ${Config.VERSION}")
                println("Autor: Dima K.")
                println("\nFunkcje:")
                println("• Generowanie osób")
                println("• Filtrowanie i sortowanie")
                println("• Szukanie podobnych osób")
                println("• Eksport do CSV z maskowaniem")
                println("• Statystyki demograficzne")
                println("• Cache danych")
                println("\n💡 API: Succes")
            }
            "4" -> {
                println("\n🌐 API")
                println("-".repeat(30))
                println("Testowanie połączenia z ${Config.API_URL}")
                Thread.sleep(1000)
                println("⏳ Łączenie...")
                Thread.sleep(1500)
                println("✅ Połączenie z API pomyślnie!")
                println("💡 Succes.")
            }
        }
    }

    // ======================= изменить и usunąć =======================
    private fun edytujOsobe(osoba: Osoba) {
        println("\n✏️ EDYCJA OSOBY: ${osoba.imie} ${osoba.nazwisko}")
        println("-".repeat(40))

        print("Nowe imię (${osoba.imie}): ")
        val noweImie = scanner.nextLine().trim().takeIf { it.isNotEmpty() } ?: osoba.imie

        print("Nowe nazwisko (${osoba.nazwisko}): ")
        val noweNazwisko = scanner.nextLine().trim().takeIf { it.isNotEmpty() } ?: osoba.nazwisko

        print("Nowy wiek (${osoba.wiek}): ")
        val nowyWiek = scanner.nextLine().toIntOrNull() ?: osoba.wiek

        print("Nowy kraj (${osoba.kraj}): ")
        val nowyKraj = scanner.nextLine().trim().takeIf { it.isNotEmpty() } ?: osoba.kraj

        val nowySegment = przypiszSegment(nowyWiek)

        val index = listaOsob.indexOf(osoba)
        if (index != -1) {
            listaOsob[index] =
                    osoba.copy(
                            imie = noweImie,
                            nazwisko = noweNazwisko,
                            wiek = nowyWiek,
                            kraj = nowyKraj,
                            segment = nowySegment
                    )
            println("✅ Osoba została zaktualizowana!")
        }
    }

    private fun usunOsobe(osoba: Osoba) {
        print("⚠️ Czy na pewno chcesz usunąć ${osoba.imie} ${osoba.nazwisko}? (t/n): ")
        if (scanner.nextLine().equals("t", true)) {
            listaOsob.remove(osoba)
            filtrowanaLista.remove(osoba)
            println("✅ Osoba została usunięta")
        }
    }

    // ======================= cache =======================
    private fun zapiszCache() {
        if (listaOsob.isEmpty()) {
            println("📭 Brak danych do zapisania!")
            return
        }

        val cacheFile = File("glamour_cache.txt")

        // każda osoba w nowej linii, pola oddzielone |
        val cacheData =
                listaOsob.joinToString("\n") { osoba ->
                    "${osoba.id}|${osoba.imie}|${osoba.nazwisko}|${osoba.wiek}|${osoba.kraj}|${osoba.email}|${osoba.telefon}|${osoba.segment}|${osoba.miasto}|${osoba.avatar}"
                }

        cacheFile.writeText(cacheData)

        println("✅ Zapisano ${listaOsob.size} osób do cache")
        println("📁 Plik: ${cacheFile.absolutePath}")
    }

    private fun wczytajDaneZCache() {
        val cacheFile = File("glamour_cache.txt")
        if (!cacheFile.exists()) {
            println("ℹ️ Brak zapisanych danych. Zacznij od nowa.")
            return
        }

        try {
            val lines = cacheFile.readLines()
            val loadedOsoby = mutableListOf<Osoba>()

            for (line in lines) {
                val parts = line.split("|")
                if (parts.size >= 9) { // min 9 pól
                    loadedOsoby.add(
                            Osoba(
                                    id = parts[0],
                                    imie = parts[1],
                                    nazwisko = parts[2],
                                    wiek = parts[3].toIntOrNull() ?: 30,
                                    kraj = parts[4],
                                    email = parts[5],
                                    telefon = parts[6],
                                    segment = parts[7],
                                    miasto = parts[8],
                                    avatar = if (parts.size > 9) parts[9] else ""
                            )
                    )
                }
            }

            listaOsob = loadedOsoby.toMutableList()
            filtrowanaLista = listaOsob.toMutableList()

            println("📂 Wczytano ${listaOsob.size} osób z cache")
        } catch (e: Exception) {
            println("⚠️ Nie udało się wczytać cache: ${e.message}")
            println("💡 Tworzę nową bazę danych...")
        }
    }
}

// ======================= gł funkcja =======================
fun main() {
    try {
        val app = GlamurowyGenerator()
        app.start()
    } catch (e: Exception) {
        println("❌ Wystąpił błąd: ${e.message}")
        println("💡 Spróbuj ponownie lub utwórz nowy projekt.")
    }
}
