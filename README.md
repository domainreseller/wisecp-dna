<div align="center">  
  <a href="README.md"   >   TR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/TR.png" alt="TR" height="20" /></a>  
  <a href="README-EN.md"> | EN <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/US.png" alt="EN" height="20" /></a>  
  <a href="README-AZ.md"> | AZ <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AZ.png" alt="AZ" height="20" /></a>  
  <a href="README-DE.md"> | DE <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/DE.png" alt="DE" height="20" /></a>  
  <a href="README-FR.md"> | FR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/FR.png" alt="FR" height="20" /></a>  
  <a href="README-AR.md"> | AR <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/AR.png" alt="AR" height="20" /></a>  
  <a href="README-NL.md"> | NL <img style="padding-top: 8px" src="https://raw.githubusercontent.com/yammadev/flag-icons/master/png/NL.png" alt="NL" height="20" /></a>  
</div>

# Domainnameapi Modülü 

Bu modül, WiseCP için geliştirilmiş bir 'domainnameapi.com' entegrasyonudur.

## 📦 İndirme — her zaman Releases kullanın!

⬇️ **En güncel ve test edilmiş sürümü buradan indirin: https://github.com/domainreseller/wisecp-dna/releases/latest**

> ⚠️ Yeşil **Code → Download ZIP** düğmesini **kullanmayın** — bu düğme ham geliştirme dalını indirir. Release paketleri sürümlenmiş, test edilmiş ve canlı kullanıma hazırdır.

## Gereksinimler

- WiseCP'nin 3 ve üzeri sürümü gerekmektedir.
- PHP'nin 7.4 ve üzeri sürümü gerekmektedir.
- PHP Soap eklentisi etkinleştirilmelidir.

## Kurulum

1. İndirdiğiniz klasör içindeki "coremio" klasörünün WISECP kurulu olduğu klasörün içine atın. (Örnek: /home/wisecp/public_html) `.gitignore`, `README.md`, `LICENSE` dosyalarını atmayın.
2. Klasör yapısının doğru olduğundan emin olun. (Örnek: /home/wisecp/public_html/coremio/modules/Registrars/DomainNameApi/DomainNameApi.php)
3. WiseCP'nin yönetim paneline gidin.
4. Ürünler/Hizmetler menüsüne gelin ve "Alan Adı Tescili"ni seçin.
5. Kurulum adımına tıklayın.

## Güncelleme

İndirdiğiniz klasör içindeki "coremio" klasörünün WISECP kurulu olduğu klasörün içine atın. config.php dosyasını göndermeyin. Gönderirseniz, mevcut ayarlarınız silinebilir.


![Kurulum Ekran](https://github.com/domainreseller/wisecp-dna/assets/118720541/0cc8cca1-980e-4ae2-928a-28a809da87eb)

### Bayi Kullanıcı Bilgileri

1. Bayi kullanıcı adını ve şifresini girin.
2. "Kaydet" düğmesine tıklayın.

## 🔑 API Bilgileri — Kullanıcı Adı/Şifre mi, Reseller ID/API Key mi?

Her ikisi de desteklenir — bilgileri modüldeki aynı iki alana girin; modül hangi API'nin kullanılacağını otomatik olarak algılar:

| Elinizdeki bilgi | "Kullanıcı Adı" alanı | "Şifre" alanı | Kullanılan API |
|---|---|---|---|
| **Yeni panel bilgileri** (önerilen) | Reseller ID — `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx` formatında UUID | API Key | REST |
| **Eski (legacy) bilgiler** | API kullanıcı adı | API şifresi | SOAP |

> 💡 **Reseller ID** ve **API Key** bilgilerinizi DomainNameAPI panelinizde **API Ayarları** bölümünde bulabilirsiniz.
> ⚠️ Bunlar **API bilgileridir** — panele giriş yaptığınız e-posta/şifre burada **ÇALIŞMAZ**; API Ayarları bölümündeki Reseller ID (UUID) ve API Key kullanılmalıdır.

Ekstra bir yapılandırma gerekmez — kullanıcı adı alanında bir UUID varsa modül modern REST API'yi, aksi halde klasik SOAP'ı kullanır.

### Bağlantıyı Test Etme

1. "Bağlantıyı Test Et" düğmesine tıklayarak bağlantının sorunsuz bir şekilde kurulup kurulmadığını kontrol edin.

## Alan TLD'lerini İçeri Aktarma

1. "Uzantıları İçeri Aktar" sekmesine tıklayarak alan adı uzantılarını içeri aktarın.
2. Tüm uzantılar başarıyla içeri aktarılacaktır.

## Alan Adlarını İçeri Aktarma

1. "İçeri Aktar" sekmesine tıklayarak alan adlarını görüntüleyin.
2. Listede görünen domainleri göreceksiniz. İçeri aktarmak istediğiniz domaini istediğiniz müşteriye eşitleyin ve "İçeri Aktar" düğmesine tıklayın.

Bu kadar! Artık Domainnameapi modülünü WiseCP'de başarıyla kullanabilirsiniz.

## Dönüş ve Hata Kodları ile Açıklamaları

| Kod  | Açıklama                                        | Detay                                                                                                                                                                         |
|------|-------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1000 | Command completed successfully                  | İşlem başarılı.                                                                                                                                                               |
| 1001 | Command completed successfully; action pending. | İşlem başarılı. Fakat işlem şu an tamamlanmak için kuyruğa alındı.                                                                                                            |
| 2003 | Required parameter missing                      | Parametre eksik hatası. Örneğin; Kontak bilgisinde telefon girişi yapılmaması.                                                                                                |
| 2105 | Object is not eligible for renewal              | Domain durumu yenilemeye müsait değil, güncelleme işlemlerine kilitlenmiştir. Durum durumu "clientupdateprohibited" olmamalı. Diğer durum durumlarından kaynaklanabilir.      |
| 2200 | Authentication error                            | Yetki hatası, güvenlik kodu hatalı veya domain başka bir kayıt firmasında bulunuyor.                                                                                          |
| 2302 | Object exists                                   | Domain adı veya name server bilgisi veritabanında mevcut. Kayıt edilemez.                                                                                                     |
| 2303 | Object does not exist                           | Domain adı veya name server bilgisi veritabanında mevcut değil. Yeni kayıt oluşturulmalı.                                                                                     |
| 2304 | Object status prohibits operation               | Domain durumu güncellemeye müsait değildir, güncelleme işlemlerine kilitlenmiştir. Durum durumu "clientupdateprohibited" olmamalı. Diğer durum durumlarından kaynaklanabilir. |



