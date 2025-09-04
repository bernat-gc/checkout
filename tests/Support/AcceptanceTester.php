<?php

declare(strict_types=1);

namespace BGC\Checkout\Tests\Support;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\CartItem;
use BGC\Checkout\Carts\Domain\Collection\CartItems;
use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use BGC\Checkout\Product\Domain\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Tests\ObjectMother\CartItemMother;
use BGC\Checkout\Tests\ObjectMother\CartMother;
use BGC\Checkout\Tests\ObjectMother\PriceMother;
use BGC\Checkout\Tests\ObjectMother\ProductMother;
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
        'cart' => Cart::class,
        'cart_item' => CartItem::class,
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
     * @When I PATCH to ":uri" with json body: :body
     */
    public function iPatchToUriWithJson(string $uri, PyStringNode $body): void
    {
        $this->haveHttpHeader('Content-Type', 'application/json');

        $this->sendPATCH($uri, $body->getRaw());
    }

    /**
     * @When I GET to ":uri"
     */
    public function iGetToUri(string $uri): void
    {
        $this->sendGET($uri);
    }

    /**
     * @When I DELETE to ":uri"
     */
    public function iDeleteToUri(string $uri): void
    {
        $this->sendDelete($uri);
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
     * @Then database should not have a :entity with attributes:
     */
    public function databaseShouldNotHaveAWithAttributes($entity, TableNode $attributes)
    {
        $keys = null;
        foreach ($attributes->getRows() as $index => $row) {
            if ($index == 0) {
                $keys = $row;
                continue;
            }

            $row = $this->cleanRow($row);

            $criteria = array_combine($keys, $row);
            // dump($this->grabEntityFromRepository(CartItem::class, ['id' => new Uuid($criteria['id'])]));
            $this->dontSeeInRepository(
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

    /**
     * @Given I have these items:
     */
    public function iHaveTheseItems(TableNode $attributes)
    {
        $keys = null;
        foreach ($attributes->getRows() as $index => $row) {
            if ($index == 0) {
                $keys = $row;
                continue;
            }

            $row = $this->cleanRow($row);

            $values = array_combine($keys, $row);

            $price = PriceMother::aPrice(
                (int)$values['cents_amount'] ?? self::faker()->numberBetween(10, 2000),
                $values['currency'] ?? 'EUR'
            );

            $product = ProductMother::aProduct(
                id: $values['product_id'] ?? self::faker()->uuid(),
                description: $values['description'] ?? self::faker()->word(),
                price: $price
            );

            $cartItem = CartItemMother::aCartItem(
                id: $values['id'] ?? self::faker()->uuid(),
                product: $product,
                quantity: (int)$values['quantity'] ?? self::faker()->numberBetween(1,4)
            );

            $cart = $this->grabEntityFromRepository(Cart::class, ['id' => $values['cart_id']]);
            $cart->addItem($cartItem);

            $cartItem = $this->haveInRepository(
                $cartItem
            );
        }
    }

    /**
     * @Given I have these products:
     */
    public function iHaveTheseProducts(TableNode $attributes)
    {
        $keys = null;
        foreach ($attributes->getRows() as $index => $row) {
            if ($index == 0) {
                $keys = $row;
                continue;
            }

            $row = $this->cleanRow($row);

            $values = array_combine($keys, $row);

            $price = PriceMother::aPrice(
                (int)$values['cents_amount'] ?? self::faker()->numberBetween(10, 2000),
                $values['currency'] ?? 'EUR'
            );

            $product = new Product(
                id: new Uuid($values['id'] ?? self::faker()->uuid()),
                description: $values['description'] ?? self::faker()->word(),
                price: $price
            );

            $this->haveInRepository($product);
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
