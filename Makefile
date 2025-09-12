APP_NAME = app
TEST_CMD = vendor/bin/codecept run
VERBOSE = -vv
SHELL_CMD = bash

build:
	docker compose up --build -d

test:
	docker exec $(APP_NAME) $(TEST_CMD)

testv:
	docker exec $(APP_NAME) $(TEST_CMD) $(VERBOSE)

in:
	docker exec $(APP_NAME) $(SHELL_CMD)

clean:
	docker compose down
