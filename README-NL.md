<div align="center">  
  <a href="README.md"   >   TR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/TR.png" alt="TR" height="20" /></a>  
  <a href="README-EN.md"> | EN <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/US.png" alt="EN" height="20" /></a>  
  <a href="README-AZ.md"> | AZ <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AZ.png" alt="AZ" height="20" /></a>  
  <a href="README-DE.md"> | DE <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/DE.png" alt="DE" height="20" /></a>  
  <a href="README-FR.md"> | FR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/FR.png" alt="FR" height="20" /></a>  
  <a href="README-AR.md"> | AR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AR.png" alt="AR" height="20" /></a>  
  <a href="README-NL.md"> | NL <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/NL.png" alt="NL" height="20" /></a>  
</div>

# Handleiding voor het gebruik van de Domainnameapi-module

Deze module is een integratie van 'domainnameapi.com' voor WiseCP.

## 📦 Downloaden — gebruik altijd de Releases!

⬇️ **Download hier de nieuwste geteste versie: https://github.com/domainreseller/wisecp-dna/releases/latest**

> ⚠️ Gebruik **niet** de groene knop **Code → Download ZIP** — die downloadt de ruwe ontwikkelbranch. Release-pakketten zijn geversioneerd, getest en klaar voor productie.

## Vereisten

- WiseCP versie 3 of hoger is vereist.
- PHP versie 7.4 of hoger is vereist.
- De PHP Soap-extensie moet geactiveerd zijn.

## Installatie

1. Plaats de "coremio" map uit de gedownloade map binnen de WiseCP-installatiemap (Bijvoorbeeld: /home/wisecp/public_html). Plaats de bestanden `.gitignore`, `README.md` en `LICENSE` niet.
2. Controleer of de mapstructuur correct is (Bijvoorbeeld: /home/wisecp/public_html/coremio/modules/Registrars/DomainNameApi/DomainNameApi.php).
3. Ga naar het beheerderspaneel van WiseCP.
4. Ga naar het menu Producten/Diensten en selecteer "Domeinregistratie".
5. Klik op de installatiestap.

## Update

Plaats de map "coremio" uit de gedownloade map in de map waar WISECP is geïnstalleerd. Stuur het bestand config.php niet mee. Als u dat doet, kunnen uw huidige instellingen worden gewist.

![Screenshot van de installatie](https://github.com/domainreseller/wisecp-dna/assets/118720541/0cc8cca1-980e-4ae2-928a-28a809da87eb)

### Informatie voor resellergebruiker

1. Voer de gebruikersnaam en wachtwoord van de reseller in.
2. Klik op de knop "Opslaan".

## 🔑 API-gegevens — Gebruikersnaam/Wachtwoord of Reseller ID/API Key?

Beide worden ondersteund — voer ze in dezelfde twee modulevelden in; de module detecteert automatisch welke API wordt gebruikt:

| U heeft | Veld "Gebruikersnaam" | Veld "Wachtwoord" | Gebruikte API |
|---|---|---|---|
| **Nieuwe panelgegevens** (aanbevolen) | Reseller ID — UUID zoals `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx` | API Key | REST |
| **Oude (legacy) gegevens** | API-gebruikersnaam | API-wachtwoord | SOAP |

> 💡 U vindt uw **Reseller ID** en **API Key** in uw DomainNameAPI-paneel onder **API-instellingen**.
> ⚠️ Dit zijn **API-gegevens** — het e-mailadres en wachtwoord waarmee u op het paneel inlogt, werken hier **niet**.

Er is geen extra configuratie nodig — als het gebruikersnaamveld een UUID bevat, gebruikt de module de moderne REST API, anders klassiek SOAP.

### Verbinding testen

1. Klik op de knop "Verbinding testen" om te controleren of de verbinding succesvol tot stand is gebracht.

## Importeren van domein TLD's

1. Klik op het tabblad "TLD's importeren" om de domeinnaamextensies te importeren.
2. Alle extensies worden succesvol geïmporteerd.

## Importeren van domeinnamen

1. Klik op het tabblad "Importeren" om de domeinnamen te bekijken.
2. U ziet een lijst met beschikbare domeinen. Selecteer het domein dat u wilt importeren en wijs het toe aan de gewenste klant, en klik vervolgens op de knop "Importeren".

Dat is alles! U kunt nu de Domainnameapi-module succesvol gebruiken in WiseCP.



## Terugkeer- en Foutcodes met Uitleg

| Code | Uitleg                                          | Details                                                                                                                                                                     |
|------|-------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1000 | Command completed successfully                  | Opdracht succesvol uitgevoerd                                                                                                                                               |
| 1001 | Command completed successfully; action pending. | Opdracht succesvol uitgevoerd; actie in behandeling                                                                                                                         |
| 2003 | Required parameter missing                      | Vereiste parameter ontbreekt. Bijvoorbeeld: Ontbrekend telefoonnummer in contactgegevens                                                                                    |
| 2105 | Object is not eligible for renewal              | Object komt niet in aanmerking voor vernieuwing, update-acties vergrendeld. Status mag niet "clientupdateprohibited" zijn. Kan te wijten zijn aan andere statusvoorwaarden. |
| 2200 | Authentication error                            | Authenticatiefout, autorisatiecode onjuist of domein is geregistreerd bij een andere registrar.                                                                             |
| 2302 | Object exists                                   | Domeinnaam of nameserver-informatie bestaat al in de database. Kan niet worden geregistreerd.                                                                               |
| 2303 | Object does not exist                           | Domeinnaam of nameserver-informatie bestaat niet in de database. Nieuwe registratie vereist.                                                                                |
| 2304 | Object status prohibits operation               | Objectstatus verbiedt de actie, updates vergrendeld. Status mag niet "clientupdateprohibited" zijn. Kan te wijten zijn aan andere statusvoorwaarden.                        |
