CREATE DATABASE helloprint;

\c helloprint

CREATE TABLE employees(
   ID INT PRIMARY KEY     NOT NULL,
   NAME           TEXT    NOT NULL,
   LASTNAME       TEXT    NOT NULL,
   AGE            INT     NOT NULL,
   ADDRESS        CHAR(50),
   SALARY         REAL
);

INSERT INTO employees (ID,NAME,LASTNAME,AGE,ADDRESS,SALARY) VALUES (1, 'Hello', 'World', 33, 'Rotterdam', 3333.00);
