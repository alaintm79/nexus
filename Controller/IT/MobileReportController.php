<?php

namespace App\Controller\IT;

use App\Entity\IT\Mobile;
use App\Repository\IT\MobileRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/it/report/mobile')]
class MobileReportController extends AbstractController
{
    #[Route('/totals', name: 'app_it_mobile_totals')]
    public function totals(Request $request, MobileRepository $mobileRepository, Pdf $pdf): Response
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Móviles',
            'titulo' => 'Total de Móviles'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('reports/it_mobile_totals.pdf.twig', [
            'mobiles' => $mobileRepository->getTotalsGroupByUnidad($unit)
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html, [
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'movil_totales.pdf'
        );
    }

    #[Route('/{id<[1-9]\d*>}/form', name: 'app_it_mobile_user_form')]
    public function userForm(Mobile $mobile, Pdf $pdf): Response
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();

        if(($unit !== 'ALL') && ($unit !== $mobile->getUsername()->getUnit()->getUnit())){
            throw new AccessDeniedException;
        }

        $html = $this->renderView('reports/it_mobile_user_form.pdf.twig', [
            'mobile' => $mobile
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'movil_totales.pdf'
        );
    }
}
