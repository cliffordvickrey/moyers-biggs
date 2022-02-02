<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Enum;

use CliffordVickrey\MoyersBiggs\Domain\Exception\InvalidArgumentException;
use JsonSerializable;
use Stringable;

use function sprintf;
use function substr;

/**
 * Answer type enum: either "Moyers" or "Biggs"
 *
 * @PHP 8.1 enum
 */
final class Valence implements JsonSerializable, Stringable
{
    public const BIGGS = 'Biggs';
    public const MOYERS = 'Moyers';

    /** @var array<string, string> */
    private static array $enum = [self::BIGGS => self::BIGGS, self::MOYERS => self::MOYERS];
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!isset(self::$enum[$value])) {
            throw new InvalidArgumentException(sprintf('Invalid value for %s, "%s"', self::class, $value));
        }

        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toType(): string
    {
        return substr($this->value, 0, 1);
    }
}
