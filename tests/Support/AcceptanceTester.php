<?php

declare(strict_types=1);

namespace BGC\Checkout\Tests\Support;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\Collection\CartItems;
use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Tests\ObjectMother\CartMother;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Faker\Factory;
use Faker\Generator;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    private static $entityMap = [
        'cart' => Cart::class
    ];

    private static ?Generator $faker = null;

    public static function faker(): Generator
    {
        if (!self::$faker) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }

    /**
     * @When I POST to ":uri" with json body: :body
     */
    public function iPostToUriWithJson(string $uri, PyStringNode $body): void
    {
        $this->haveHttpHeader('Content-Type', 'application/json');

        $this->sendPOST($uri, $body->getRaw());
    }

    /**
     * @Then /^response should be (\d+)$/
     */
    public function responseShoudBe(string $code): void
    {
        $this->canSeeResponseCodeIs((int)$code);
    }

    /**
     * @Then database should have a :entity with attributes:
     */
    public function databaseShouldHaveAWithAttributes($entity, TableNode $attributes)
    {
        $keys = null;
        foreach ($attributes->getRows() as $index => $row) {
            if ($index == 0) {
                $keys = $row;
                continue;
            }

            $row = $this->cleanRow($row);

            $criteria = array_combine($keys, $row);
            $this->seeInRepository(
                self::$entityMap[$entity],
                $criteria
            );
        }
    }

    /**
     * @Then response should contain ":text"
     */
    public function responseShouldContain($text)
    {
        $this->canSeeResponseContains($text);
    }

    /**
     * @Given I have these carts:
     */
    public function iHaveTheseCarts(TableNode $attributes)
    {
        $keys = null;
        foreach ($attributes->getRows() as $index => $row) {
            if ($index == 0) {
                $keys = $row;
                continue;
            }

            $row = $this->cleanRow($row);

            $values = array_combine($keys, $row);

            $cart = CartMother::aCartWithoutItems(
                id: $values['id'] ?? self::faker()->uuid(),
                userId: $values['user_id'] ?? self::faker()->uuid()
            );
            $cart = $this->haveInRepository(
                $cart
            );
        }
    }

    private function cleanRow(array $row): array
    {
        return array_map(
            function ($value) {
                return ($value == 'NULL') ? null : $value;
            },
            $row
        );
    }
}
