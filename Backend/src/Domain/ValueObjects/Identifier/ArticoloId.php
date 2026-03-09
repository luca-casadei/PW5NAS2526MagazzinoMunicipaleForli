<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Identifier;

use InvalidArgumentException;

/**
 * Wrapper immutabile per l'identificativo unico dell'articolo.
 */
final readonly class ArticoloId
{
    /**
     * @param int $valore Deve essere maggiore di zero.
     */
    public function __construct(public int $valore)
    {
        if ($valore <= 0) {
            throw new InvalidArgumentException('L ID Articolo deve essere positivo.');
        }
    }
}