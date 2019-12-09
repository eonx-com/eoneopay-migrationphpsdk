<?php
declare(strict_types=1);

namespace Tests\EoneoPay\MigrationPhpSdk\Endpoints;

use EoneoPay\MigrationPhpSdk\Endpoints\Ewallet;
use EoneoPay\PhpSdk\Endpoints\Balance;
use Tests\EoneoPay\MigrationPhpSdk\TestCase;

/**
 * @covers \EoneoPay\MigrationPhpSdk\Endpoints\Ewallet
 */
final class EwalletTest extends TestCase
{
    /**
     * Test an ewallet can be migrated with balance and reference.
     *
     * @return void
     */
    public function testMigrateEwallet(): void
    {
        $ewallet = $this->createApiManager(
            [
                'balances' => [
                    'available' => '1000.01',
                    'balance' => '1000.01',
                    'credit_limit' => null,
                ],
                'created_at' => '2019-02-26T00-14-25Z',
                'currency' => 'AUD',
                'id' => 'dad99a43563c72a19a99aae4b1605b49',
                'pan' => 'W...J3X7',
                'primary' => false,
                'reference' => 'WCMKZAJ3X7',
                'type' => 'ewallet',
                'updated_at' => '2019-02-26T00-14-25Z',
                'user' => [
                    'created_at' => '2019-02-22T03-09-44Z',
                    'email' => 'example@user.test',
                    'metadata' => [],
                    'name' => 'test',
                    'updated_at' => '2019-02-22T03-09-44Z',
                ],
            ],
            201
        )->create((string)\getenv('PAYMENTS_API_KEY'), new Ewallet([
            'balance' => '1000.01',
        ]));

        self::assertInstanceOf(Ewallet::class, $ewallet);

        /**
         * @var \EoneoPay\MigrationPhpSdk\Endpoints\Ewallet $ewallet
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises assertion
         */
        $balances = $ewallet->getBalances();
        self::assertInstanceOf(Balance::class, $balances);
        self::assertSame('1000.01', $balances->getAvailable());
        self::assertSame('1000.01', $balances->getBalance());
    }

    /**
     * Test to check if Uri has been entered.
     *
     * @return void
     */
    public function testUriIsCreated(): void
    {
        $ewallet = new Ewallet();

        $uris = $ewallet->uris();

        self::assertSame('/rewards/ewallets', $uris[Ewallet::CREATE] ?? null);
    }
}
