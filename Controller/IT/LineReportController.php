<?php

namespace App\Controller\IT;

use App\Entity\IT\Line;
use App\Repository\IT\LineRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/it/report/line')]
class LineReportController extends AbstractController
{
    #[Route('/totals', name: 'app_it_line_totals')]
    public function totals(Request $request, LineRepository $lineRepository, Pdf $pdf): Response
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Lineas',
            'titulo' => 'Total de Lineas'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('reports/it_line_totals.pdf.twig', [
            'lines' => $lineRepository->getTotalsGroupByUnidad($unit)
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html, [
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'movil_totales.pdf'
        );
    }

    #[Route('/{id<[1-9]\d*>}/form', name: 'app_it_line_user_form')]
    public function userForm(Line $line, Pdf $pdf): Response
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();

        if(($unit !== 'ALL') && ($unit !== $line->getUsername()->getUnit()->getUnit())){
            throw new AccessDeniedException;
        }

        $html = $this->renderView('reports/it_line_user_form.pdf.twig', [
            'line' => $line
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'movil_totales.pdf'
        );
    }
}
