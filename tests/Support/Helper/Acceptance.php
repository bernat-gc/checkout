<?php

declare(strict_types=1);

namespace BGC\Checkout\Tests\Support\Helper;

use Codeception\Module;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class Acceptance extends Module
{
    public function _before(\Codeception\TestInterface $test)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getModule('Doctrine')->_getEntityManager();

        $meta = $em->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($em);

        $tool->dropSchema($meta);
        $tool->createSchema($meta);
    }
}
