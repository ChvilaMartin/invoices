<?php namespace Pixiucz\Invoices;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use Illuminate\Support\Facades\DB;
use Twig_Loader_Filesystem;
use Twig_Environment;

class InvoiceGenerator extends Model
{
    protected $table = "pixiu_invoices";
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    /**
     * Actual year used for invoice.
     * @var integer
     */
    private $invoiceYear;


    public function __construct()
    {
        parent::__construct();

        $recordExists = DB::table('pixiu_invoices')->first();

        if (!$recordExists) {
            DB::table('pixiu_invoices')->insert([
                'actual_year' => Carbon::now()->year,
                'invoice_number' => 0
            ]);
        }
        $this->invoiceYear = DB::table('pixiu_invoices')->first()->actual_year;

        if (!($this->invoiceYear) OR ($this->invoiceYear != Carbon::now()->year)) {
            $this->invoiceYear = $this->setActualYear();
            $this->resetInvoiceNumber();
        }
    }

    public function generateInvoice(array $variables, string $templatePath = null, $forcedInvoiceNumber = null)
    {
        $invoiceNumber = $forcedInvoiceNumber;
        if (!$invoiceNumber){
            $invoiceNumber = ($this->invoiceYear * 1000000) + $this->getInvoiceNumber();
        }
        $variables['invoice_number'] = $invoiceNumber;

        $twig = $this->prepareTwig($templatePath);

        // Temporary error silencing
        $pdf = @$this->renderPdf($twig, $variables);

        return [
            'invoice_number' => $invoiceNumber,
            'pdf' => $pdf
        ];
    }

    private function prepareTwig($templatePath)
    {
        $fileName = "invoice.htm";
        $path = __DIR__ . "/views/";

        if ($templatePath ) {
            $fileName = basename($templatePath);
            $path = dirname($templatePath);
        }

        $loader = new Twig_Loader_Filesystem($path);
        $twig = new Twig_Environment($loader);
        return $twig->load($fileName);
    }

    private function renderPdf($twig, $variables)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($twig->render($variables));
        return $pdf->stream();
    }

    private function resetInvoiceNumber()
    {
        DB::table('pixiu_invoices')->update(['invoice_number' => 0]);
    }

    private function setActualYear()
    {
        DB::table('pixiu_invoices')->update(['actual_year' => Carbon::now()->year]);
        return Carbon::now()->year;
    }

    private function getInvoiceNumber()
    {
        DB::table('pixiu_invoices')->increment('invoice_number');
        return DB::table('pixiu_invoices')->first()->invoice_number;
    }

    public function debug() // delete once published
    {
        return [
            'invoice number' => DB::table('pixiu_invoices')->first()->invoice_number,
            'actual_year' => DB::table('pixiu_invoices')->first()->actual_year
        ];
    }
}
