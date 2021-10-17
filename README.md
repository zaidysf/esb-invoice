# ESB Invoice

ESB Invoice is an application that focusing on creating invoices.
This applications are built for [PT Esensi Solusi Buana](https://esb.co.id).

![app](https://raw.githubusercontent.com/zaidysf/esb-invoice/main/public/ss.png)
![invoice print](https://raw.githubusercontent.com/zaidysf/esb-invoice/main/public/ss2.png)

# Table of Content
- [Introduction](#introduction)
- [Installation](#installation)
- [Third Party](#third-party)

## Requirements

* PHP `>= 7.3`

* PhpSpreadsheet: `^1.15`

* PHP extension `php_zip` enabled

* PHP extension `php_xml` enabled

* PHP extension `php_gd2` enabled

* PHP extension `php_iconv` enabled

* PHP extension `php_simplexml` enabled

* PHP extension `php_xmlreader` enabled

* PHP extension `php_zlib` enabled

* [Composer](https://getcomposer.org/)

* MySQL/MariaDB

## Introduction

we can access it via our favorite browser. By default, default host will show us the homepage

## Installation

* Clone this repository

* Copy .env.example to `.env`

* Change values of our `.env` as necessary
(like redis, db and order source file)

* Start our MySQL/MariaDB Server

* Run this command to install dependencies, generate key for laravel and do database migration
    ```
    composer install && php artisan key:generate && php artisan migrate --seed
    ```

* Serve the application by using our own webserver or run below command
    ```
    php artisan serve
    ```

* Default credentials
    ```
    username : admin@esb-invoice.com
    password : 12345678
    ```

## Third-party

* Laravel Invoices

* Laravel Breeze

* GuzzleHttp


## Author

[Zaid Yasyaf](https://www.linkedin.com/in/zaidysf/) :email: [Email Me](mailto:zaid.ug@gmail.com)
