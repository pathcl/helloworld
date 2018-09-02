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

## Database
    psql -U postgres -f database.sql -h localhost
    kubectl port-forward `kubectl get pods --no-headers=true|grep postgres|cut -f1 -d' '` 5432:5432

## Web server
    kubectl port-forward `kubectl get pods --no-headers=true|grep nginx|cut -f1 -d' '` 8080:80

## Create Docker image
    make build

##  Publish image to hub.docker.com
    make publish