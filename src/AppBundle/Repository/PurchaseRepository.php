<?php

namespace AppBundle\Repository;

/**
 * PurchaseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PurchaseRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param $id integer
     */
    public function getPurchasesByUser($id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

}