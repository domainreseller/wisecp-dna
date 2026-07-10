<div align="center">  
  <a href="README.md"   >   TR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/TR.png" alt="TR" height="20" /></a>  
  <a href="README-EN.md"> | EN <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/US.png" alt="EN" height="20" /></a>  
  <a href="README-AZ.md"> | AZ <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AZ.png" alt="AZ" height="20" /></a>  
  <a href="README-DE.md"> | DE <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/DE.png" alt="DE" height="20" /></a>  
  <a href="README-FR.md"> | FR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/FR.png" alt="FR" height="20" /></a>  
  <a href="README-AR.md"> | AR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AR.png" alt="AR" height="20" /></a>  
  <a href="README-NL.md"> | NL <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/NL.png" alt="NL" height="20" /></a>  
</div>

# Domainnameapi Modulunun İstifadəsi üçün README

Bu modul, WiseCP üçün 'domainnameapi.com' inteqrasiyasıdır.

## 📦 Yükləmə — həmişə Releases istifadə edin!

⬇️ **Ən son test edilmiş versiyanı buradan yükləyin: https://github.com/domainreseller/wisecp-dna/releases/latest**

> ⚠️ Yaşıl **Code → Download ZIP** düyməsini **istifadə etməyin** — bu düymə xam inkişaf (development) qolunu yükləyir. Release paketləri versiyalanmış, test edilmiş və istehsalata hazırdır.

## Tələblər

- WiseCP'nin 3 və ya daha yüksək versiyası tələb olunur.
- PHP'nin 7.4 və ya daha yüksək versiyası tələb olunur.
- PHP Soap əlavəsi aktiv olmalıdır.

## Quraşdırma

1. Yüklədiyiniz qovluqda olan "coremio" qovluğunu WiseCP-nin quraşdırılmış olduğu qovluğun daxilinə atın (Məsələn: /home/wisecp/public_html). `.gitignore`, `README.md`, `LICENSE` fayllarını atmayın.
2. Qovluq strukturunun düzgün olduğundan əmin olun. (Məsələn: /home/wisecp/public_html/coremio/modules/Registrars/DomainNameApi/DomainNameApi.php)
3. WiseCP-nin idarəetmə panelinə daxil olun.
4. Məhsullar/Xidmətlər menyusuna daxil olun və "Domain Qeydiyyatı"nı seçin.
5. Quraşdırma addımına klikləyin.

## Yenilənmə

Yüklədiyiniz qovluqdakı "coremio" qovluğunu WISECP quraşdırıldığı qovluğa atın. config.php faylını göndərməyin. Əgər göndərsəniz, mövcud parametrləriniz silinə bilər.

![Quraşdırma Ekranı](https://github.com/domainreseller/wisecp-dna/assets/118720541/0cc8cca1-980e-4ae2-928a-28a809da87eb)

### Bayi İstifadəçi Məlumatları

1. Bayi istifadəçi adını və şifrəsini daxil edin.
2. "Yadda Saxla" düyməsinə klikləyin.

## 🔑 API Məlumatları — İstifadəçi adı/Şifrə, yoxsa Reseller ID/API Key?

Hər ikisi dəstəklənir — məlumatları modulda eyni iki sahəyə daxil edin; modul hansı API-nin istifadə olunacağını avtomatik müəyyən edir:

| Sizdə olan | "İstifadəçi adı" sahəsi | "Şifrə" sahəsi | İstifadə olunan API |
|---|---|---|---|
| **Yeni panel məlumatları** (tövsiyə olunur) | Reseller ID — `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx` formatında UUID | API Key | REST |
| **Köhnə (legacy) məlumatlar** | API istifadəçi adı | API şifrəsi | SOAP |

> 💡 **Reseller ID** və **API Key** məlumatlarınızı DomainNameAPI panelinizdə **API Parametrləri** bölməsində tapa bilərsiniz.
> ⚠️ Bunlar **API məlumatlarıdır** — panelə giriş üçün istifadə etdiyiniz e-poçt və şifrə burada **işləməyəcək**.

Əlavə konfiqurasiya tələb olunmur — istifadəçi adı sahəsində UUID varsa, modul müasir REST API-dən, əks halda klassik SOAP-dan istifadə edir.

### Bağlantını Test Et

1. Bağlantını sorunsuz şəkildə qurulub-qurulmadığını yoxlamaq üçün "Bağlantını Test Et" düyməsinə klikləyin.

## Alan TLD-lərini İçəri Aktarma

1. "Uzantıları İçəri Aktar" tabını klikləyərək domain adı uzantılarını içəri aktarın.
2. Bütün uzantılar uğurla içəri aktarılacaq.

## Alan Adlarını İçəri Aktarma

1. "İçəri Aktar" tabını klikləyərək domain adlarını içəri aktarın.
2. Siz bir siyahı görəcəksiniz. İçəri aktarmaq istədiyiniz domaini seçin və müştəriyə təyin edin, sonra "İçəri Aktar" düyməsinə klikləyin.

Bu qədər! Artıq Domainnameapi modulunu



## Qayıdış və Səhv Kodları, Təfərrüatları

| Kod  | Açıqlama                                        | Təfərrüat                                                                                                                                                                      |
|------|-------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1000 | Command completed successfully                  | Əmr uğurla icra edildi                                                                                                                                                         |
| 1001 | Command completed successfully; action pending. | Əmr uğurla icra edildi; amal gözlənilir                                                                                                                                        |
| 2003 | Required parameter missing                      | Tələb olunan parametr yoxdur. Məsələn; Əlaqə məlumatlarında telefon qeyd edilməməsi                                                                                            |
| 2105 | Object is not eligible for renewal              | Nəqliyyat yenilənməyə layiq deyil, yeniləmə əməliyyatları qapalıdır. "clientupdateprohibited" vəziyyət durumu olmamalıdır. Digər vəziyyət durumlarından səbəb götürə bilər     |
| 2200 | Authentication error                            | Doğrulama səhvi, təhlükəsizlik kodu yanlışdır və ya domen başqa bir qeydiyyat şirkətində mövcuddur                                                                             |
| 2302 | Object exists                                   | Domen adı və ya ad server məlumatları verilənlər bazasında mövcuddur. Qeydiyyat edilə bilməz                                                                                   |
| 2303 | Object does not exist                           | Domen adı və ya ad server məlumatları verilənlər bazasında mövcud deyil. Yeni qeydiyyat yaradılmalıdır                                                                         |
| 2304 | Object status prohibits operation               | Domen vəziyyəti əməliyyat üçün maneə törədir, yeniləmə əməliyyatları qapalıdır. Vəziyyət "clientupdateprohibited" olmamalıdır. Digər vəziyyət durumlarından səbəb götürə bilər |
