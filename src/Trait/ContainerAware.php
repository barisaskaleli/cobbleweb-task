<?php

namespace App\Trait;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerAware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @required
     *
     * @param ContainerInterface $container
     * @return ContainerAware
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }
}
