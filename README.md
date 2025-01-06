<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Handover Note

## About this App

Hi there 👋🏾, I'm <a href="https://www.justinamehunke.com">Justin Amehunke</a>, the developer who originally revamped this application.

First of all, allow me to assure you that this application has been developed with you in mind. The application was built using Laravel. As you may already know, the laravel framework is a web application framework with expressive, elegant syntax. I believe most of the code written in here have been made easier to understand. Laravel takes the pain out of development by providing a structure and workflow that allow for clear and rapid development. However I have provided comments where I think you would need a little more explanation to quickly understand what is happening.

Always start from the route and cross check the controller a route is connected to, to cross check the logic in there.
Also another trickiest part of this application has to do with how relationships are defined across the models. I tried to make it as clear as possible, but if you have any questions, please do not hesitate to ask. Look up my name on google, I can't be missed.

With that being said, the following are the few things I would like to through light on to help you understand the code.

Note: Use control + p to go to the logic reference paths.

### Custom Login logic: Auth with token sent via SMS or Email

The custom login logic is implemented in the

path: app\Providers\JetstreamServiceProvider.php:38

### Forced HTTPS

path: app\Providers\AppServiceProvider.php:25

### Request Forms Blueprint

path: resources\views\documents-products\request-blueprint.blade.php:155

### Form-specific validation

The request forms have series of validations. Some such as ID validation, Phone number validation, and bank account validation use mostly the same validation logic even though the step within the form where that validation maybe called may be different.

path: resources\views\documents-products\request-blueprint.blade.php:672

e.g.: Activatated In Educator Form Here: resources\views\documents-products\products\educator.blade.php:2017

Most of the product forms have this unique validation that needs to be satisfied before moving from a step to a next step while filling the form.

e.g.: path: resources\views\documents-products\products\educator.blade.php:2103

Aside that we have input specific validations such as calculation of sum assured and premium, phone number and ID length validation.

e.g: path: resources\views\documents-products\products\educator.blade.php:2322

### DB For new Clients

It is important to note that this application has been build to easily fit into the existing application db structure.
Even though some few improvement such as normalization of the various form requests table to create a different table for beneficiaries and covers have been made.
In an event where there is a need to deploy this applicaton for a new client, most of the relevant table migrations have to be crated reversely from the current database. (Submit the table structures to chatGPT and task it the create the laravel migrations for you).

### File Storare

S3 Bucket storage

-- For documents

$targetDir = $isSignature ? 'documents/signatures/' : 'documents/';

-- For signatures

$targetDir = signatures/

-- For IDs

$targetDir = documents/

-- For Complaints

$targetDir = attachments/

--
ON S3: move documents from

oldmutualdocs-justin

to

oldmutualdocs

## Fields added the existing tables

### tbl_users

Alter the existing users table by running the following sql script:

ALTER TABLE tbl_users
ADD `email_verified_at` timestamp NULL DEFAULT NULL,
ADD `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
ADD `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
ADD `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
ADD `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD `current_team_id` bigint unsigned DEFAULT NULL;

### documents applications

ALTER TABLE tbl_document_applications ADD COLUMN flag_request INT DEFAULT 0;
ALTER TABLE tbl_document_applications ADD COLUMN flag_comment TEXT NULL;

### tbl_document_checklist

form_filled

Migrating old cover data

migration-optimised.sql:165

Migrating old beneficiary data

migration-optimised.sql:279

## Happy Coding...

### Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
