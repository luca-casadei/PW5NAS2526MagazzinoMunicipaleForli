<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Value;

use InvalidArgumentException;

/**
 * Wrapper immutabile per l'identificativo unico dell'articolo.
 */
final readonly class NumeroScaffale
{
    /**
     * @param int $numero Deve essere maggiore di zero.
     */
    public function __construct(public int $numero)
    {
        if ($numero <= 0) {
            throw new InvalidArgumentException('Il numero di scaffale deve essere positivo.');
        }
    }
}