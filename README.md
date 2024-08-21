Untuk menghapus ekstensi `.php` dari URL pada XAMPP, Anda bisa menggunakan file `.htaccess` dengan cara melakukan konfigurasi mod_rewrite di Apache. Berikut langkah-langkahnya:

1. **Aktifkan mod_rewrite di Apache:**
   - Buka file `httpd.conf` di `C:\xampp\apache\conf\httpd.conf`.
   - Cari baris yang berisi `#LoadModule rewrite_module modules/mod_rewrite.so`.
   - Hapus tanda `#` di awal baris tersebut untuk mengaktifkan mod_rewrite.
   - Simpan file tersebut dan restart Apache dari XAMPP Control Panel.

2. **Buat atau edit file `.htaccess`:**
   - Di dalam folder proyek Anda (misalnya `C:\xampp\htdocs\project`), buat file bernama `.htaccess` jika belum ada.
   - Tambahkan kode berikut di dalam file `.htaccess`:

     ```apache
     <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^([^\.]+)$ $1.php [NC,L]
     </IfModule>
     ```

   Kode di atas akan memeriksa apakah file atau direktori yang diminta tidak ada, kemudian akan mencoba untuk mengakses file dengan ekstensi `.php` yang sesuai.

3. **Akses URL tanpa ekstensi `.php`:**
   - Sekarang kamu bisa mengakses file PHP tanpa menulis `.php` di akhir URL. Misalnya, jika kamu memiliki file `index.php`, kamu bisa mengaksesnya dengan `http://localhost/project/index` tanpa perlu menambahkan `.php` di akhir.

Setelah mengikuti langkah-langkah ini, file `.php` kamu bisa diakses tanpa perlu menuliskan ekstensi `.php` di URL.
