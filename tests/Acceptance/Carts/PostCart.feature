Feature: Create a Cart

  Scenario: A Cart can be created
    When I POST to "/carts" with json body:
      """
      {
        "id": "b33edca8-efff-4191-8f13-8866f32cf281",
        "user_id": "eab6a90e-0c57-4f88-a6f1-a742be49d871"
      }
      """
    Then response should be 201
    And database should have a "cart" with attributes:
    | id                                   | userId                               | status   |
    | b33edca8-efff-4191-8f13-8866f32cf281 | eab6a90e-0c57-4f88-a6f1-a742be49d871 | Shopping |

  Scenario: Id must be a valid uuid
    When I POST to "/carts" with json body:
      """
      {
        "id": "123456",
        "user_id": "eab6a90e-0c57-4f88-a6f1-a742be49d871"
      }
      """
    Then response should be 400
    And response should contain "not a valid UUID"

  Scenario: User_id must be a valid uuid
    When I POST to "/carts" with json body:
      """
      {
        "id": "eab6a90e-0c57-4f88-a6f1-a742be49d871",
        "user_id": "e1234"
      }
      """
    Then response should be 400
    And response should contain "not a valid UUID"

  Scenario: Can not create an already existing cart
    Given I have these carts:
    | id                                   |
    | b33edca8-efff-4191-8f13-8866f32cf281 |
    When I POST to "/carts" with json body:
      """
      {
        "id": "b33edca8-efff-4191-8f13-8866f32cf281",
        "user_id": "eab6a90e-0c57-4f88-a6f1-a742be49d871"
      }
      """
    Then response should be 409
    And response should contain "Cart already created"
