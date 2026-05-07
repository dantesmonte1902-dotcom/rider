# Rider

Küçük PHP router tabanlı sürücü başvuru ve yönetim uygulaması.  
(A small PHP router-based driver-application & admin-panel app.)

---

## İçindekiler / Table of Contents

1. [Gereksinimler](#gereksinimler)
2. [XAMPP ile Kurulum (Yerel)](#xampp-ile-kurulum-yerel)
3. [Veritabanı Kurulumu](#veritabanı-kurulumu)
4. [Admin Kullanıcısı Oluşturma / Şifre Sıfırlama](#admin-kullanıcısı-oluşturma--şifre-sıfırlama)
5. [Routing ve .htaccess Notları](#routing-ve-htaccess-notları)
6. [URL Yapısı](#url-yapısı)
7. [Canlıya Alma (Hosting)](#canlıya-alma-hosting)
8. [Geliştirici Notları](#geliştirici-notları)

---

## Gereksinimler

| Bileşen   | Minimum   |
|-----------|-----------|
| PHP       | 8.1+      |
| MySQL     | 5.7+ / MariaDB 10.4+ |
| Apache    | `mod_rewrite` etkin |
| Sunucu    | XAMPP (yerel) veya paylaşımlı hosting |

---

## XAMPP ile Kurulum (Yerel)

1. **XAMPP'ı başlatın** — Apache ve MySQL servislerini açın.
2. **Projeyi htdocs'a kopyalayın:**
   ```
   C:\xampp\htdocs\rider\
   ```
   Klasörün içinde doğrudan `index.php` bulunmalı  
   (yani `C:\xampp\htdocs\rider\index.php` var olmalı).
3. **`mod_rewrite` aktif mi kontrol edin:**  
   `C:\xampp\apache\conf\httpd.conf` dosyasında şu satırın `#` işaretini kaldırın:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
   Ve `<Directory "C:/xampp/htdocs">` bloğunda:
   ```
   AllowOverride All
   ```
   olduğundan emin olun; `None` ise `All` yapın.
4. Apache'yi yeniden başlatın.
5. Tarayıcıda açın: `http://localhost/rider/`

---

## Veritabanı Kurulumu

1. phpMyAdmin'i açın: `http://localhost/phpmyadmin/`
2. Yeni veritabanı oluşturun; önerilen isim: **`deneme`**  
   (farklı isim kullanıyorsanız `app/config.php` içindeki `DB_NAME` sabitini güncelleyin).
3. Oluşturduğunuz veritabanını seçin → **İçe Aktar (Import)** sekmesi  
   → `db/schema.sql` dosyasını seçip çalıştırın.

Şema şu tabloları oluşturur:

| Tablo           | Açıklama |
|-----------------|----------|
| `admin_users`   | Yönetici hesapları (`email`, `password_hash`) |
| `applications`  | Sürücü başvuruları |
| `cities`        | Şehir listesi (başvuru formunda dropdown) |
| `vehicle_types` | Araç tipi listesi |

Schema ayrıca `cities` ve `vehicle_types` için örnek veriler ekler.

---

## Admin Kullanıcısı Oluşturma / Şifre Sıfırlama

Güvenlik gereği şifre SQL dosyasında plain-text olarak **bulunmaz**.  
Bunun yerine dahil edilen `reset-admin.php` yardımcı scriptini kullanın.

> **⚠️ Güvenlik Notu:**  
> `reset-admin.php` yalnızca `localhost` / `127.0.0.1` üzerinde çalışır.  
> Canlı sunucuda kullandıktan sonra **MUTLAKA silin**.

### İlk admin kullanıcısı oluşturma

```
http://localhost/rider/reset-admin.php?action=create&email=admin@example.com&pass=SifrenizBuraya
```

### Mevcut şifreyi sıfırlama

```
http://localhost/rider/reset-admin.php?email=admin@example.com&pass=YeniSifre123!
```

### Tüm admin kullanıcılarını listeleme

```
http://localhost/rider/reset-admin.php?action=list
```

Şifre sıfırlama işlemi tamamlandıktan sonra admin paneline giriş yapın:

```
http://localhost/rider/admin/login
```

---

## Routing ve .htaccess Notları

Uygulama **tek giriş noktası (front controller)** yöntemini kullanır:  
tüm HTTP istekleri `index.php`'ye yönlendirilir, gerçek dosya/klasör istekleri hariç.

### `.htaccess` içeriği (proje kökünde)

```apache
Options -Indexes

RewriteEngine On
RewriteBase /rider/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

**Önemli noktalar:**

- `RewriteBase` değeri XAMPP için `/rider/` olmalıdır.  
  Domain root'unda çalışıyorsanız `/` yapın.
- CSS/JS/resim gibi statik dosyalar `.htaccess` olmadan da çalışır  
  (gerçek dosyalar `index.php`'ye yönlendirilmez).
- **Klasör index.php şimleri kullanmayın** (`admin/login/index.php` gibi).  
  Bu yöntem POST routing'ini bozar. Sadece `.htaccess` + front controller yeterlidir.
- Trailing slash normalize edilir: `/admin/login/` → `/admin/login` otomatik.

### `app/config.php` içinde `BASE_PATH`

```php
define('BASE_PATH', '/rider');   // XAMPP'de
define('BASE_PATH', '');         // Domain root'unda (örn: sercan.com.tr)
```

---

## URL Yapısı

| URL | Açıklama |
|-----|----------|
| `GET  /rider/` | Ana sayfa |
| `GET  /rider/apply` | Sürücü başvuru formu |
| `POST /rider/apply` | Başvuru kaydet |
| `GET  /rider/apply/success` | Başvuru başarı sayfası |
| `GET  /rider/admin/login` | Admin giriş sayfası |
| `POST /rider/admin/login` | Admin giriş işlemi |
| `GET  /rider/admin/logout` | Oturumu kapat |
| `GET  /rider/admin` | Dashboard (auth gerektirir) |
| `GET  /rider/admin/applications` | Başvuru listesi (auth gerektirir) |
| `GET  /rider/admin/applications/{id}` | Başvuru detay (auth gerektirir) |
| `POST /rider/admin/applications/{id}/status` | Başvuru durumu güncelle |
| `POST /rider/admin/applications/{id}/delete` | Başvuru sil |
| `POST /rider/admin/applications/{id}/note` | Başvuruya admin notu ekle/güncelle |
| `POST /rider/admin/applications/bulk` | Toplu başvuru işlemleri |
| `GET  /rider/admin/cities` | Şehir yönetimi (liste/ekle/düzenle/sil) |
| `GET  /rider/admin/vehicles` | Araç tipi yönetimi (liste/ekle/düzenle/sil) |
| `GET  /rider/admin/users` | Admin kullanıcı yönetimi |
| `GET  /rider/admin/settings/password` | Şifre değiştirme |

---

## Canlıya Alma (Hosting)

### www / non-www Canonical

Arama motoru optimizasyonu ve session tutarlılığı için:  
`.htaccess`'e `www` → `non-www` (veya tersi) yönlendirmesi ekleyin:

```apache
# www → non-www
RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
RewriteRule ^ https://%1%{REQUEST_URI} [R=301,L]
```

Session çerezi her iki hostta ayrı tutulur; bu yüzden **tek host** kullanın.

### Ortam Değişkenleri

| Değişken   | Varsayılan | Açıklama |
|------------|------------|----------|
| `DB_HOST`  | `localhost` | MySQL host |
| `DB_NAME`  | `deneme` | Veritabanı adı |
| `DB_USER`  | `root` | Kullanıcı adı |
| `DB_PASS`  | *(boş)* | Şifre |
| `APP_LOCAL`| *(yok)* | `1` yapılırsa `reset-admin.php` her hostta aktif olur |

Hosting kontrol panelinizde (cPanel/Plesk) veya `.env` sistemiyle set edin.

### `BASE_PATH` Güncelleme

Siteniz domain root'unda çalışıyorsa `app/config.php`:

```php
define('BASE_PATH', '');
```

Alt klasörde çalışıyorsa (örn. `sercan.com.tr/rider`):

```php
define('BASE_PATH', '/rider');
```

ve `.htaccess` içinde `RewriteBase /rider/` olmalı.

---

## Geliştirici Notları

- **PHP 8.1+** gereklidir (`str_ends_with`, `str_starts_with`, `never` dönüş tipi).
- CSRF koruması her form için otomatik; `csrf_field()` helper'ı kullanın.
- Admin oturumu `$_SESSION['admin_id']` ile kontrol edilir.
- Session fixation'a karşı login sonrası `session_regenerate_id(true)` çağrılır.
- Tüm çıktılar `e()` (htmlspecialchars wrapper) ile escape edilir.
- Veritabanı sorguları PDO prepared statements kullanır.
