<?php

namespace App\Controller\Tic;

use Knp\Snappy\Pdf;
use App\Entity\Tic\Control;
use App\Entity\Tic\Celular\Celular;
use App\Entity\Tic\Celular\LogEstado;
use App\Form\Tic\Celular\CelularType;
use App\Entity\Tic\Celular\LogUsuario;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Tic\Celular\CelularBajaType;
use App\Repository\Tic\ControlRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Tic\Celular\CelularRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Celular controller.
 *
 * @Route("tic/celulares")
 */
class CelularController extends AbstractController
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
            $usuario->setCelular($entity);
            $usuario->setUsuario($changeSet['usuario'][1]);

            $this->em->persist($usuario);
        }

        if(isset($changeSet['estado'])){
            $estado = new LogEstado();
            $estado->setCelular($entity);
            $estado->setEstado($changeSet['estado'][1]);

            $this->em->persist($estado);
        }
    }

    /**
     * @Route("/{estado}",
     *      name="app_tic_celular_index",
     *      requirements={"estado": "activos|bajas"},
     *      methods={"GET"}
     * )
     */
    public function index(Request $request, string $estado, CelularRepository $celulares): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $breadcrumb = [
            ['title' => 'Celulares']
        ];

        return $this->render('tic/celular.html.twig',[
            'total' => $celulares->findTotalesByEstado($unidad),
            'estado' => $estado,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{estado}/list",
     *      name="app_tic_celular_list",
     *      requirements={"estado": "activos|bajas"},
     *      methods={"GET"}
     * )
     */
    public function list(Request $request, CelularRepository $celulares, string $estado): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $celulares = $celulares->findCelularesByEstado($estado, $unidad);

        return new JsonResponse($celulares);
    }

    /**
     * Creates a new celular entity.
     *
     * @Route("/new",
     *      name="app_tic_celular_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad'),
        ];
        $celular = new Celular();
        $form = $this->createForm(CelularType::class, $celular, $options);
        $breadcrumb = [
            ['title' => 'Celulares', 'url' => $this->generateUrl('app_tic_celular_index', ['estado' => 'activos'])],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($celular);

            $this->tracking($celular);

            $this->em->flush();

            $this->addFlash('notice', 'Celular registrado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_tic_celular_new');
            }

            return $this->redirectToRoute('app_tic_celular_index', ['estado' => 'activos']);
        }

        return $this->render('tic/form/celular_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing contrato entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_tic_celular_edit",
     *      methods={"GET", "POST"})
     * @Entity("celular", expr="repository.findOneCelularById(id, 'activo')")
     */
    public function edit(Request $request, Celular $celular)
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad'),
        ];
        $breadcrumb = [
            ['title' => 'Celulares', 'url' => $this->generateUrl('app_tic_celular_index', ['estado' => $celular->getEstado()->getEstado() != 'Baja' ? 'activos' : 'bajas'])],
            ['title' => 'Modificar']
        ];

        $form = $this->createForm(CelularType::class, $celular, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->tracking($celular);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Celular modificado con exito!');

            return $this->redirectToRoute('app_tic_celular_index', [
                'estado' => $celular->getEstado()->getEstado() != 'Baja' ? 'activos' : 'bajas'
            ]);
        }

        return $this->render('tic/form/celular_form.html.twig', [
            'celular' => $celular,
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a celular entity.
     *
     * @Route("/{id<[1-9]\d*>}/show",
     *      name="app_tic_celular_show",
     *      methods={"GET"}
     * )
     * @Entity("celular", expr="repository.findOneCelularById(id, 'show')")
     */
    public function show(Celular $celular): Response
    {
        $breadcrumb = [
            ['title' => 'Celulares', 'url' => $this->generateUrl('app_tic_celular_index', ['estado' => $celular->getEstado()->getEstado() != 'Baja' ? 'activos' : 'bajas'])],
            ['title' => 'Detalle']
        ];

        return $this->render('tic/form/celular_show.html.twig', [
            'celular' => $celular,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Finds and displays a log celular entity.
     *
     * @Route("/{id<[1-9]\d*>}/log",
     *      name="app_tic_celular_log",
     *      methods={"GET"}
     * )
     */
    public function log(Celular $celular, int $id): Response
    {
        $breadcrumb = [
            ['title' => 'Celulares', 'url' => $this->generateUrl('app_tic_celular_index', ['estado' => $celular->getEstado()->getEstado() != 'Baja' ? 'activos' : 'bajas'])],
            ['title' => 'Log']
        ];

        return $this->render('tic/form/celular_log.html.twig', [
            'celular' => $celular,
            'usuarios' => $this->em->getRepository(LogUsuario::class)->findByCelularId($id),
            'estados' => $this->em->getRepository(LogEstado::class)->findByCelularId($id),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Baja an existing usuario entity at other unidad.
     *
     * @Route("/{id<[1-9]\d*>}/unsubscribe",
     *      name="app_tic_celular_unsubscribe",
     *      methods={"GET","POST"})
     */
    public function unsubscribe(Request $request, Celular $celular): Response
    {
        $breadcrumb = [
            ['title' => 'Celulares', 'url' => $this->generateUrl('app_tic_celular_index', ['estado' => $celular->getEstado()->getEstado() != 'Baja' ? 'activos' : 'bajas'])],
            ['title' => 'Baja']
        ];

        $form = $this->createForm(CelularBajaType::class, $celular);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $celular->setEstado($this->em->getReference('App:Tic\Nomenclador\Estado', 5));
            $celular->setObservacion('ORIGEN: '.$celular->getUsuario()->getUnidad().'. USUARIO: '. $celular->getUsuario());
            $celular->setUsuario(\null);
            $celular->setLinea(\null);

            $this->tracking($celular);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Celular dado de baja con exito!');

            return $this->redirectToRoute('app_tic_celular_index', ['estado' => 'activos']);
        }

        return $this->render('tic/form/celular_baja_form.html.twig', [
            'celular' => $celular,
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/reporte/totales", name="rep_tic_celular_totales")
     */
    public function reportTotales(Request $request, CelularRepository $celulares, Pdf $pdf): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');
        $header = $this->renderView('reports/header.html.twig', [
            'modulo' => 'TIC / Celulares',
            'titulo' => 'Total de Celulares'
        ]);
        $footer = $this->renderView('reports/footer.html.twig');
        $html = $this->renderView('tic/report/celular_report_totales.pdf.html.twig', [
            'celulares' => $celulares->findTotalesGroupByUnidad($unidad)
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html,[
                'header-html' => $header,
                'footer-html' => $footer,
            ]),
            'celulares_totales.pdf'
        );
    }
}
