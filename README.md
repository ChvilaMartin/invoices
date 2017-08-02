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
- This method requires 2 paramteres
1. **String** path to your `htm` file that will serve as template. This template should use **Twig** syntax and at very least define `{{ invoice_number }}` where generated invoice number will be printed.
  
2. **Array** of all variables that you want to be printed in provided tempalte.

- Last parameter is optional and allows you to bypass invoice number generator and push your own invoice number to template by passing **int**.

- Method **returns array** with `'invoice_number'` and rendered `'pdf'` key-value pairs.

### Example
```php
public function example(Pixiucz\Invoices\InvoiceGenerator $invoiceGenerator)
{
  $templateVariables = ['Supplier' => 'Example']
  
  $invoice = $invoiceGenerator->generateInvoice('path/to/my/invoice_template.htm', $templateVariables);
  return $invoice['pdf'];
}
```
