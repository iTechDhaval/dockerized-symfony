<?php

namespace AppBundle\Repository;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\ORM\EntityRepository;
/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    use ORMBehaviors\Timestampable\Timestampable;
}
