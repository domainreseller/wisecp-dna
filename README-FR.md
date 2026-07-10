<div align="center">  
  <a href="README.md"   >   TR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/TR.png" alt="TR" height="20" /></a>  
  <a href="README-EN.md"> | EN <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/US.png" alt="EN" height="20" /></a>  
  <a href="README-AZ.md"> | AZ <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AZ.png" alt="AZ" height="20" /></a>  
  <a href="README-DE.md"> | DE <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/DE.png" alt="DE" height="20" /></a>  
  <a href="README-FR.md"> | FR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/FR.png" alt="FR" height="20" /></a>  
  <a href="README-AR.md"> | AR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AR.png" alt="AR" height="20" /></a>  
  <a href="README-NL.md"> | NL <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/NL.png" alt="NL" height="20" /></a>  
</div>

# Guide d'utilisation du module Domainnameapi

Ce module est une intégration de 'domainnameapi.com' pour WiseCP.

## 📦 Téléchargement — utilisez toujours les Releases !

⬇️ **Téléchargez la dernière version testée ici : https://github.com/domainreseller/wisecp-dna/releases/latest**

> ⚠️ N'utilisez **pas** le bouton vert **Code → Download ZIP** — il télécharge la branche de développement brute. Les paquets de release sont versionnés, testés et prêts pour la production.

## Prérequis

- WiseCP version 3 ou supérieure est requise.
- PHP version 7.4 ou supérieure est requise.
- L'extension PHP Soap doit être activée.

## Installation

1. Copiez le dossier "coremio" contenu dans le dossier que vous avez téléchargé à l'intérieur du dossier d'installation de WiseCP (Exemple : /home/wisecp/public_html). Ne copiez pas les fichiers `.gitignore`, `README.md` et `LICENSE`.
2. Assurez-vous que la structure du dossier est correcte (Exemple : /home/wisecp/public_html/coremio/modules/Registrars/DomainNameApi/DomainNameApi.php).
3. Accédez au panneau d'administration de WiseCP.
4. Accédez au menu Produits/Services et sélectionnez "Enregistrement de domaine".
5. Cliquez sur l'étape d'installation.

## Mise à jour

Déplacez le dossier "coremio" du dossier téléchargé dans le dossier où WISECP est installé. Ne transférez pas le fichier config.php. Si vous le faites, vos paramètres actuels pourraient être supprimés.

![Capture d'écran de l'installation](https://github.com/domainreseller/wisecp-dna/assets/118720541/0cc8cca1-980e-4ae2-928a-28a809da87eb)

### Informations de l'utilisateur revendeur

1. Entrez le nom d'utilisateur et le mot de passe du revendeur.
2. Cliquez sur le bouton "Enregistrer".

## 🔑 Identifiants API — Nom d'utilisateur/Mot de passe ou Reseller ID/API Key ?

Les deux sont pris en charge — saisissez-les dans les deux mêmes champs du module ; le module détecte automatiquement quelle API utiliser :

| Vous disposez de | Champ « Nom d'utilisateur » | Champ « Mot de passe » | API utilisée |
|---|---|---|---|
| **Nouveaux identifiants du panneau** (recommandé) | Reseller ID — UUID de la forme `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx` | API Key | REST |
| **Identifiants hérités (legacy)** | Nom d'utilisateur API | Mot de passe API | SOAP |

> 💡 Vous trouverez votre **Reseller ID** et votre **API Key** dans votre panneau DomainNameAPI, section **Paramètres API**.
> ⚠️ Ce sont des **identifiants API** — l'e-mail et le mot de passe de connexion à votre panneau ne fonctionneront **pas** ici.

Aucune configuration supplémentaire n'est nécessaire — si le champ nom d'utilisateur contient un UUID, le module utilise l'API REST moderne, sinon le SOAP classique.

### Test de la connexion

1. Cliquez sur le bouton "Tester la connexion" pour vérifier si la connexion a été établie avec succès.

## Importation des TLD de domaine

1. Cliquez sur l'onglet "Importer les TLD" pour importer les extensions de noms de domaine.
2. Toutes les extensions seront importées avec succès.

## Importation des noms de domaine

1. Cliquez sur l'onglet "Importer" pour afficher les noms de domaine.
2. Vous verrez une liste des domaines disponibles. Sélectionnez le domaine que vous souhaitez importer et associez-le au client souhaité, puis cliquez sur le bouton "Importer".

C'est tout ! Vous pouvez maintenant utiliser le module Domainnameapi avec succès dans WiseCP.

## Codes de Retour et d'Erreur avec Explications

| Code | Explication                                     | Détails                                                                                                                                                                                             |
|------|-------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1000 | Command completed successfully                  | Commande exécutée avec succès                                                                                                                                                                       |
| 1001 | Command completed successfully; action pending. | Commande exécutée avec succès ; action en attente                                                                                                                                                   |
| 2003 | Required parameter missing                      | Paramètre requis manquant. Par exemple : Numéro de téléphone manquant dans les informations de contact                                                                                              |
| 2105 | Object is not eligible for renewal              | L'objet n'est pas éligible pour le renouvellement, les actions de mise à jour sont verrouillées. Le statut ne doit pas être "clientupdateprohibited". Peut être dû à d'autres conditions de statut. |
| 2200 | Authentication error                            | Erreur d'authentification, code d'autorisation incorrect, ou le domaine est enregistré chez un autre registrar.                                                                                     |
| 2302 | Object exists                                   | Le nom de domaine ou les informations de serveur de noms existent déjà dans la base de données. Ne peut pas être enregistré.                                                                        |
| 2303 | Object does not exist                           | Le nom de domaine ou les informations de serveur de noms n'existent pas dans la base de données. Nouvel enregistrement requis.                                                                      |
| 2304 | Object status prohibits operation               | Le statut de l'objet interdit l'action, les mises à jour sont verrouillées. Le statut ne doit pas être "clientupdateprohibited". Peut être dû à d'autres conditions de statut.                      |
