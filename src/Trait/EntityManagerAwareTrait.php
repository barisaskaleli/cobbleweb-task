<?php


namespace App\Trait;


use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait EntityManagerAwareTrait
 * @package CarInsurance\Traits
 */
trait EntityManagerAwareTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @required
     *
     * @param EntityManagerInterface $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->entityManager = $entityManager;

        return $this;
    }
}
