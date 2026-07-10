<div align="center">  
  <a href="README.md"   >   TR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/TR.png" alt="TR" height="20" /></a>  
  <a href="README-EN.md"> | EN <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/US.png" alt="EN" height="20" /></a>  
  <a href="README-AZ.md"> | AZ <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AZ.png" alt="AZ" height="20" /></a>  
  <a href="README-DE.md"> | DE <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/DE.png" alt="DE" height="20" /></a>  
  <a href="README-FR.md"> | FR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/FR.png" alt="FR" height="20" /></a>  
  <a href="README-AR.md"> | AR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AR.png" alt="AR" height="20" /></a>  
  <a href="README-NL.md"> | NL <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/NL.png" alt="NL" height="20" /></a>  
</div>

# Anleitung zur Verwendung des Domainnameapi-Moduls

Dieses Modul ist eine Integration von 'domainnameapi.com' für WiseCP.

## 📦 Download — immer die Releases verwenden!

⬇️ **Die neueste getestete Version finden Sie hier: https://github.com/domainreseller/wisecp-dna/releases/latest**

> ⚠️ Verwenden Sie **nicht** den grünen Button **Code → Download ZIP** — dieser lädt den rohen Entwicklungszweig herunter. Release-Pakete sind versioniert, getestet und produktionsbereit.

## Voraussetzungen

- WiseCP Version 3 oder höher wird benötigt.
- PHP Version 7.4 oder höher wird benötigt.
- Die PHP Soap-Erweiterung muss aktiviert sein.

## Installation

1. Kopieren Sie den Ordner "coremio" aus dem heruntergeladenen Ordner in das WiseCP-Installationsverzeichnis (Beispiel: /home/wisecp/public_html). Kopieren Sie die Dateien `.gitignore`, `README.md` und `LICENSE` nicht.
2. Stellen Sie sicher, dass die Ordnerstruktur korrekt ist (Beispiel: /home/wisecp/public_html/coremio/modules/Registrars/DomainNameApi/DomainNameApi.php).
3. Gehen Sie zum WiseCP-Verwaltungspanel.
4. Gehen Sie zum Menü Produkte/Dienstleistungen und wählen Sie "Domainregistrierung".
5. Klicken Sie auf den Installations-Schritt.

## Aktualisierung

Verschieben Sie den Ordner "coremio" aus dem heruntergeladenen Ordner in den Ordner, in dem WISECP installiert ist. Senden Sie die Datei config.php nicht. Wenn Sie sie senden, könnten Ihre aktuellen Einstellungen gelöscht werden.

![Screenshot der Installation](https://github.com/domainreseller/wisecp-dna/assets/118720541/0cc8cca1-980e-4ae2-928a-28a809da87eb)

### Informationen für den Wiederverkäuferbenutzer

1. Geben Sie den Benutzernamen und das Passwort des Wiederverkäufers ein.
2. Klicken Sie auf die Schaltfläche "Speichern".

## 🔑 API-Zugangsdaten — Benutzername/Passwort oder Reseller ID/API Key?

Beides wird unterstützt — tragen Sie die Daten in dieselben zwei Modulfelder ein; das Modul erkennt automatisch, welche API verwendet wird:

| Sie haben | Feld "Benutzername" | Feld "Passwort" | Verwendete API |
|---|---|---|---|
| **Neue Panel-Zugangsdaten** (empfohlen) | Reseller ID — UUID wie `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx` | API Key | REST |
| **Alte (Legacy-)Zugangsdaten** | API-Benutzername | API-Passwort | SOAP |

> 💡 Ihre **Reseller ID** und Ihren **API Key** finden Sie in Ihrem DomainNameAPI-Panel unter **API-Einstellungen**.
> ⚠️ Dies sind **API-Zugangsdaten** — Ihre Panel-Login-E-Mail und das zugehörige Passwort funktionieren hier **nicht**.

Es ist keine zusätzliche Konfiguration erforderlich — enthält das Benutzernamen-Feld eine UUID, verwendet das Modul die moderne REST-API, andernfalls klassisches SOAP.

### Verbindungstest

1. Klicken Sie auf die Schaltfläche "Verbindung testen", um zu überprüfen, ob die Verbindung erfolgreich hergestellt wurde.

## Importieren von Domain-TLDs

1. Klicken Sie auf die Registerkarte "TLDs importieren", um die Domainnamenserweiterungen zu importieren.
2. Alle Erweiterungen werden erfolgreich importiert.

## Importieren von Domainnamen

1. Klicken Sie auf die Registerkarte "Importieren", um die Domainnamen anzuzeigen.
2. Sie sehen eine Liste der verfügbaren Domains. Wählen Sie die Domain aus, die Sie importieren möchten, und weisen Sie sie dem gewünschten Kunden zu, und klicken Sie dann auf die Schaltfläche "Importieren".

Das war's! Sie können das Domainnameapi-Modul jetzt erfolgreich in WiseCP verwenden.



## Rückgabe- und Fehlercodes mit Erklärungen

| Code | Erklärung                                       | Details                                                                                                                                                                         |
|------|-------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1000 | Command completed successfully                  | Befehl erfolgreich ausgeführt                                                                                                                                                   |
| 1001 | Command completed successfully; action pending. | Befehl erfolgreich ausgeführt; Aktion ausstehend                                                                                                                                |
| 2003 | Required parameter missing                      | Erforderlicher Parameter fehlt. Zum Beispiel: Telefonnummer in den Kontaktdaten fehlt                                                                                           |
| 2105 | Object is not eligible for renewal              | Objekt ist nicht zur Verlängerung berechtigt, Update-Aktionen gesperrt. Der Status darf nicht "clientupdateprohibited" sein. Möglicherweise aufgrund anderer Statusbedingungen. |
| 2200 | Authentication error                            | Authentifizierungsfehler, Berechtigungscode ungültig oder Domain ist bei einem anderen Registrar registriert.                                                                   |
| 2302 | Object exists                                   | Domänenname oder Nameserver-Informationen sind bereits in der Datenbank vorhanden. Kann nicht registriert werden.                                                               |
| 2303 | Object does not exist                           | Domänenname oder Nameserver-Informationen sind in der Datenbank nicht vorhanden. Neue Registrierung erforderlich.                                                               |
| 2304 | Object status prohibits operation               | Objektstatus verbietet die Aktion, Updates gesperrt. Der Status darf nicht "clientupdateprohibited" sein. Möglicherweise aufgrund anderer Statusbedingungen.                    |
