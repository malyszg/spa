# SPA Symfony

Prosty projekt SPA w Symfony z:
- formularzem rezerwacji (zapis do MySQL),
- formularzem kontaktowym,
- Dockerem,
- testem PHPUnit,
- pipeline CI na GitHub Actions.

## Szybki start

1. Uruchom:
   ```bash
   ./bin/run.sh
   ```
   Skrypt automatycznie utworzy `app/.env` z `app/.env.example`, jesli plik nie istnieje.
2. Otworz aplikacje:
   [http://localhost:15014](http://localhost:15014)
3. Otworz phpMyAdmin:
   [http://localhost:8086](http://localhost:8086)

## Zmienne srodowiskowe bazy

W pliku `.env` (w katalogu projektu) sa ustawione:
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`
- `DB_ROOT_PASSWORD`
- `DB_SERVER_VERSION`

## Przydatne komendy

- Testy:
  ```bash
  docker-compose run --rm app php bin/phpunit
  ```
- Zatrzymanie kontenerow:
  ```bash
  docker-compose down
  ```
