# Bz module for Decotatoo' ERP

## Installation

Bz module adalah package untuk mensikronisasikan data dari erp ke website e-commerce berbasis WooCommerce ataupun sebalikanya.

Bz module memerlukan plugin WordPress [DWI (Decotatoo WooCommerce Integration)](https://github.com/decotatoo/wp-plugin-dwi) terinstall pada website e-commerce.

Panduan pemasangan terdapat 2 bagian, yaitu di sisi ERP dan di sisi website e-commerce.

### ERP

Install package menggunakan Composer.

Kemudian jalankan perintah berikut untuk mempublikasikan aset dan sumber daya dari package.

```bash
php artisan vendor:publish --tag=bz-config
```
```bash
php artisan vendor:publish --tag=bz-views
```
```bash
php artisan vendor:publish --tag=bz-migrations
```

Pada file .env di root folder, silahkan sesuaikan config nya

```env
APP_URL=

BZ_BASE_URL="http://localhost:8080"

BZ_DASHBOARD_PATH="/wp/wp-admin/"

BZ_REST_BASE_PATH="/wp-json/dwi-erp/v1"
BZ_REST_USERNAME=
BZ_REST_PASSWORD=

BZ_WEBHOOK_SECRET=

BZ_WOOCOMMERCE_CONSUMER_KEY=
BZ_WOOCOMMERCE_CONSUMER_SECRET=
BZ_WOOCOMMERCE_VERIFY_SSL=true
```

berikut adalah penjelasan dari setiap config:
- **`BZ_BASE_URL`** adalah URL website e-commerce berbasis WooCommerce. Contoh: `http://deco-front.test`

- **`BZ_WOOCOMMERCE_CONSUMER_KEY`** dan **`BZ_WOOCOMMERCE_CONSUMER_SECRET`** adalah API Key  untuk mengakses API woocommerce pada website e-commerce. Untuk panduan resmi dapat diakses di link berikut https://woocommerce.com/document/woocommerce-rest-api.

- **`BZ_REST_USERNAME`** adalah username dari sebuah akun dengan pangkat admin pada website e-commerce. Untuk panduan resmi cara mendapatkan username dapat diakses pada link berikut [https://wordpress.org/support/article/users-your-profile-screen/](https://wordpress.org/support/article/users-your-profile-screen/#:~:text=is%20accessible,screen)

- **`BZ_REST_PASSWORD`** adalah Application Password dari website e-commerce. Untuk panduan resmi cara membuat Application Password terdapat pada link berikut [https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/#:~:text=From%20the%20Edit%20User%20page,safely%20revoked)

- **`BZ_WEBHOOK_SECRET`** adalah secret yg digunakan untuk menverifikasi webhook dari woocommerce.

Kemudian jalankan perintah berikut untak memigrasikan dan _seeding_ database

```bash
php artisan db:migrate
```
```bash
php artisan db:seed
```

#### Menjalankan _Scheduler_ dan _Queue Worker_

Setiap perubahan pada ERP, data disinkornasikan dari ERP ke website e-commerce dengan menggunakan _scheduler_ dan _queues_. 

Kita menggunakan _scheduler_ untuk sinkornasi data keseluruhan dari ERP ke website e-commerce pada saat diluar jam kerja. Kita juga menggunakan _queues_ mensirkonasikan sebagian data dari ERP ke website e-commerce pada saat jam kerja.


Untuk menjalankan _scheduler_, silahkan ikutin panduan berikut https://laravel.com/docs/8.x/scheduling#running-the-scheduler

terdapat 4 _queue_, yaitu:
- `webhook`
- `default`
- `high`
- `low`

untuk menjalankan _queue worker_, gunakan perintah berikut pada 4 prosess yang berbeda

```bash
php artisan queue:work --queue=webhook
```
```bash
php artisan queue:work --queue=default
```
```bash
php artisan queue:work --queue=high
```
```bash
php artisan queue:work --queue=low
```

Untuk menjaga proses `queue:work` berjalan secara permanen di latar belakang ikuti panduan berikut https://laravel.com/docs/8.x/queues#supervisor-configuration.

### Website E-Commerce

Untuk melakukan installasi pada website e-commerce, silahkan mengikuti panduan berikut [decotatoo/wordpress/README.md](https://github.com/decotatoo/wordpress/blob/master/README.md)