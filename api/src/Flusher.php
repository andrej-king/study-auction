<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    private EntityManagerInterface $em;

    /**
     * Flusher constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Save data in db
     */
    public function flush(): void
    {
        $this->em->flush();
    }
}
