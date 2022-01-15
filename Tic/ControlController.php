<?php

namespace App\Controller\Tic;

use App\Entity\Tic\Control;
use App\Entity\Tic\Celular\Celular;
use App\Entity\Tic\Nomenclador\TipoEquipo;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Tic\ControlRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Control controller.
 *
 * @Route("tic/control/{medio}",
 *      requirements={"medio": "celulares"}
 * )
 */
class ControlController extends AbstractController
{
    private $em;
    private const ENTITY = [
        'celulares' => Celular::class
    ];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function whiteList(string $url): void
    {
        $whitelist = [
            '/tic/celulares/activos',
        ];

        if(!\in_array($url, $whitelist)){
            throw new \InvalidArgumentException('Error de url de retorno');
        }
    }

    /**
     * @Route("/",
     *      name="app_tic_control_index",
     *      methods={"GET"}
     * )
     */
    public function index(Request $request, string $medio): Response
    {
        $redirectTo = $request->query->get('redirect_to');

        $this->whiteList($redirectTo);

        $breadcrumb = [
            ['title' => \ucfirst($medio), 'url' => $redirectTo],
            ['title' => 'Control'],
        ];

        return $this->render('tic/control.html.twig',[
            'medio' => $medio,
            'breadcrumb' => $breadcrumb,
            'redirect_to' => $redirectTo
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_tic_control_list",
     *      methods={"GET"}
     * )
     */
    public function list(Request $request, ControlRepository $controles, string $medio): Response
    {
        $tipos = $this->getParameter('app_tic_unpluralize');
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');

        return new JsonResponse($controles->findControlByUnidadAndMedio($unidad, \ucfirst($tipos[0][$medio])));
    }

    /**
     * @Route("/new",
     *      name="app_tic_control_new",
     *      methods={"GET"}
     * )
     */
    public function new(Request $request, string $medio)
    {
        $redirectTo = $request->query->get('redirect_to');
        $this->whiteList($redirectTo);

        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? $this->getParameter('app_tic_control_multiple') : [$request->getSession()->get('_unidad')];
        $controlados = $this->em->getRepository(self::ENTITY[$medio])->findByFechaControl(5, $unidad, true);
        $tipos = $this->getParameter('app_tic_unpluralize');

        if(!empty($controlados)) {
            $this->em->getRepository(self::ENTITY[$medio])->updateFechaControl(5, $unidad, true);

            $tipo = $this->em->getRepository(TipoEquipo::class)->findOneBy(['tipo' => \ucfirst($tipos[0][$medio])]);

            $control = new Control();
            $control->setUsuario($this->getUser());
            $control->setMedio($tipo);
            $control->setInventarios(array_column($controlados, 'inventario'));

            $this->em->persist($control);
            $this->em->flush();

            $this->addFlash('notice', 'Control de medios generado con exito.');
        } else {
            $this->addFlash('error', 'Lo sentimos no existen medios suficientes para controlar.');
        }

        return $this->redirectToRoute('app_tic_control_index', [
            'medio' => $medio,
            'redirect_to' => $redirectTo
        ]);
    }

    /**
     * Finds and displays a control entity.
     *
     * @Route("/{id<[1-9]\d*>}/show",
     *      name="app_tic_control_show",
     *      methods={"GET"}
     * )
     * @Entity("control", expr="repository.findOneControlById(id)")
     */
    public function show(Request $request, Control $control, string $medio): Response
    {
        $redirectTo = $request->query->get('redirect_to');

        $this->whiteList($redirectTo);

        $breadcrumb = [
            ['title' => \ucfirst($medio), 'url' => $redirectTo],
            ['title' => 'Control'],
        ];

        return $this->render('tic/form/control_show.html.twig', [
            'control' => $control,
            'controlados' => $this->em->getRepository(self::ENTITY[$medio])->findByInventario($control->getInventarios()),
            'medio' => $medio,
            'breadcrumb' => $breadcrumb,
            'redirect_to' => $redirectTo
        ]);
    }
}
