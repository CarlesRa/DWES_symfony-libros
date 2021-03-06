<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Libro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Libro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Libro[]    findAll()
 * @method Libro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }

    /**
     * @return Libro[]
     */
    public function nPaginas($paginas) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT lib FROM App\Entity\Libro lib
                                              WHERE lib.paginas <= :paginas');
        return $query->setParameter('paginas', $paginas)->getResult();
    }

    /**
     * @return Libro[]
     */
    public function buscarLibros($filtro) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT lib FROM App\Entity\Libro lib
                                              WHERE lib.titulo LIKE :filtro');
        return $query->setParameter('filtro', '%'.$filtro.'%')->getResult();
    }
    // /**
    //  * @return Libro[] Returns an array of Libro objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Libro
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
