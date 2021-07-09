<?php

declare(strict_types=1);

namespace App;

interface Flusher
{
    /**
     * Save data in db
     */
    public function flush(): void;
}
