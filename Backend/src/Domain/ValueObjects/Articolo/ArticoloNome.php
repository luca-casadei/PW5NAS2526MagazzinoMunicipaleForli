<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Articolo;

use InvalidArgumentException;

/**
 * Wrapper immutabile per il nome dell'articolo.
 */
final readonly class ArticoloNome
{
    /**
     * @param string $valore Deve essere una stringa non vuota.
     */
    public function __construct(public string $valore)
    {
        if (strlen(trim($valore)) <= 0) {
            throw new InvalidArgumentException('Il nome dell\'articolo non può essere vuoto.');
        }
    }
}