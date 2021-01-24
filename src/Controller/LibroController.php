<?php  

namespace App\Controller;

use App\Entity\Libro;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    class LibroController extends AbstractController{

        private $libros = array(
            array("isbn" => "A001", "titulo" => "JarryChoped", "autor" => "JK Bowling", "paginas" => 100),
            array("isbn" => "A002", "titulo" => "El señor de los palillos", "autor" => "JRR TolQuien", "paginas" => 200),
            array("isbn" => "A003", "titulo" => "Los polares de la tierra", "autor" => "Ken Follonett", "paginas" => 300),
            array("isbn" => "A004", "titulo" => "Los juegos de enjambre", "autor" => "Suzanne Collonins", "paginas" => 400)
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
         * @Route("/libro/{isbn}", name="ficha_libro")
         */
        public function loadLibroByIsbn($isbn) {

            $libro = $this->getDoctrine()
                          ->getRepository(Libro::class)
                          ->find($isbn);
            if ($libro) {
                return $this->render('ficha_libro.html.twig', array('libro' => $libro));
            }
            return new Response('No se localiza el libro');
        }

        /**
         * @Route("/insertar/", name="insertar")
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
        * @Route("/filtrar/{paginas}", name="filtrar")
        */
        public function filtrar($paginas) {
            $repository = $this->getDoctrine()->getRepository(Libro::class);
            $libros = $repository->nPaginas($paginas);
            return $this->render('lista_libros_paginas.html.twig', array('libros' => $libros));
        }

        /**
        * 
        */  
        /* public function filtrarPaginas($paginas) {
            $repository = $this->getDoctrine()->getRepository(Libro::class);
            $libros = $repository->nPaginas($paginas); 
            if ($libros) {
                return $this->render('lista_libros_paginas.html.twig');
            }
        } */

    }

?>