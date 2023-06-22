# Nordigen PHP example application with Laravel

## Set-up
---
You'll need to get your `SECRET_ID` and `SECRET_KEY` from the [GoCardless Bank Account Data Portal](https://bankaccountdata.gocardless.com/user-secrets/).
Create  `.env` file and add secrets to the file or pass secrets as a string to `app/Providers/AppServiceProvider.php` to `NordigenService`.
Generate encryption APP_KEY if you don't have one `php artisan key:generate`


To initialize session with a bank, you have to specify `country` (a two-letter country code) and specify your `redirectUrl`.
In the `routes/web.php` edit country parameter.
```php
$country = "LV";
$redirectUrl = "http://localhost:8000/results";
```

## Installation
---
Install dependencies

```bash
composer install
```

Start Laravel application

```bash
php artisan serve
```

### 1. Go to http://localhost:8000/ and select bank
<p align="center">
    <img align="center" src="./resources/_media/f_3_select_aspsp.png" width="200" />
</p>

### 2. Provide consent
<p align="center">
  <img src="./resources/_media/f_4_ng_agreement.jpg" width="200" />
  <img src="./resources/_media/f_4.1_ng_redirect.png" width="200" />
</p>

### 3. Sign into bank (Institution)
<p align="center">
  <img src="./resources/_media/f_5_aspsps_signin.png" width="230" />
  <img src="./resources/_media/f_5.1_aspsps_signin.jpg" width="200" />
  <img src="./resources/_media/f_5.2_aspsps_signin.jpg" width="200" />
</p>

<p align="center">
  <img src="./resources/_media/f_5.3_aspsp_auth.jpg" width="200" />
</p>

### 4. Select accounts
<p align="center">
  <img src="./resources/_media/f_6_aspsp_accs.jpg" width="200" />
</p>

### 5. You will be redirected to specified `redirectUrl` in our case it is `http://localhost:8000/results/` where details, balances and transactions will be returned from your bank account.

