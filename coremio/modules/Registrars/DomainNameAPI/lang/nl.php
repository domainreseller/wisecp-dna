<?php

return [
    'name'                 => 'DomainNameAPI',
    'description'          => 'Met domainnameapi.com, een van de populaire domeinnaamregistrars, kunnen alle domeinnaamtransacties onmiddellijk worden uitgevoerd via de domein-API. Definieer hiervoor uw domainnameapi.com-clientaccountinformatie in de volgende velden.',
    'importTldButton'      => 'Meer dan 750 extensies importeren',
    'hour'                 => "Uur",
    'fields'               => [
        'balance'       => "Saldo",
        'username'      => 'Reseller gebruikersnaam',
        'password'      => 'Reseller wachtwoord',
        'test-mode'     => 'Testmodus',
        'privacyFee'    => 'Whois Beschermingskosten',
        'adp'           => 'Prijzen automatisch bijwerken',
        'importTld'     => 'Importeer extensies',
        'cost-currency' => 'Kostenvaluta',
        'api-v2'        => "Gebruik de nieuwe versie",
        'resellerid'    => "Reseller ID",
        'periodic-sync' => "Periodieke synchronisatie",
        'sync-count'    => "Aantal synchronisaties per minuut",
        'sync-delay'    => "Synchronisatiefrequentie",
    ],
    'desc'                 => [
        'privacyFee'    => "<br>Vraag om een vergoeding voor de Whois-beschermingsdienst.",
        'test-mode'     => 'Activeren om te verwerken in de testmodus',
        'adp'           => 'Haalt de prijzen dagelijks automatisch op, en de prijs wordt vastgesteld tegen het winstpercentage',
        'importTld-1'   => 'Automatisch alle extensies importeren',
        'importTld-2'   => 'Alle domeinextensies en kosten die op de API zijn geregistreerd, worden collectief geïmporteerd.',
        'cost-currency' => '',
        'periodic-sync' => "Het synchronisatie-evenement draait elke minuut, als je het activeert, verwerkt het volgens andere instellingen",
        'sync-count'    => "Aantal domeinen dat moet worden verwerkt in synchronisatie, u kunt een hoog aantal kiezen als u veel domeinnamen heeft. U moet kleine aantallen kiezen om rate limits te voorkomen.",
        'sync-delay'    => "De frequentie waarmee elke domeinnaam kan worden gesynchroniseerd. Het wordt aanbevolen om de frequentie lang te houden om rate limits te voorkomen. Als u het kort houdt, worden sommige domeinen mogelijk niet gesynchroniseerd.",
    ],
    'tabDetail'            => 'API -informatie',
    'tabImport'            => 'Importeren',
    'testButton'           => 'Test verbinding',
    'importNote'           => 'U kunt eenvoudig de domeinnamen overdragen die al zijn geregistreerd in het systeem van de provider. De geïmporteerde domeinnamen worden gemaakt als een add-on, domeinnamen die momenteel zijn geregistreerd in het systeem zijn gemarkeerd groen.',
    'importStartButton'    => 'Importeren',
    'saveButton'           => 'Instellingen opslaan',
    'error1'               => 'API -informatie is niet beschikbaar',
    'error2'               => 'Domein- en uitbreidingsinformatie is niet aanwezig',
    'error3'               => 'Er is een fout opgetreden bij het ophalen van het contact -ID',
    'error4'               => 'Kan statusinformatie niet krijgen',
    'error5'               => 'De overdrachtsinformatie kon niet worden opgehaald',
    'error6'               => 'Nadat u de API -provider hebt verwerkt, kunt u de status van de bestelling activeren',
    'error7'               => 'PHP Soap is niet op uw server geïnstalleerd. Neem contact op met uw hostingprovider voor meer informatie.',
    'error8'               => 'Voer de API -informatie in',
    'error9'               => 'De importbewerking is mislukt',
    'error10'              => 'Er is een fout opgetreden',
    'error11'              => 'De bedrijfsnaam moet uit minstens twee woorden bestaan.',
    'error12'              => 'Documenten en controle zijn vereist voordat de domeinnaam geactiveerd wordt.',
    'success1'             => 'Instellingen met succes opgeslagen',
    'success2'             => 'Verbindingstest is geslaagd',
    'success3'             => 'Importeren met succes voltooid',
    'success4'             => 'Extensies werden met succes geïmporteerd',
    'version1'             => 'Uw huidige versie:',
    'version2'             => 'Gelieve te updaten.',
    'version3'             => 'Updatepagina',
    'version4'             => 'Uw versie is up-to-date!',
    'headerImport'         => 'De volgende domeinnamen worden geïmporteerd',
    'noImportDomains'      => 'Geen domeinnamen gevonden om te importeren.',
    'importQuestion'       => ' domein wordt geïmporteerd. Weet je het zeker?',
    'yes'                  => 'Ja',
    'no'                   => 'Nee',
    'importProcessing'     => 'Importproces is aan de gang...',
    'process'              => 'Proces',
    'importFinished'       => 'Importproces voltooid.',
    'okey'                 => 'OK',
    'tabImportTld'         => 'Importeer extensies',
    'importTldNote'        => 'U kunt de geregistreerde extensies en kosten in de API kiezen en gezamenlijk importeren. Alle prijsberekeningen zijn in USD. Om automatische synchronisatie uit te schakelen, selecteert u de optie Excl(Exclude)',
    'tld'                  => 'Extensie',
    'dna'                  => 'DNA?',
    'cost'                 => 'Kosten',
    'current'              => 'Verkoop',
    'margin'               => 'Winst',
    'register'             => 'Registreren',
    'renew'                => 'Vernieuwen',
    'transfer'             => 'Overdragen',
    'noTldSelected'        => 'Geen TLD geselecteerd',
    'noTldSelectedDesc'    => 'Selecteer een TLD om te importeren',
    'numofTLDSelected'     => ' u synchroniseert de extensie, weet je het zeker?',
    'numofTLDSynced'       => 'Extensiesynchronisatie voltooid',
    'numofTLDSyncedTxt'    => 'Het proces is succesvol voltooid',
    'numofTLDNotSynced'    => 'Fout',
    'numofTLDNotSyncedTxt' => 'Er is een fout opgetreden. Probeer het opnieuw.',
    'stillProcessing'      => 'Proces is nog steeds aan de gang...',
    'eplasedTime'          => 'API-verbinding duurde :time: seconden',
];
