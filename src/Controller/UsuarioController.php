<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Usuario;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="usuario")
     */
    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }

    /**
     * @Route("/usuario/nuevo", name="nuevo_usuario")
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido a administradores');
        $usuario = new Usuario();
        $formulario = $this->createFormBuilder($usuario)
                      ->add('login', TextType::class, array('label' => 'Nombre de usuario'))
                      ->add('password', PasswordType::class, array('label' => 'Password'))
                      ->add('email', EmailType::class, array('label' => 'Email'))
                      ->add('rol', TextType::class, array('label' => 'Rol'))
                      ->add('save', SubmitType::class, array('label' => 'Registrarse'))
                      ->getForm();
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $plainPass = $formulario['password']->getData();
            $encriptedPass = $encoder->encodePassword($usuario, $plainPass);
            $usuario->setPassword($encriptedPass);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            try {
                $entityManager->flush();
                return $this->redirectToRoute('listar_libros');
            } catch(Exception $e) {
                return new Response('herror al insertar el usuario');
            }         
        }
        return $this->render('nuevo_usuario.html.twig',
                      array('formulario' => $formulario->createView()))             ;
    }
}
