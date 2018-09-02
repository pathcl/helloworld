# import config.
# You can change the default config with `make cnf="config_special.env" build`
cnf ?= config.env
include $(cnf)
export $(shell sed 's/=.*//' $(cnf))

# import deploy config
# You can change the default deploy config with `make cnf="deploy_special.env" release`
dpl ?= deploy.env
include $(dpl)
export $(shell sed 's/=.*//' $(dpl))

# grep the version from the mix file
rls ?= version.env
include $(rls)
export $(shell sed 's/=.*//' $(rls))

nginx_pod=`kubectl get pods --no-headers=true|grep nginx|cut -f1 -d' '`
pgsql_pod=`kubectl get pods --no-headers=true|grep postgres|cut -f1 -d' '`

# HELP
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# DOCKER TASKS
# Build the container
build: ## Build the container
	docker build -t $(APP_NAME):$(VERSION) helloworld/

run: ## Run container on port configured in `config.env`
	docker run -i -t --rm --env-file=./config.env -p=$(PORT):$(PORT) --name="$(APP_NAME)" $(APP_NAME)

up: build run ## Run container on port configured in `config.env` (Alias to run)

stop: ## Stop and remove a running container
	docker stop $(APP_NAME); docker rm $(APP_NAME)

login: ## Docker login
	@echo 'login into hub.docker.com'
	docker login

publish: ## TODO: tag && publish image to hub.docker.com
	#we should tag prior to push
	#@echo 'create tag $(VERSION)'
	#docker tag $(APP_NAME) $(APP_NAME):$(VERSION)
	@echo 'about to publish......'
	docker push $(APP_NAME):$(VERSION)

version: ## Output the current version
	@echo $(APPVERSION)

nginx:
	@echo 'Forwarding nginx traffic'
	kubectl port-forward $(nginx_pod) 8080:80

postgres:
	@echo 'Forwarding nginx traffic'
	kubectl port-forward $(pgsql_pod) 5432:5432

deploy:
	@echo 'Deploying postgres database'
	kubectl create -f helloworld/database-all-objects-one-file.yml
	@echo 'Deploying frontend (nginx)'
	kubectl create -f helloworld/frontend.yml
	@echo 'Deploying redis'
	kubectl create -f helloworld/redis.yml