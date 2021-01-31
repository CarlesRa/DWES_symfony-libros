<?php  

namespace App\Controller;

use App\Entity\Libro;
use App\Entity\Editorial;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LibroType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


    class LibroController extends AbstractController{

        private $libros = array(
            array("isbn" => "A001", "titulo" => "Jarry Choped", "autor" => "JK Bowling", "paginas" => 100),
            array("isbn" => "A002", "titulo" => "El señor de los palillos", "autor" => "JRR TolQuien", "paginas" => 200),
            array("isbn" => "A003", "titulo" => "Los polares de la tierra", "autor" => "Ken Follonett", "paginas" => 300),
            array("isbn" => "A004", "titulo" => "Los juegos del enjambre", "autor" => "Suzanne Collonins", "paginas" => 400)
        );

        /**
        * @Route("/libros", name="listar_libros")
        */  
        public function loadLibros() {
            $repository = $this->getDoctrine()->getRepository(Libro::class);
            $libros = $repository->findAll();

            return $this->render('ficha_libros.html.twig', array('libros' => $libros));
        }

        /**
        * @Route("/nuevo", name="nuevo")
        */
        public function crear(Request $request) {
            $libro = new Libro();
            $formulario = $this->createForm(LibroType::class, $libro);
            $formulario->handleRequest($request);
            if ($formulario->isSubmitted() && $formulario->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($libro);
                try {
                    $entityManager->flush();
                    return $this->redirectToRoute('listar_libros');
                }catch (Exception $e) {
                    return new Response('herror al insertar el libro');
                }
            }
            return $this->render('nuevo_libro.html.twig',
                                 array('formulario' => $formulario->createView()));
        }

        /**
        * @Route("/buscar", name="buscar")
        */
        public function buscar(Request $request) {
            $libros = null;
            $formulario = $this->createFormBuilder()
                          ->add('filtro', TextType::class)
                          ->add('save', SubmitType::class, array('label' => 'Buscar'))
                          ->getForm();
            $formulario->handleRequest($request);
            if ($formulario->isSubmitted() && $formulario->isValid()) {
                /**
                * @var (LibroRepository)
                */
                $repository = $this->getDoctrine()->getRepository(Libro::class);
                $filtro = $formulario->getData()['filtro'];
                $libros = $repository->buscarLibros($filtro);
            }
            return $this->render('buscar_libros.html.twig', 
                   array('formulario' => $formulario->createView(), 'libros' => $libros));
        }

        /**
        * @Route("/editar/{isbn}", name="editar")
        */
        public function editar(Request $request, $isbn) {
            $libro = $this->getDoctrine()
                          ->getRepository(Libro::class)
                          ->find($isbn);
            $formulario = $this->createForm(LibroType::class, $libro);
            $formulario->handleRequest($request);
            if ($formulario->isSubmitted() && $formulario->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($libro);
                try {
                    $entityManager->flush();
                    return $this->redirectToRoute('listar_libros');
                }catch (Exception $e) {
                    return new Response('herror al insertar el libro');
                }
            }
            return $this->render('nuevo_libro.html.twig',
                                 array('formulario' => $formulario->createView()));
        }

        /**
         * @Route("/libro/insertar/", name="insertar")
         */
        public function insertar() {
            foreach($this->libros as $libro) {
                $entityManager = $this->getDoctrine()->getManager();
                $newLibro = new Libro();
                $newLibro->setIsbn($libro['isbn']);
                $newLibro->setTitulo($libro['titulo']);
                $newLibro->setAutor($libro['autor']);
                $newLibro->setPaginas($libro['paginas']);
                $entityManager->persist($newLibro);
                try {
                    $entityManager->flush();
                } catch(Exception $e) {
                    return new Response('Error al insertar los libros');
                }
            }
            return $this->redirectToRoute('listar_libros');
        }

        /**
         * @Route("/libro/{isbn}", name="ficha_libro")
         */
        public function loadLibroByIsbn($isbn) {

            $libro = $this->getDoctrine()
                          ->getRepository(Libro::class)
                          ->find($isbn);
            return $this->render('ficha_libro.html.twig', array('libro' => $libro));
        }

        /**
         * @Route("/eliminar/{isbn}", name="eliminar")
         */
        public function eliminarLibro($isbn) {
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Libro::class);
            $libro = $repository->find($isbn);

            if ($libro) {
                $entityManager->remove($libro);

                try {
                    $entityManager->flush();
                    return $this->redirectToRoute('listar_libros');
                } catch (Exception $e) {
                    return new Response('Error al eliminar el libro');
                }
            }
        }

        /**
        * @Route("/libros/paginas/{paginas}", name="filtrar")
        */
        public function filtrar($paginas) {
            /**
             * @var LibroRepository
             */
            $repository = $this->getDoctrine()->getRepository(Libro::class);
            $libros = $repository->nPaginas($paginas);
            return $this->render('lista_libros_paginas.html.twig', array('libros' => $libros));
        }

        /**
        * @Route("/libros/insertarConEditorial", name="insertar_editorial")
        */
        public function insertarConEditorial() {
            /**
             * @var EditorialRepository
             */
            $entityManager = $this->getDoctrine()->getManager();
            $editrorial = new Editorial();
            $editrorial->setNombre('Alfaguara');
            $entityManager->persist($editrorial);

            try {
                $entityManager->flush();
                // Si se inserta la editorial correctamente
                $libro = new Libro();
                $libro->setIsbn('2222BBBB');
                $libro->setTitulo('Libro de prueba con editorial');
                $libro->setAutor('Autor de prueba con editorial');
                $libro->setPaginas(200);
                $libro->setEditorial($editrorial);
                
                $entityManager->persist($libro);
                $entityManager->flush();
                return $this->redirectToRoute('listar_libros');
            } catch(Exception $e) {
                return new Response('Error al insertar....');
            }
        }
    }

?>