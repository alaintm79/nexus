<?php

namespace App\Controller\Sistema;

use App\Entity\Sistema\Usuario;
use App\Form\Sistema\UsuarioBajaType;
use App\Form\Sistema\UsuarioPasswordType;
use App\Form\Sistema\UsuarioType;
use App\Repository\Sistema\UsuarioRepository;
use App\Util\UsuarioUtil;
use phpseclib\Net\SSH2;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
 */
class UsuarioController extends AbstractController
{
    /**
     * @Route("/sistema/usuarios/{estado}",
     *      name="app_usuario_index",
     *      defaults={"estado": "registrados"},
     *      requirements={"estado": "registrados|bajas"},
     *      methods={"GET"}
     * )
     */
    public function index(Request $request, UsuarioRepository $usuario, $estado): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');

        // $usuarios = $usuario->findBy(['token' => null], [], 50);

        // dump(count($usuarios));

        // foreach($usuarios as $usuario){
        //     $usuario->setToken(UsuarioUtil::token());
        //     $this->getDoctrine()->getManager()->persist($usuario);
        // }

        // $this->getDoctrine()->getManager()->flush();

        // exit();

        $breadcrumb = [
            ['title' => 'Usuarios']
        ];

        return $this->render('sistema/usuario.html.twig', [
            'estado' => $estado,
            'total' => $usuario->findTotalesByEstado($unidad),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/sistema//usuarios/list/{estado}",
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
     * @Route("/sistema//usuarios/new",
     *      name="app_usuario_new",
     *      defaults={"unidad": null},
     *      methods={"GET", "POST"}
     * )
     * @Security("is_granted('ROLE_NX_ADMIN')")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad'),
            'action' => 'new'
        ];
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario, $options);
        $breadcrumb = [
            ['title' => 'Usuarios', 'url' => $this->generateUrl('app_usuario_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($form->get('hasAccount')->getData()){
                $plainPassword = $form->get('plainPassword')->getData();
                $usuario->setIsActive(true);

                $this->userPassword($usuario, $plainPassword, $passwordEncoder);
            }

            $em->persist($usuario);
            $em->flush();

            $this->addFlash('notice', 'Usuario registrado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_usuario_new');
            }

            return $this->redirectToRoute('app_usuario_index');
        }

        return $this->render('sistema/form/usuario_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing usuario entity.
     *
     * @Route("/sistema/usuarios/{id}/edit",
     *      name="app_usuario_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Usuario $usuario, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad'),
            'action' => 'edit'
        ];
        $breadcrumb = [
            ['title' => 'Usuarios', 'url' => $usuario->getIsBaja() ? $this->generateUrl('app_usuario_index', ['estado' => 'bajas']) : $this->generateUrl('app_usuario_index')],
            ['title' => 'Modificar']
        ];

        $form = $this->createForm(UsuarioType::class, $usuario, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();

            if($form->get('hasAccount')->getData() && null !== $plainPassword){
                $this->userPassword($usuario, $plainPassword, $passwordEncoder);
            }

            $this->addFlash('notice', 'Usuario modificado con exito!');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_usuario_index', ['estado' => $usuario->getIsBaja() ? 'bajas' : 'registrados']);
        }

        return $this->render('sistema/form/usuario_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Reset password an existing usuario entity at other unidad.
     *
     * @Route("/sistema/usuarios/{id}/status",
     *      name="app_usuario_status",
     *      methods={"GET"})
     */
    public function status(Usuario $usuario): Response
    {
        $status = $usuario->getIsActive() ? false : true;

        $usuario->setIsActive($status);

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('notice', 'Cambio de estado realizado con exito!');

        return $this->redirectToRoute('app_usuario_index');
    }

    /**
     * Baja an existing usuario entity at other unidad.
     *
     * @Route("/sistema/usuarios/{id}/leave",
     *      name="app_usuario_leave",
     *      methods={"GET","POST"})
     */
    public function leave(Request $request, Usuario $usuario): Response
    {
        $form = $this->createForm(UsuarioBajaType::class, $usuario);
        $breadcrumb = [
            ['title' => 'Usuarios', 'url' => $this->generateUrl('app_usuario_index')],
            ['title' => 'Baja']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usuario->setIsBaja(true);
            $usuario->setIsActive(false);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Usuario dado de baja con exito!');

            return $this->redirectToRoute('app_usuario_index');
        }

        return $this->render('sistema/form/usuario_baja_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Alta an existing usuario entity at other unidad.
     *
     * @Route("/sistema/usuarios/{id}/reincorporar",
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

        return $this->redirectToRoute('app_usuario_index');
    }

    /**
     * Change password an existing usuario entity at other unidad.
     *
     * @Route("/sistema/usuarios/{token}/update",
     *      name="app_usuario_password",
     *      methods={"GET","POST"})
     * @Route("/usuario/{token}/update",
     *      name="app_usuario_mi_clave",
     *      methods={"GET","POST"})
     * @Entity("usuario", expr="repository.findByToken(token)")
     */
    public function passwordChange(Request $request, Usuario $usuario, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UsuarioPasswordType::class, $usuario);

        if ($request->get('_route') === 'app_usuario_mi_clave') {
            $breadcrumb = [
                ['title' => 'Mi clave']
            ];
        } else {
            $breadcrumb = [
                ['title' => 'Usuarios', 'url' => $this->generateUrl('app_usuario_index')],
                ['title' => 'Modificar Clave']
            ];
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userPassword($usuario, $form->get('plainPassword')->getData(), $passwordEncoder);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Clave modificada con exito!');

            $routeTo = $request->get('_route') === 'app_usuario_mi_clave' ? 'app_home' : 'app_usuario_index';

            return $this->redirectToRoute($routeTo);
        }

        return $this->render('sistema/form/usuario_change_password_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     *  Establecer clave del usuario
     */
    private function userPassword($usuario, $password, $encoder)
    {
        $usuario->setPassword($encoder->encodePassword($usuario, $password));

        // Sincronización clave con dominio
        if($this->getParameter('app_pass_sync') === 'domain'){
            $usuario->setIsSyncPassword($this->syncPasswordDomain($usuario->getUsername(), $password));
        }
    }

    /**
     * Sincronización de clave con dominio
     */
    private function syncPasswordDomain($usuario, $clave): ?bool
    {
        if($this->isHostAlive($this->getParameter('app_ssh2_host'))){
            $ssh = new SSH2($this->getParameter('app_ssh2_host'));
            $cmd = "echo %s | sudo -S /usr/bin/samba-tool user setpassword %s --newpassword='%s'";

            $ssh->login($this->getParameter('app_ssh2_user'), $this->getParameter('app_ssh2_pass'));
            $ssh->exec(sprintf($cmd, $this->getParameter('app_ssh2_pass'), $usuario, $clave));

            return $ssh->getExitStatus() !== 0 ? false : true;
        }

        return false;
    }

    /**
     *  Comprobar si el host esta activo
     */
    private function isHostAlive ($ip): ?bool
    {
        $exec = 'ping -c 1 -W 1 '.$ip.' >/dev/null';

        $process = new Process($exec);
        $process->run();

        return $process->isSuccessful();
    }
}
