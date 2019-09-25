<?php
declare(strict_types=1);

namespace EoneoPay\MigrationPhpSdk\Endpoints;

use EoneoPay\PhpSdk\Endpoints\PaymentSources\CreditCard;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @method string|null getToken()
 */
class Token extends CreditCard
{
    /**
     * V1 token.
     *
     * @Groups({"create"})
     *
     * @var string|null
     */
    protected $token;

    /**
     * {@inheritdoc}
     */
    public function uris(): array
    {
        // Overload create URI to redirect to rewards migration endpoint.
        return \array_merge(parent::uris(), [
            self::CREATE => \sprintf('/rewards/tokens/%s', $this->token),
        ]);
    }
}
