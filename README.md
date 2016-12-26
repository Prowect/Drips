# Drips PHP-Framework

## Installation

```bash
$ composer create-project prowect/drips -s dev
```

Der *DocumentRoot* des Webservers muss zwingend auf dem `public`-Verzeichnis des `Drips`-Verzeichnisses liegen.

Sollte es nach der Installation zu einem Fehler kommen, ist wie folgt vorzugehen:

 - `php drips key:generate` ausführen
 - den generierten Key kopieren und
 - in die `.env` Datei einfügen: `APP_KEY=your-key-here`

## Update

```bash
$ composer update
```