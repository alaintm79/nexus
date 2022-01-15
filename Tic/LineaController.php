<?php

namespace App\Controller\Tic;

use App\Entity\Tic\Linea\Linea;
use App\Entity\Tic\Linea\LogPlanDatos;
use App\Form\Tic\Linea\LineaType;
use App\Entity\Tic\Linea\LogPlanVoz;
use App\Entity\Tic\Linea\LogSim;
use App\Entity\Tic\Linea\LogUsuario;
use App\Entity\Tic\Linea\PlanExtra;
use App\Form\Tic\Linea\LineaBajaType;
use App\Form\Tic\Linea\PlanExtraType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Tic\Linea\LineaRepository;
use App\Repository\Tic\Linea\PlanExtraRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Linea controller.
 *
 * @Route("tic/lineas")
 */
class LineaController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function tracking($entity)
    {
        $uow = $this->em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeSet = $uow->getEntityChangeSet($entity);

        if(empty($changeSet)){
            return;
        }

        if(isset($changeSet['usuario'])){
            $usuario = new LogUsuario();
            $usuario->setLinea($entity);
            $usuario->setUsuario($changeSet['usuario'][1]);

            $this->em->persist($usuario);
        }

        if(isset($changeSet['planVoz'])){
            $planVoz = new LogPlanVoz();
            $planVoz->setPlan($changeSet['planVoz'][1]);
            $planVoz->setLinea($entity);

            $this->em->persist($planVoz);
        }

        if(isset($changeSet['planDatos'])){
            $planDatos = new LogPlanDatos();
            $planDatos->setPlan($changeSet['planDatos'][1]);
            $planDatos->setLinea($entity);

            $this->em->persist($planDatos);
        }

        if(isset($changeSet['pin']) || isset($changeSet['puk'])){
            $sim = new LogSim();

            if(isset($changeSet['pin'])){
                $sim->setPin($changeSet['pin'][1]);
            }

            if(isset($changeSet['puk'])){
                $sim->setPuk($changeSet['puk'][1]);
            }

            $sim->setLinea($entity);

            $this->em->persist($sim);
        }
    }

    /**
     * @Route("/{estado}",
     *      name="app_tic_linea_index",
     *      requirements={"estado": "activas|bajas"},
     *      methods={"GET"}
     * )
     */
    public function index(Request $request, string $estado, LineaRepository $lineas): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $breadcrumb = [
            ['title' => 'Líneas']
        ];

        return $this->render('tic/linea.html.twig',[
            'total' => $lineas->findTotalesByEstado($unidad),
            'estado' => $estado,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{estado}/list",
     *      name="app_tic_linea_list",
     *      requirements={"estado": "activas|bajas"},
     *      methods={"GET"}
     * )
     */
    public function list(Request $request, LineaRepository $lineas, string $estado): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $lineas = $lineas->findLineasByEstado($estado, $unidad);

        return new JsonResponse($lineas);
    }

    /**
     * Creates a new linea entity.
     *
     * @Route("/new",
     *      name="app_tic_linea_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $linea = new Linea();
        $form = $this->createForm(LineaType::class, $linea);
        $breadcrumb = [
            ['title' => 'Lineas', 'url' => $this->generateUrl('app_tic_linea_index', ['estado' => 'activas'])],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($linea);

            $this->tracking($linea);

            $this->em->flush();

            $this->addFlash('notice', 'Linea registrada con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_tic_linea_new');
            }

            return $this->redirectToRoute('app_tic_linea_index', ['estado' => 'activas']);
        }

        return $this->render('tic/form/linea_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing contrato entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_tic_linea_edit",
     *      methods={"GET", "POST"})
     * @Entity("linea", expr="repository.findOneLineaById(id, 'activa')")
     */
    public function edit(Request $request, Linea $linea)
    {
        $breadcrumb = [
            ['title' => 'Líneas', 'url' => $this->generateUrl('app_tic_linea_index', ['estado' => $linea->getIsBaja() ? 'bajas' : 'activas'])],
            ['title' => 'Modificar']
        ];

        $form = $this->createForm(LineaType::class, $linea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->tracking($linea);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Línea modificada con exito!');

            return $this->redirectToRoute('app_tic_linea_index', [
                'estado' => !$linea->getIsBaja() ? 'activas' : 'bajas'
            ]);
        }

        return $this->render('tic/form/linea_form.html.twig', [
            'linea' => $linea,
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a linea entity.
     *
     * @Route("/{id<[1-9]\d*>}/show",
     *      name="app_tic_linea_show",
     *      methods={"GET"}
     * )
     * @Entity("linea", expr="repository.findOneLineaById(id, 'detalle')")
     */
    public function show(Linea $linea): Response
    {
        $breadcrumb = [
            ['title' => 'Líneas', 'url' => $this->generateUrl('app_tic_linea_index', ['estado' => $linea->getIsBaja() ? 'bajas' : 'activas'])],
            ['title' => 'Detalle']
        ];

        return $this->render('tic/form/linea_show.html.twig', [
            'linea' => $linea,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Baja an existing usuario entity at other unidad.
     *
     * @Route("/{id<[1-9]\d*>}/unsubscribe",
     *      name="app_tic_linea_unsubscribe",
     *      methods={"GET","POST"})
     */
    public function unsubscribe(Request $request, Linea $linea): Response
    {
        $breadcrumb = [
            ['title' => 'Líneas', 'url' => $this->generateUrl('app_tic_linea_index', ['estado' => $linea->getIsBaja() ? 'bajas' : 'activas'])],
            ['title' => 'Baja']
        ];

        $form = $this->createForm(LineaBajaType::class, $linea);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $linea->setIsBaja(true);
            $linea->setIsReserva(false);
            $linea->setUsuario(\null);

            $this->tracking($linea);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Línea dada de baja con exito!');

            return $this->redirectToRoute('app_tic_linea_index', ['estado' => 'activas']);
        }

        return $this->render('tic/form/linea_baja_form.html.twig', [
            'linea' => $linea,
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Finds and displays a log linea entity.
     *
     * @Route("/{id<[1-9]\d*>}/log",
     *      name="app_tic_linea_log",
     *      methods={"GET"}
     * )
     */
    public function log(Linea $linea, int $id): Response
    {
        $breadcrumb = [
            ['title' => 'Líneas', 'url' => $this->generateUrl('app_tic_linea_index', ['estado' => $linea->getIsBaja() ? 'bajas' : 'activas'])],
            ['title' => 'Log']
        ];

        return $this->render('tic/form/linea_log.html.twig', [
            'linea' => $linea,
            'usuarios' => $this->em->getRepository(LogUsuario::class)->findByLineaId($id),
            'planesVoz' => $this->em->getRepository(LogPlanVoz::class)->findByLineaId($id),
            'planesDatos' => $this->em->getRepository(LogPlanDatos::class)->findByLineaId($id),
            'sims' => $this->em->getRepository(LogSim::class)->findByLineaId($id),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/extraplan",
     *      name="app_tic_linea_plan_extra",
     *      methods={"GET", "POST"}
     * )
     */
    public function extraPlan(Request $request, Linea $linea, PlanExtraRepository $planesExtra): Response
    {
        $breadcrumb = [
            ['title' => 'Líneas', 'url' => $this->generateUrl('app_tic_linea_index', ['estado' => $linea->getIsBaja() ? 'bajas' : 'activas'])],
            ['title' => 'Planes extra']
        ];
        $plan = new PlanExtra();
        $form = $this->createForm(PlanExtraType::class, $plan);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plan->setLinea($linea);
            $plan->setUsuario($linea->getUsuario());

            $this->em->persist($plan);
            $this->em->flush();

            $this->addFlash('notice', 'Plan extra registrado con exito!');

            return $this->redirectToRoute('app_tic_linea_plan_extra', ['id' => $linea->getId()]);
        }

        return $this->render('tic/form/linea_plan_extra.html.twig',[
            'breadcrumb' => $breadcrumb,
            'linea' => $linea,
            'form' => $form->createView(),
            'total' => $planesExtra->findTotalByIdLinea($linea->getId()),
            'planesExtra' => $planesExtra->findByIdLinea($linea->getId()),
        ]);
    }

    /**
     * @Route("/reporte/totales", name="rep_tic_linea_totales")
     */
    public function reportTotales(Request $request, LineaRepository $lineas, Pdf $pdf): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Líneas',
            'titulo' => 'Total de Líneas'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('tic/report/linea_report_totales.pdf.html.twig', [
            'lineas' => $lineas->findTotalesGroupByUnidad($unidad)
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html,[
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'lineas_totales.pdf'
        );
    }

    /**
     * @Route("/reporte/planes-voz", name="rep_tic_linea_planes_voz")
     */
    public function reportPlanesVoz(Request $request, LineaRepository $lineas, Pdf $pdf): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Líneas',
            'titulo' => 'Total planes de voz + Costo'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('tic/report/linea_report_planes_voz.pdf.html.twig', [
            'planes' => $lineas->findTotalesByPlanGroupByUnidad($unidad, 'voz')
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html,[
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'lineas_planes_voz.pdf'
        );

    }


    /**
     * @Route("/reporte/planes-datos", name="rep_tic_linea_planes_datos")
     */
    public function reportPlanesDatos(Request $request, LineaRepository $lineas, Pdf $pdf): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Líneas',
            'titulo' => 'Total planes de datos + Costo'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('tic/report/linea_report_planes_datos.pdf.html.twig', [
            'planes' => $lineas->findTotalesByPlanGroupByUnidad($unidad, 'datos')
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html,[
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'lineas_planes_datos.pdf'
        );

    }
}
