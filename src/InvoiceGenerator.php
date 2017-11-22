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

    private $invoiceLine;

    private $patternName;

    public function __construct()
    {
        parent::__construct();

        $invoiceLines = DB::table('pixiu_invoices')->get();

        foreach ($invoiceLines as $invoiceLine) {
            if ($invoiceLine->actual_year != Carbon::now()->year) {
                $this->setActualYear();
                $this->resetInvoiceNumber();
            }
        }
        
    }

    public function generateInvoice($lineName, array $variables, $templatePath = null, $forcedInvoiceNumber = null)
    {
        $invoiceNumber = $forcedInvoiceNumber;
        if (!$invoiceNumber){
            $invoiceNumber = $this->getInvoiceNumber($lineName);
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

    private function renderPdf($twig, array $variables)
    {
        $pdf = app('dompdf.wrapper');
        $html = $twig->render($variables);

        $html = str_replace("\n", "", $html);
        $html = str_replace("\r", "", $html);

        $pdf->setOptions(['isFontSubsettingEnabled' => true, 'isRemoteEnabled' => true])->loadHTML($html);

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

    public function getInvoiceNumber($lineName)
    {
        $invoiceLine = DB::table('pixiu_invoices')->where('name', $lineName)->first();

        if (!$invoiceLine) {
            throw new \Exception('Invoice line \'' . $lineName . ' \' not found. Did you create one?');
        }

        DB::table('pixiu_invoices')->where('name', $lineName)->increment('invoice_number');

        $this->invoiceLine = $invoiceLine;

        $pattern = $invoiceLine->pattern;

        return strtr($pattern, [
            '{year}' => $invoiceLine->actual_year,
            '{number}' => $invoiceLine->invoice_number
        ]);
    }
}
