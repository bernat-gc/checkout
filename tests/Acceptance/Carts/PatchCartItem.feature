Feature: Modify Cart item

    Background:
        Given I have these carts:
        | id                                   | user_id                              |
        | 11e0db78-06f5-4563-aa74-b611b77c7905 | 019913e6-40d1-7eb9-bf52-8399ae5a3680 |
        And I have these items:
        | id                                   | cart_id                              | product_id                           | description | cents_amount | currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | 11e0db78-06f5-4563-aa74-b611b77c7905 | fcdae761-7c72-4b22-89d9-11eed548280a | Product1    | 1000         | EUR      | 2        |

    Scenario: A cart item quantity attribute can be increased
        When I PATCH to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items/b7bd21d2-778d-402a-be01-37ca726288f0" with json body:
        """
        {
            "quantity": 3
        }
        """
        Then response should be 204
        And database should have a "cart_item" with attributes:
        | id                                   | product.id                           | product.price.centsAmount | product.price.currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | fcdae761-7c72-4b22-89d9-11eed548280a | 1000                      | EUR                    | 3        |

    Scenario: A cart item quantity attribute can be decreased
        When I PATCH to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items/b7bd21d2-778d-402a-be01-37ca726288f0" with json body:
        """
        {
            "quantity": 1
        }
        """
        Then response should be 204
        And database should have a "cart_item" with attributes:
        | id                                   | product.id                           | product.price.centsAmount | product.price.currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | fcdae761-7c72-4b22-89d9-11eed548280a | 1000                      | EUR                    | 1        |

    Scenario: A cart item modification request must be idempotent
        When I PATCH to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items/b7bd21d2-778d-402a-be01-37ca726288f0" with json body:
        """
        {
            "quantity": 2
        }
        """
        Then response should be 204
        And database should have a "cart_item" with attributes:
        | id                                   | product.id                           | product.price.centsAmount | product.price.currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | fcdae761-7c72-4b22-89d9-11eed548280a | 1000                      | EUR                    | 2        |

    Scenario: Asking for a non-exiting Cart should return not-found response
        When I PATCH to "/carts/ab2680d5-9925-4c71-886b-75b692103807/items/b7bd21d2-778d-402a-be01-37ca726288f0" with json body:
        """
        {
            "quantity": 3
        }
        """
        Then response should be 404
        And response should contain "Cart with id ab2680d5-9925-4c71-886b-75b692103807 not found"

    Scenario: Asking for a non-exiting Cart item should return not-found response
        When I PATCH to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/items/ab2680d5-9925-4c71-886b-75b692103807" with json body:
        """
        {
            "quantity": 3
        }
        """
        Then response should be 404
        And response should contain "Cart item with id ab2680d5-9925-4c71-886b-75b692103807 not found"
