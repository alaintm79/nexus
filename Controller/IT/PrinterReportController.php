<?php

namespace App\Controller\IT;

use Knp\Snappy\Pdf;
use App\Repository\IT\PrinterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/it/report/printer')]
class PrinterReportController extends AbstractController
{
    #[Route('/totals', name: 'app_it_printer_totals')]
    public function totals(Request $request, PrinterRepository $printerRepository, Pdf $pdf): Response
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Impresoras',
            'titulo' => 'Total de Impresoras'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('reports/it_printer_totals.pdf.twig', [
            'printers' => $printerRepository->getTotalsGroupByUnidad($unit)
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html, [
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'impresoras_totales.pdf'
        );
    }
}
