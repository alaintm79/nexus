<?php

namespace App\Controller\Sistema;

use App\Controller\Traits\PasswordTrait;
use App\Controller\Traits\SexoEdadTrait;
use App\Entity\Sistema\Usuario;
use App\Form\Sistema\UsuarioBajaType;
use App\Form\Sistema\UsuarioPasswordType;
use App\Form\Sistema\UsuarioType;
use App\Repository\Sistema\UsuarioRepository;
use App\Util\UsuarioUtil;
use phpseclib\Net\SSH2;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Usuario controller.
 *
 * @Route("sistema/usuarios")
 */
class UsuarioController extends AbstractController
{
    use PasswordTrait, SexoEdadTrait;

    /**
     * @Route("/{estado}",
     *      name="app_usuario_index",
     *      defaults={"estado": "registrados"},
     *      requirements={"estado": "registrados|bajas"},
     *      methods={"GET"}
     * )
     */
    public function index($estado): Response
    {
        return $this->render('sistema/usuario/index.html.twig', [
            'estado' => $estado,
        ]);
    }

    /**
     * @Route("/list/{estado}",
     *      name="app_usuario_list",
     *      defaults={"estado": "registrados"},
     *      requirements={"estado": "registrados|bajas"},
     *      methods={"GET"}
     * )
     */
    public function list(Request $request, UsuarioRepository $usuarios, $estado): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');

        return new JsonResponse($usuarios->findAll($unidad, $estado));
    }

    /**
     * Creates a new usuario entity.
     *
     * @Route("/new",
     *      name="app_usuario_new",
     *      defaults={"unidad": null},
     *      methods={"GET", "POST"}
     * )
     * @Security("is_granted(['ROLE_NX_ADMIN'])")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad'),
            'action' => 'new'
        ];

        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario, $options);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($form->get('hasAccount')->getData()){
                $plainPassword = $form->get('plainPassword')->getData();

                $this->setSexoAndEdad($usuario);
                $this->userPassword($usuario, $plainPassword, $passwordEncoder);
            }

            $em->persist($usuario);
            $em->flush();

            $this->addFlash('notice', 'Usuario registrado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/usuario/modal/usuario_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'action' => 'new'
        ]);
    }

    /**
     * Displays a form to edit an existing usuario entity.
     *
     * @Route("/{id}/edit",
     *      name="app_usuario_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Usuario $usuario, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad'),
            'action' => 'edit'
        ];

        $form = $this->createForm(UsuarioType::class, $usuario, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();

            $this->setSexoAndEdad($usuario);

            if($form->get('hasAccount')->getData() && null !== $plainPassword){
                $this->userPassword($usuario, $plainPassword, $passwordEncoder);
            }

            $this->addFlash('notice', 'Usuario modificado con exito!');

            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/usuario/modal/usuario_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'action' => 'edit'
        ]);
    }

    /**
     * Reset password an existing usuario entity at other unidad.
     *
     * @Route("/{id}/status",
     *      name="app_usuario_status",
     *      methods={"GET"})
     */
    public function status(Usuario $usuario): Response
    {
        $status = $usuario->getIsActive() ? false : true;

        $usuario->setIsActive($status);

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('notice', 'Cambio de estado realizado con exito!');

        return $this->render('common/notify.html.twig', []);
    }

    /**
     * Baja an existing usuario entity at other unidad.
     *
     * @Route("/{id}/leave",
     *      name="app_usuario_leave",
     *      methods={"GET","POST"})
     */
    public function leave(Request $request, Usuario $usuario): Response
    {
        $form = $this->createForm(UsuarioBajaType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usuario->setIsBaja(true);
            $usuario->setIsActive(false);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Usuario dado de baja con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/usuario/modal/baja_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Alta an existing usuario entity at other unidad.
     *
     * @Route("/{id}/reincorporar",
     *      name="app_usuario_reincorporar",
     *      methods={"GET"})
     */
    public function reincorporar(Usuario $usuario): Response
    {
        $usuario->setIsActive(true);
        $usuario->setFechaBaja(null);
        $usuario->setIsBaja(false);

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('notice', 'Usuario reincorporado con exito!');

        return $this->render('common/notify.html.twig', []);
    }

    /**
     * Change password an existing usuario entity at other unidad.
     *
     * @Route("/{id}/password",
     *      name="app_usuario_password",
     *      methods={"GET","POST"})
     */
    public function changePassword(Request $request, Usuario $usuario, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UsuarioPasswordType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userPassword($usuario, $form->get('plainPassword')->getData(), $passwordEncoder);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Clave modificada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/usuario/modal/password_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Reporte de usuarios
     */
    public function reporteDashboard(Request $request, UsuarioRepository $usuarios): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');

        return $this->render('sistema/usuario/_dashboard.html.twig', [
            'usuarios' => $usuarios->findReporteTotalUsuarios($unidad),
        ]);
    }
}
