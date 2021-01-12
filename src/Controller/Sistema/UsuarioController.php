<?php

namespace App\Controller\Sistema;

use phpseclib\Net\SSH2;
use App\Entity\Sistema\Usuario;
use App\Form\Sistema\UsuarioType;
use Symfony\Component\Process\Process;
use App\Repository\Sistema\UsuarioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Usuario controller.
 *
 * @Route("sistema/usuarios")
 */
class UsuarioController extends AbstractController
{
    /**
     * @Route("/{estado}/",
     *      name="app_usuario_index",
     *      defaults={"estado": "activos"},
     *      requirements={"estado": "activos|bajas"},
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
     *      defaults={"estado": "activos"},
     *      requirements={"estado": "activos|bajas"},
     *      methods={"GET"}
     * )
     */
    public function list(Session $session, UsuarioRepository $usuarios, $estado): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $session->get('_unidad');

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
     */
    public function new(Request $request, Session $session, $unidad, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $options = [
            'unidad' => $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $session->get('_unidad'),
        ];

        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario, $options);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($form->get('hasAccount')->getData()){
                $plainPassword = $form->get('plainPassword')->getData();
                $usuario->setPassword($passwordEncoder->encodePassword($usuario, $plainPassword));
                $usuario->setCheckPassword(\hash('sha256', $usuario->getId().$plainPassword));

                // Sincronización clave con dominio
                if($this->getParameter('app_pass_sync') === 'domain'){
                    $usuario->setIsSyncPassword($this->syncPasswordDomain($usuario->getUsername(), $plainPassword));
                }
            }

            $this->addFlash('notice', 'Usuario registrado con exito!');

            $em->persist($usuario);
            $em->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/usuario/modal/usuario_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'action' => 'create'
        ]);

        return $this->render('common/notify.html.twig', []);
    }



    /**
     * Formulario para reiniciar password
     */
    private function formPasswordReset(){
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('app_usuario_password_reset'))
            ->add('id', HiddenType::class,[
                'required' => true
            ])
            ->getForm();
    }

    /**
     * Sincronización de clave con dominio
     */
    private function syncPasswordDomain($usuario, $clave){

        if($this->isHostAlive($this->getParameter('app_ssh2_host'))){
            $ssh = new SSH2($this->getParameter('app_ssh2_host'));
            $cmd = "echo %s | sudo /usr/bin/samba-tool user setpassword %s --newpassword='%s'";

            $ssh->login($this->getParameter('app_ssh2_user'), $this->getParameter('app_ssh2_pass'));
            $ssh->exec(sprintf($cmd, $this->getParameter('app_ssh2_pass'), $usuario, $clave));

            return $ssh->getExitStatus() !== 0 ? false : true;
        }

        return false;
    }

    /**
     *  Commprobar si el host esta activo
     */
    private function isHostAlive ($ip){
        $exec = 'ping -c 1 -W 1 '.$ip.' >/dev/null';

        $process = new Process($exec);
        $process->run();

        return $process->isSuccessful();
    }
}
