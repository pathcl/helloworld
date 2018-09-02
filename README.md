# Hello world application:

- Basic setup for kubernetes+nginx+postgresql+redis+php

You'll need:

* minikube
* internet connection
* 1-2 hours

# Install minikube:

## macOS
```shell
brew cask install minikube
```

## Linux
```shell
curl -Lo minikube https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64 && chmod +x minikube && sudo cp minikube /usr/local/bin/ && rm minikube
```

* [Further information can be found here](https://github.com/kubernetes/minikube/blob/master/README.md)

# Application setup

### Clone the repo

    git clone https://github.com/pathcl/helloworld.git && cd helloworld

You'll find several files:

```shell
.
├── Makefile
├── README.md
├── config.env
├── deploy.env
├── helloworld
│   ├── Dockerfile
│   ├── database-all-objects-one-file.yml
│   ├── database-configmap.yml
│   ├── database-deployment.yml
│   ├── database-service.yml
│   ├── database-storage.yml
│   ├── database.sql
│   ├── frontend.yml
│   ├── nginx-service.yml
│   ├── redis.yml
│   └── wwwroot
│       ├── dbconfig.php
│       └── index.php
└── version.env
```

# First steps

Main folder for web application 

* In 'helloworld/wwwroot' folder you'll find the PHP app. Make changes there and then do 'make build' in order to create image. 
* P.D: You'll need to push the image to your registry (i.e. hub.docker.com)

## Deploy everything

    make -i deploy

This command will try to install: postgresql, nginx, redis. You should wait until every pod is running:

    kubectl get pods

    NAME                        READY     STATUS    RESTARTS   AGE
    nginx-bb58ff6bb-9mztl       1/1       Running   0          1m
    postgres-77cd88ff7d-kr2mg   1/1       Running   0          1m
    redis-6c78c5c69-w29f2       1/1       Running   0          1m

## Populate database for first time deployment

This is only needed first time since we're using persistent volumes.

    make postgres &
    psql -U postgres -f helloworld/database.sql -h localhost


## Test application

    make nginx

* Open your browser http://127.0.0.1:8080

# Separate steps if needed

### Deploy database
    kubectl create -f helloworld/database-all-objects-one-file.yml

### Deploy frontend (nginx)
    kubectl create -f helloworld/frontend.yml
    
### Deploy redis
    kubectl create -f helloworld/redis.yml

### Create Docker image
    make build

### Publish image to hub.docker.com
    make publish

### Forward traffic from frontend (nginx) to localhost
    NGINX_POD=`kubectl get pods --no-headers=true|grep nginx|cut -f1 -d' '`
    kubectl port-forward $NGINX_POD 8080:80

or 
    
    make nginx
