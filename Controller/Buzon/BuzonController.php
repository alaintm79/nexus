<?php

namespace App\Controller\Buzon;

use App\Service\Notify;
use App\Entity\Buzon\Mensaje;
use App\Form\Buzon\MensajeType;
use App\Repository\Buzon\MensajeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Buzon controller.
 * @Route("buzon")
 */
class BuzonController extends AbstractController
{
    private const EMAIL_TEMPLATE = 'buzon/notify.html.twig';

    /**
     * @Route("/",
     *      name="app_buzon_index",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Buzón']
        ];

        return $this->render('buzon/admin/mensaje.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_buzon_list",
     *      methods={"GET"}
     * )
     */
    public function list(Request $request, MensajeRepository $mensajes): Response
    {
        $params = $request->query->all();
        $total = $mensajes->findTotalMensajes($params);
        $mensajes = $mensajes->findMensajes($params);

        $result = [
            'total' => empty($mensajes) ? 0 : $total,
            'rows' => $mensajes
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/new", name="app_buzon_mensaje_new")
     */
    public function new(Request $request, Notify $notify): Response
    {
        $mensaje = new Mensaje();
        $form = $this->createForm(MensajeType::class, $mensaje);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $mensaje->setUsuario($this->getUser());

            $em->persist($mensaje);
            $em->flush();

            $this->addFlash('blog_notice', 'Mensaje registrado con exito!');

            $notify->send(
                $this->getParameter('app_notify_buzon'),
                $mensaje,
                self::EMAIL_TEMPLATE,
                'Nuevo mensaje enviado'
            );

            return $this->redirectToRoute('app_buzon_mensaje_new', []);
        }

        return $this->render('buzon/mensaje.html.twig', [
            'buzon' => $mensaje,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mensaje/{id<[1-9]\d*>}/show",
     *      name="app_buzon_mensaje_show",
     *      methods={"GET"}
     * )
     * @Entity("buzon", expr="repository.findById(id)")
     */
    public function show(Mensaje $mensaje): Response
    {
        $breadcrumb = [
            ['title' => 'Buzón', 'url' => $this->generateUrl('app_buzon_index')],
            ['title' => 'Mensaje']
        ];

        return $this->render('buzon/admin/form/mensaje_show.html.twig',[
            'mensaje' => $mensaje,
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
