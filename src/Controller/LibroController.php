<?php  

namespace App\Controller;
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
        * @Route("/libros", name="libros")
        */  
        public function loadLibros() {
            return $this->render('ficha_libros.html.twig', array('libros' => $this->libros));
        }

        /**
         * @Route("/libro/{isbn}", name="libro")
         */
        public function loadLibroByIsbn($isbn) {
            $result = array_filter($this->libros, function($libro) use ($isbn) {
                return strpos($libro["isbn"], $isbn) !== false;
            });
            return $this->render('ficha_libro.html.twig', array('libro' => $result));
        }
    }

?>