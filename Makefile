APP_NAME = app
TEST_CMD = vendor/bin/codecept run
VERBOSE = -vv
SHELL_CMD = bash

build:
	@docker compose up --build -d
	@docker exec $(APP_NAME) composer install --no-interaction --no-progress --prefer-dist

test:
	@docker exec $(APP_NAME) $(TEST_CMD)

testv:
	@docker exec $(APP_NAME) $(TEST_CMD) $(VERBOSE)

in:
	@docker exec $(APP_NAME) $(SHELL_CMD)

clean:
	@docker compose down

.PHONY: build test testv in clean
