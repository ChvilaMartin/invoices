## Installation
- Require package via composer
```
composer require pixiucz/invoices=dev-master
```
- And then register `InvoicesServiceProvider` in your `config\app.php` (laravel) or in boot method of your October's `Plugin.php`
```
Pixiucz\Invoices\InvoicesServiceProvider::class,
```
- You also need to run `php artisan migrate` or any other command that will run migrations and create tables

## Basic usage
- Once instantiated, `InvoiceGenerator` provides public method `generateInvoice`
- This method requires one parameter, two other are optional

1. **Array** of all variables that you want to be printed in provided tempalte. (**required**)
2. **String** path to your `htm` file that will serve as template. This template should use [Twig syntax](https://twig.symfony.com/doc/2.x/templates.html) and at very least define `{{ invoice_number }}` where generated invoice number will be printed. (**optional**)
3. **int** invoice number, this will bypass inner invoice number generator (**optional**)

- Method **returns array** with `'invoice_number'` and rendered `'pdf'` key-value pairs.

### Example
```php
public function example(Pixiucz\Invoices\InvoiceGenerator $invoiceGenerator)
{
  $invoice = $invoiceGenerator->generateInvoice($templateVariables);
  $invoice = $invoiceGenerator->generateInvoice($templateVariables, 'path/to/your/template.htm');
  $invoice = $invoiceGenerator->generateInvoice($templateVariables, 'path/to/your/template.htm', 12345);
  $invoice = $invoiceGenerator->generateInvoice($templateVariables, null, 12345);
  return $invoice['pdf'];
}
```
