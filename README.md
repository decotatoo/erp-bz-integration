# WooCommerce Integration module for Decotatoo' ERP

## Installation

WooCommerce Integration module adalah package untuk mensikronisasikan data dari erp ke website e-commerce berbasis WooCommerce ataupun sebalikanya.

WooCommerce Integration module memerlukan plugin WordPress [DWI (Decotatoo WooCommerce Integration)](https://github.com/decotatoo/wp-plugin-dwi) terinstall pada website e-commerce.

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

WOOCOMMERCE_STORE_URL=
WOOCOMMERCE_CONSUMER_KEY=
WOOCOMMERCE_CONSUMER_SECRET=
WOOCOMMERCE_VERIFY_SSL=true

WOOCOMMERCE_WEBHOOK_SECRET=

WI_APP_URL="${WOOCOMMERCE_STORE_URL}/wp-json"
WI_APP_BASE_PATH="/dwi-erp/v1"
WI_APP_USERNAME=
WI_APP_PASSWORD=
```

berikut adalah penjelasan dari setiap config:
- **`WOOCOMMERCE_STORE_URL`** adalah URL website e-commerce berbasis WooCommerce. Contoh: `http://deco-front.test`

- **`WOOCOMMERCE_CONSUMER_KEY`** dan **`WOOCOMMERCE_CONSUMER_SECRET`** adalah API Key  untuk mengakses API woocommerce pada website e-commerce. Untuk panduan resmi dapat diakses di link berikut https://woocommerce.com/document/woocommerce-rest-api.

- **`WI_APP_USERNAME`** adalah username dari sebuah akun dengan pangkat admin pada website e-commerce. Untuk panduan resmi cara mendapatkan username dapat diakses pada link berikut [https://wordpress.org/support/article/users-your-profile-screen/](https://wordpress.org/support/article/users-your-profile-screen/#:~:text=is%20accessible,screen)

- **`WI_APP_PASSWORD`** adalah Application Password dari website e-commerce. Untuk panduan resmi cara membuat Application Password terdapat pada link berikut [https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/#:~:text=From%20the%20Edit%20User%20page,safely%20revoked)