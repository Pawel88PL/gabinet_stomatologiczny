# Aplikacja Zarządzania Kliniką Dentystyczną
![image](https://github.com/Pawel88PL/gabinet_stomatologiczny/assets/97385295/21e1ea88-c659-438e-bd89-59b9d38a5ea6)

## Opis
Aplikacja Zarządzania Kliniką Dentystyczną to kompleksowe narzędzie przeznaczone do zarządzania wizytami, dostępnością, a także profilami pacjentów i personelu w klinice dentystycznej. Umożliwia efektywne planowanie, monitorowanie i śledzenie wizyt dentystycznych dla zarówno pacjentów, jak i personelu kliniki.

## Funkcje
- **Logowanie i rejestracja**: Umożliwia pacjentom i personelowi kliniki rejestrację oraz logowanie.
- **Zarządzanie wizytami**: Pacjenci mogą rezerwować, przeglądać i anulować wizyty. Personel ma możliwość przeglądania, aktualizowania statusów i anulowania wizyt.
- **Zarządzanie dostępnością**: Dentystom umożliwia dodawanie, edycję oraz usuwanie swoich dostępnych terminów.
- **Panel administracyjny**: Umożliwia administratorom zarządzanie profilami personelu, w tym dodawanie nowych dentystów i edycję ich danych.
- **Interaktywny kalendarz**: Umożliwia pacjentom przeglądanie dostępności dentystów i rezerwację wizyt za pomocą łatwego w obsłudze kalendarza.
- **Notyfikacje**: System wysyła powiadomienia dotyczące rezerwacji, anulowania i zmian w statusie wizyt.

## Technologie
- **Backend**: PHP.
- **Frontend**: HTML, CSS (Bootstrap), JavaScript (w tym biblioteki takie jak FullCalendar i SweetAlert2).
- **Baza danych**: MySQL.

## Uruchomienie
1. Sklonuj repozytorium do lokalnego środowiska.
2. Skonfiguruj połączenie z bazą danych w `config/database.php`.
3. Uruchom serwer (np. Apache) i przetestuj aplikację lokalnie.

## Bezpieczeństwo
Aplikacja stosuje standardowe praktyki bezpieczeństwa, takie jak hashowanie haseł, przygotowane zapytania SQL, walidacja i sanitacja danych wejściowych, aby chronić przed zagrożeniami takimi jak SQL Injection i XSS.
