<?php
declare(strict_types=1);

namespace EoneoPay\MigrationPhpSdk\Endpoints;

use EoneoPay\PhpSdk\Endpoints\Ewallet as BaseEwallet;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @method string|null getBalance()
 */
class Ewallet extends BaseEwallet
{
    /**
     * Initial ewallet balance.
     *
     * @Groups({"create"})
     *
     * @var string|null
     */
    protected $balance;

    /**
     * {@inheritdoc}
     */
    public function uris(): array
    {
        // Overload create URI to redirect to rewards migration endpoint.
        return \array_merge(parent::uris(), [
            self::CREATE => '/rewards/ewallets',
        ]);
    }
}
