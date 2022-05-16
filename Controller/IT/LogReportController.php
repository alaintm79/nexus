<?php

namespace App\Controller\IT;

use Knp\Snappy\Pdf;
use App\Repository\IT\LogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LogReportController extends AbstractController
{
    #[Route('/it/log/{type}/{id<[1-9]\d*>}/', name: 'app_it_log')]
    public function log(EntityManagerInterface $em, LogRepository $logRepository, Pdf $pdf, int $id, string $type): Response
    {
        $class = "App\\Entity\\IT\\".$type;
        $device = $em->getRepository($class)->findOneBy(['id' => $id]);
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();

        if(($unit !== 'ALL') && ($unit !== $device->getUsername()->getUnit()->getUnit())){
            throw new AccessDeniedException();
        }

        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC',
            'titulo' => 'Registro de cambios'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('reports/it_device_log.pdf.twig', [
            'logs' => $logRepository->findLogByDeviceId($id, $type),
            'device' => $device
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html, [
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'registro_cambios.pdf'
        );
    }
}
