## Installation
- Require package via composer
```
composer require pixiucz/invoices
```
- And then register `InvoicesServiceProvider` in your `config\app.php` (laravel) or in boot method of your October's `Plugin.php`
```
Pixiucz\Invoices\InvoicesServiceProvider::class,
```
- Once installed, you are required to run migrations – either by running 
```
php artisan migrate
``` 
or using provided cli command 
```
php artisan invoices:migrate
``` 
(this command will migrate only package’s table)
- You have to create at least one named pattern, that will be used on invoices. You can create pattern using
```
php artisan invoices:MakePattern “name_of_pattern” “pattern”
```
- Your pattern have to be string with two slots -> {year} where correct year will be inserted and {number} for invoice number.
  - Example: 
  ```
  php artisan invoices:MakePattern "eshop" "My-eshop-invoice-{year}/{number}"
  ```

## Basic usage
- Once instantiated, `InvoiceGenerator` provides public method `generateInvoice`
- This method requires one parameter, two other are optional

1. **String** name of pattern (invoice line)
1. **Array** of all variables that you want to be printed in provided tempalte. (**required**)
2. **String** path to your `htm` file that will serve as template. This template should use [Twig syntax](https://twig.symfony.com/doc/2.x/templates.html) and at very least define `{{ invoice_number }}` where generated invoice number will be printed. (**optional**)
3. **int** invoice number, this will bypass inner invoice number generator (**optional**)

- Method **returns array** with `'invoice_number'` and rendered `'pdf'` key-value pairs.

### Example
```php
public function handleInvoice(Pixiucz\Invoices\InvoiceGenerator $invoiceGenerator, $templateVariables)
{
  $invoice = $invoiceGenerator->generateInvoice("eshop", $templateVariables);
  return $invoice['pdf'];
}
```

## Format of array with variables (all optional)
- Company
	- name
	- zip
	- address
	- ico
	- dic
	- ic_dph
	- bank
	- account_number
	- iban
	- swift
- delivery_address
	- first_name
	- last_name
	- address
	- zip
	- city
	- country
- billing_address
	- first_name
	- last_name
	- address
	- zip
	- city
	- country
	- ic
	- dic
- updated_at
- variants 
	- variant
		- ean
		- name
		- quantity
		- price
		- price_without_tax
		- sum_without_tax
		- tax
		- sum
	- …
- delivery_option
	- name
	- price
	- price_without_tax
	- tax
- sum 
	- sum_without_tax
	- sum_tax_only
	- sum
- currency (currency symbol)
- status [‘canceled’, ‘refunded’] -> leave empty when neither of these
