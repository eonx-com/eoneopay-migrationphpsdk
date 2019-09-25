<?php
declare(strict_types=1);

namespace Tests\EoneoPay\MigrationPhpSdk\Endpoints;

use EoneoPay\MigrationPhpSdk\Endpoints\Token;
use Tests\EoneoPay\MigrationPhpSdk\TestCase;

/**
 * @covers \EoneoPay\MigrationPhpSdk\Endpoints\Token
 */
final class TokenTest extends TestCase
{
    /**
     * Test a v1 token can be migrated to a v2 token.
     *
     * @return void
     */
    public function testMigrateToken(): void
    {
        $token = $this->createApiManager(
            [
                'bin' => [
                    'bin' => '222300',
                    'category' => null,
                    'country' => null,
                    'created_at' => '2019-02-26T00-14-55Z',
                    'funding_source' => null,
                    'issuer' => null,
                    'prepaid' => null,
                    'scheme' => null,
                    'updated_at' => '2019-02-26T00-14-55Z',
                ],
                'created_at' => '2019-02-26T00-14-25Z',
                'expiry' => [
                    'month' => '10',
                    'year' => '2022',
                ],
                'facility' => 'Mastercard',
                'id' => 'dad99a43563c72a19a99aae4b1605b49',
                'name' => 'Test User',
                'one_time' => false,
                'pan' => '222300...0007',
                'token' => 'ABC123',
                'type' => 'credit_card',
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
        )->create((string)\getenv('PAYMENTS_API_KEY'), new Token([
            'token' => 'tok_123',
        ]));

        self::assertInstanceOf(Token::class, $token);

        /**
         * @var \EoneoPay\MigrationPhpSdk\Endpoints\Token $token
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises assertion
         */
        self::assertSame('ABC123', $token->getToken());
        self::assertSame('dad99a43563c72a19a99aae4b1605b49', $token->getId());
    }

    /**
     * Test to check if Uri has been entered.
     *
     * @return void
     */
    public function testUriIsCreated(): void
    {
        // Test token without token set
        $token = new Token();
        $uris = $token->uris();
        self::assertSame('/rewards/tokens/', $uris[Token::CREATE] ?? null);

        // Test token with token set
        $token = new Token(['token' => 'tok_123']);
        $uris = $token->uris();
        self::assertSame('/rewards/tokens/tok_123', $uris[Token::CREATE] ?? null);
    }
}
