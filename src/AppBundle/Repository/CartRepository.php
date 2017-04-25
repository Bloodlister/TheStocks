<?php

namespace AppBundle\Repository;

/**
 * CartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $id integer
     */
    public function getCartsOfUser($id)
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

}
