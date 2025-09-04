# Checkout
This project has been made as a technical test.

The project is a MVP for a checkout API. The main goal is to manage the creation, modification and processing of Carts.

## Technical aspects
The project includes a docker-compose.yaml with three services:
- app: a php fpm image where the code PHP is processed
- mysql: a database for storing the carts data
- nginx: to serve the code via HTTP

To start the project use the command:
```
docker compose up --build -d
```

Then the API will be available on `localhost:8000`.

To run the tests, use the command:
```
docker exec app vendor/bin/codecept run
```

## Model
It has been modeled using a main bounded context of Carts, and bounded context for Products which it is not complete, and it is just for examplification.

In the bounded context of Cart there is only one aggregate root, Cart. It has an `id` and a `user_id`, and a list of Items. As an aggregate root, it also has `created_at` and `updated_at` timestamps to monitor creation and modification, for data ingestion purposes. It also has an `status` attribute, which starts with value `Shopping` and can be modified to `Ordered`. After being modified, no more modifications of the Cart or its items are allowed. It represent that the Cart has been checked out and an Order and subsequent payment has been created.

The list of items can has multiple elements. Each one has a product and a quantity, and the product has an identificator, a description and a price. It has been modeled as a value-object in this bounded-context because they are only snapshots of the products and no modification are allowed.

The model can be (and must be) easily extended to include more field to Cart model or its items.

In the bounded context of Product there is only a query use-case to search for products. Although the Product aggregate root has the same fields as the value-object in Carts bounded-context, in a normal situation it should have a more rich model and logic.

## API Description
The api doc can be seen in `localhost:8000/api/doc`

There are six endpoint to invoke the following functionalities:
- Create a Cart: POST to `/cart` with a json body
- Add an item to Cart: POST to `/carts/{cart_id}/items`
- Modify the quantity in an item: PATCH to `/carts/{cart_id}/items/{item_id}`
- Delete an item:  DELETE to `/carts/{cart_id}/items/{item_id}`
- Get a Cart and its items: GET to `/carts/{cart_id}`
- Order a Cart: PATCH to `/carts/{cart_id}/order`

The last endpoint only changes the status attribute of Cart and publishes an event of `CartOrdered`. This event can be synchronously processed in a Order bounded context to create a new Order for the Cart.

## Further improvements
There a lot of aspect that are important for an API but has not been implemented.
- Logging: Although monolog has been installed, no log messages have included in the logic. I considered that other aspect were more rellevant for the goal of this test.
- Authentication: The API has no authentication nor a user entity defined. It only has the user_id field in Carts. If I has to implement it, I will use JWTs for it, with the user_id and roles in them. This will allow to control who can see, modified or order the Carts (only the owner or a high rol user).
- Broadcasting service: the symfony messenger component it has been configured only for command and query buses, and no event bus has been used. There can be configured a synchronous and an asynchronous buses, to allow other services as well as this one to consume events and react in consequence.
- Testing: it has been included only basic unit and acceptance tests. Functional and integration tests can be included, as well as increment the coverage of the existing ones.

And finally, as stated above, a very simple model has been implemented. The status attribute can be extended with more values, and a finite state machine to control its transitions.
