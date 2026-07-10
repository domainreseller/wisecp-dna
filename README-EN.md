<div align="center">  
  <a href="README.md"   >   TR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/TR.png" alt="TR" height="20" /></a>  
  <a href="README-EN.md"> | EN <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/US.png" alt="EN" height="20" /></a>  
  <a href="README-AZ.md"> | AZ <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AZ.png" alt="AZ" height="20" /></a>  
  <a href="README-DE.md"> | DE <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/DE.png" alt="DE" height="20" /></a>  
  <a href="README-FR.md"> | FR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/FR.png" alt="FR" height="20" /></a>  
  <a href="README-AR.md"> | AR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AR.png" alt="AR" height="20" /></a>  
  <a href="README-NL.md"> | NL <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/NL.png" alt="NL" height="20" /></a>  
</div>

# README for Domainnameapi Module

This module is an integration of 'domainnameapi.com' for WiseCP. 

## 📦 Download — always use Releases!

⬇️ **Get the latest tested version here: https://github.com/domainreseller/wisecp-dna/releases/latest**

> ⚠️ Do **not** use the green **Code → Download ZIP** button — that downloads the raw development branch. Release packages are versioned, tested and production-ready.

## Requirements

- WiseCP version 3 or higher is required.
- PHP version 7.4 or higher is required.
- PHP Soap extension must be enabled.

## Installation

1. Copy the "coremio" folder from the downloaded package into the folder where WiseCP is installed (e.g., /home/wisecp/public_html). Do not include the `.gitignore`, `README.md`, and `LICENSE` files.
2. Ensure that the folder structure is correct. For example: /home/wisecp/public_html/coremio/modules/Registrars/DomainNameApi/DomainNameApi.php.
3. Go to the WiseCP admin panel.
4. Navigate to Products/Services menu and select "Domain Registration".
5. Click on the "Installation" step.

## Update
Place the "coremio" folder from the downloaded folder into the folder where WISECP is installed. Do not send the config.php file. If you do, your current settings might be deleted.

![Installation Screen](https://github.com/domainreseller/wisecp-dna/assets/118720541/0cc8cca1-980e-4ae2-928a-28a809da87eb)

### Reseller User Credentials

1. Enter the reseller username and password.
2. Click the "Save" button.

## 🔑 API Credentials — Username/Password or Reseller ID/API Key?

Both are supported — enter them into the same two module fields; the module detects which API to use automatically:

| You have | "Username" field | "Password" field | API used |
|---|---|---|---|
| **New panel credentials** (recommended) | Reseller ID — UUID like `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx` | API Key | REST |
| **Legacy credentials** | API username | API password | SOAP |

> 💡 Find your **Reseller ID** and **API Key** in your DomainNameAPI panel under **API Settings**.
> ⚠️ These are **API credentials** — your panel login e-mail and password will **not** work here.

No extra configuration is needed — if the username field contains a UUID the module uses the modern REST API, otherwise classic SOAP.

### Test Connection

1. Click the "Test Connection" button to check if the connection is successfully established.

## Importing Domain TLDs

1. Click on the "Import TLDs" tab to import the domain name extensions.
2. All the extensions will be imported successfully.

## Importing Domain Names

1. Click on the "Import" tab to import the domain names.
2. You will see a list of domains. Select the domain you want to import and assign it to the desired customer. Then click the "Import" button.

That's it! You can now successfully use the Domainnameapi module in WiseCP.



## Return and Error Codes with Explanations

| Code | Explanation                                     | Details                                                                                                                                        |
|------|-------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| 1000 | Command completed successfully                  | Command completed successfully                                                                                                                 |
| 1001 | Command completed successfully; action pending. | Command completed successfully; action pending                                                                                                 |
| 2003 | Required parameter missing                      | Required parameter missing. For example: Missing phone number in contact information                                                           |
| 2105 | Object is not eligible for renewal              | Object is not eligible for renewal, update actions locked. Status must not be "clientupdateprohibited". May be due to other status conditions. |
| 2200 | Authentication error                            | Authentication error, authorization code incorrect, or domain is registered with another registrar.                                            |
| 2302 | Object exists                                   | Domain name or nameserver information already exists in the database. Cannot be registered.                                                    |
| 2303 | Object does not exist                           | Domain name or nameserver information does not exist in the database. New registration required.                                               |
| 2304 | Object status prohibits operation               | Object status prohibits the action, updates locked. Status must not be "clientupdateprohibited". May be due to other status conditions.        |

