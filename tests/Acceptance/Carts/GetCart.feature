Feature: Get Cart

    Background:
        Given I have these carts:
        | id                                   | user_id                              |
        | 11e0db78-06f5-4563-aa74-b611b77c7905 | 019913e6-40d1-7eb9-bf52-8399ae5a3680 |
        And I have these items:
        | id                                   | cart_id                              | product_id                           | description | cents_amount | currency | quantity |
        | b7bd21d2-778d-402a-be01-37ca726288f0 | 11e0db78-06f5-4563-aa74-b611b77c7905 | fcdae761-7c72-4b22-89d9-11eed548280a | Product1    | 1000         | EUR      | 2        |

    Scenario: A Cart can be recovered
        When I GET to "/carts/11e0db78-06f5-4563-aa74-b611b77c7905"
        Then response should be 200
        And response should contain "11e0db78-06f5-4563-aa74-b611b77c7905"

    Scenario: Asking for a non-exiting Cart should return not-found response
        When I GET to "/carts/25c8b25e-35d0-44ed-bcd7-e1f3d09264b8"
        Then response should be 404
        And response should contain "Cart with id 25c8b25e-35d0-44ed-bcd7-e1f3d09264b8 not found"
