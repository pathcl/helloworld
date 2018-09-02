# Php && Pgsql && Redis

## database

    psql -U postgres -f database.sql -h localhost
    kubectl port-forward `kubectl get pods --no-headers=true|grep postgres|cut -f1 -d' '` 5432:5432

## nginx