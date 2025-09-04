Feature: Add item to Cart

    Background:
        Given I have these carts:
        | id                                   |
        | 11e0db78-06f5-4563-aa74-b611b77c7905 |
        And I have these products:
        | id                                   | description | cents_amount | currency |
        | fcdae761-7c72-4b22-89d9-11eed548280a | Product1    | 1000         | EUR      |

    Scenario: Add item to existing cart
        When I POST to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items" with json body:
        """
        {
            "id": "b7bd21d2-778d-402a-be01-37ca726288f0",
            "product_id": "fcdae761-7c72-4b22-89d9-11eed548280a",
            "quantity": 1
        }
        """
        Then response should be 201
        And database should have a "cart_item" with attributes:
        | id                                   | product.id                           | product.price.centsAmount | product.price.currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | fcdae761-7c72-4b22-89d9-11eed548280a | 1000                      | EUR                    | 1        |

    Scenario: Add item to a non-existing cart should fail
        When I POST to "/carts/e31f5675-46ac-410f-a8a4-f991f541de1e/items" with json body:
        """
        {
            "id": "b7bd21d2-778d-402a-be01-37ca726288f0",
            "product_id": "fcdae761-7c72-4b22-89d9-11eed548280a",
            "quantity": 1
        }
        """
        Then response should be 404
        And response should contain "Cart with id e31f5675-46ac-410f-a8a4-f991f541de1e not found"

    Scenario: Add item with a non-existing product should fail
        When I POST to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items" with json body:
        """
        {
            "id": "b7bd21d2-778d-402a-be01-37ca726288f0",
            "product_id": "e31f5675-46ac-410f-a8a4-f991f541de1e",
            "quantity": 1
        }
        """
        Then response should be 400
        And response should contain "Product with id e31f5675-46ac-410f-a8a4-f991f541de1e not found"

    Scenario: Add item with non-positive quantity should fail
        When I POST to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items" with json body:
        """
        {
            "id": "b7bd21d2-778d-402a-be01-37ca726288f0",
            "product_id": "fcdae761-7c72-4b22-89d9-11eed548280a",
            "quantity": 0
        }
        """
        Then response should be 400
        And response should contain "This value should be positive"
