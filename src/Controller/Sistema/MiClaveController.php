<?php

namespace App\Controller\Sistema;

use App\Controller\Traits\PasswordTrait;
use App\Form\Sistema\UsuarioPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Usuario controller.
 *
 * @Route("sistema/usuarios")
 */
class MiClaveController extends AbstractController
{
    use PasswordTrait;
    /**
     * Change password an existing usuario entity.
     *
     * @Route("/mi-clave",
     *      name="app_usuario_change_password",
     *      methods={"GET","POST"})
     */
    public function miClave(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $usuario = $this->getUser();
        $form = $this->createForm(UsuarioPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userPassword($usuario, $form->get('plainPassword')->getData(), $passwordEncoder);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Su clave a sido modificada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/usuario/modal/password_form.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }
}
