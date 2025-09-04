Feature: Modify Cart item

    Background:
        Given I have these carts:
        | id                                   | user_id                              |
        | 11e0db78-06f5-4563-aa74-b611b77c7905 | 019913e6-40d1-7eb9-bf52-8399ae5a3680 |
        | d50c2450-6dee-4588-ad61-6eda621d8a56 | e22fe056-75d8-4187-a592-32e2d5011840 |
        And I have these items:
        | id                                   | cart_id                              | product_id                           | description | cents_amount | currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | 11e0db78-06f5-4563-aa74-b611b77c7905 | fcdae761-7c72-4b22-89d9-11eed548280a | Product1    | 1000         | EUR      | 2        |
        | af45442b-674e-4485-9413-b15d859fb241 | d50c2450-6dee-4588-ad61-6eda621d8a56 | fcdae761-7c72-4b22-89d9-11eed548280a | Product1    | 1000         | EUR      | 2        |
        And the cart with id "d50c2450-6dee-4588-ad61-6eda621d8a56" is ordered

    Scenario: A Cart can be ordered
        When I PATCH to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905/order" with json body:
        """
        {
            "status": "Ordered"
        }
        """
        Then response should be 204
        And database should have a "cart" with attributes:
        | id                                   | status  |
        | 11e0db78-06f5-4563-aa74-b611b77c7905 | Ordered |

    Scenario: An already ordered Cart remains ordered
        When I PATCH to "/carts/d50c2450-6dee-4588-ad61-6eda621d8a56/order" with json body:
        """
        {
            "status": "Ordered"
        }
        """
        Then response should be 204
        And database should have a "cart" with attributes:
        | id                                   | status  |
        | d50c2450-6dee-4588-ad61-6eda621d8a56 | Ordered |

    Scenario: An already ordered Cart can not be transitioned back to Shopping
        When I PATCH to "/carts/d50c2450-6dee-4588-ad61-6eda621d8a56/order" with json body:
        """
        {
            "status": "Shopping"
        }
        """
        Then response should be 400
        And database should have a "cart" with attributes:
        | id                                   | status  |
        | d50c2450-6dee-4588-ad61-6eda621d8a56 | Ordered |
