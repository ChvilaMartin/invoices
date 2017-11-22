<?php namespace Pixiucz\Invoices;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Pixiucz\Invoices\Enums\ExceptionStrings;

class Renderer
{
    private $twig;
    private $pdfRenderer;

    public function __construct()
    {
        $this->pdfRenderer = app('dompdf.wrapper');
        $this->loadDefaultTemplate();
    }

    public function loadTemplate($templatePath = null)
    {
        if (!isset($templatePath)) {
            return;
        }

        $fileName = basename($templatePath);
        $path = dirname($templatePath);

        $this->loadTwig($path, $fileName);
    }

    public function renderPdf($variables = [], $templatePath = null)
    {
        $this->loadTemplate($templatePath);

        $html = $this->renderHtml($variables);

        $this->pdfRenderer->setOptions(['isFontSubsettingEnabled' => true, 'isRemoteEnabled' => true])->loadHTML($html);

        return $this->pdfRenderer->stream();
    }

    public function renderHtml($variables = [], $templatePath = null)
    {
        $this->loadTemplate($templatePath);

        $html = $this->twig->render($variables);
        $html = str_replace("\n", "", $html);
        $html = str_replace("\r", "", $html);

        return $html;
    }

    private function loadDefaultTemplate()
    {
        $fileName = "invoice.htm";
        $path = __DIR__ . "/views/";

        $this->loadTwig($path, $fileName);
    }

    private function loadTwig($path, $fileName)
    {
        $loader = new Twig_Loader_Filesystem($path);
        $env = new Twig_Environment($loader);
        $this->twig = $env->load($fileName);
    }

}