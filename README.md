<p style="color: #ff2f1a">PHP PROJELERİMİZİ AYAĞA KALDIRMAK VE DATABASEDEN YARARLANMAK İÇİN BİR SUNUCU BAŞLATMAMIZ GEREK
<br>BEN XAMPP KULLANDIĞIM İÇİN APACHE VE MYSQL SUNUCULARIMI ÇALIŞTIRARAK BU KOMUTLARI ÇALIŞTIRACAĞIM</p>

**PROJEYİ AYAĞA KALDIRMAK İÇİN GEREKLİ OLAN KURULUMLAR**

`composer install
`
 **composer için gerekli dosyaların kurulumu**

`cp .env.example .env
`
.env dosyasını oluşturur
<br>.env dosyasını açın ve **"DB_CONNECTİON = sqlite"** kısmını **"DB_CONNECTİON = mysql"** olarak değiştirip altındaki # kısımlarını silin

`php artisan key:generate
`
**.env dosyanızdaki eksik yerleri doldurur**

`npm install
`
**bu kod ile gerekli nodemodule dosyalarını kurabilirsiniz**

`php artisan migrate
`
**migration dosyalarından databaseinizi oluşturur**

`php artisan db:seed
`
**databasemizin içinde seeder ve factoryleri yani önceden belirlenmiş ve random dataları yerleştirmemize yarar**

`php artisan serve
`
**projeyi ayağa kaldırır**

<p style="color: #ff2f1a">KURULUMLAR BU KADAR PROJE DOSYALARININ İÇİNDE BULUNAN POSTMAN COLLECTİONU KENDİ POSTMANİNİZE İMPORT EDEREK API ENDPOİNTLERİN ÇALIŞIP ÇALIŞMADIĞINI KONTROL EDEBİLİRSİNİZ
</p>

**PROJE HAKKINDA**

1. **CONTROLLER**: Projede AuthController ve LeaveRequestController adında iki ader controller bulunmakta. Auth controller yöneticinin giriş yapma işlemlerini kontrol ediyor
sistemde yöneticilerin requestleri onaylamasını veya reddetmesini istemişsiniz bunu bir auth sistemi ekleyerek çözdüm.
LeaveRequestController ise sistemdeki requestleri kontrol ediyor.

2. **ENUM KULLANIMI**: Sistemdeki requestlerin statüleri için Enum kullandım. app\Enums

3. **DATABASE DOSYALARI**: Databese klasörünün içinde migration, seeder ve factory dosyaları var buradan bütün database işlemlerine ulaşabilirsiniz.

4. **ROUTİNG**: api.php de belirlediğim endpointler ve hangi fonksiyonları kullandığı mevcuttur
