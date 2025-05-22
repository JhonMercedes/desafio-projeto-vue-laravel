# Desafio Desenvolvedor Junior - Novo Mundo

## Descrição do Projeto
    A aplicação deve permitir que o usuário interaja com um catálogo de produtos
    utilizando uma interface web. O objetivo é implementar uma solução que
    permita criar, atualizar, apagar e listar produtos armazenados em um banco de
    dados.

> Requisitos:
- Frontend(Vue.js): Crud
- Backend(Laravel): API Restful oferecendo endpoints
- Banco de Dados: Mysql/PostgresSQL,SQLite ...
- Documentação
- Deploy

- Backend Laravel com API RESTful (/api/products)
- Frontend Vue.js com interface CRUD simples
- Banco de dados MySQL
- Docker para orquestrar tudo

# Inicio do Projeto Vue+Laravel

## Tecnologias utilizadas
~~~

- Laravel 10+
- Vue.js
- MySQL
- Docker e Docker Compose
- Axios
~~~
## Funcionalidades
~~~

- [x] Listar produtos
- [x] Cadastrar novo produto
- [x] Atualizar produto existente
- [x] Remover produto
- [x] Interface amigável com Vue.js
- [x] API RESTful com Laravel
~~~

##  Deploy do Projeto 
### Pré-requisitos

- Docker 
- Docker compose
  
### Clone o projeto 
~~~bash
git clone https://github.com/JhonMercedes/desafio-projeto-vue-laravel.git

cd nome-do-repo
~~~ 

- Nesse exemplo o nome será **catalogo-produtos**
~~~bash
~ cd catalogo-produtos/
~ catalogo-produtos#
~~~

> Subindo os containers

~~~bash
docker compose up -d --build 
docker ps # para ver os container UP

CONTAINER ID   IMAGE                          COMMAND                  CREATED        STATUS        PORTS                                                    NAMES
cb3c085beb78   catalogo-produto-pj-app        "docker-php-entrypoi?"   43 hours ago   Up 30 hours   0.0.0.0:8000->8000/tcp, [::]:8000->8000/tcp              laravel-app
f1be07f07a9b   mysql:8.0                      "docker-entrypoint.s?"   43 hours ago   Up 43 hours   0.0.0.0:3306->3306/tcp, [::]:3306->3306/tcp, 33060/tcp   mysql-db
c7904a0a49c3   catalogo-produto-pj-frontend   "docker-entrypoint.s?"   43 hours ago   Up 43 hours   0.0.0.0:5173->5173/tcp, [::]:5173->5173/tcp              vue-frontend
~~~

- Acesse:

Laravel API: http://localhost:8000/api/products

Vue frontend: http://localhost:5173

## Container do Mysql

- Acesse o banco de dados para verificar os produtos cadastrados, acesse o container pelo comando 
~~~bash 
docker exec -it mysql-db mysql -u root -p
# Senha: root (ou a que você definiu)
~~~
#
~~~sql
show databases;
use produtos_db;
show tables;

select * from products;
+----+-------+-------------+---------+---------------------+---------------------+
| id | name  | description | price   | created_at          | updated_at          |
+----+-------+-------------+---------+---------------------+---------------------+
|  6 | fogao | 6 bocas     | 6000.00 | 2025-05-22 18:46:44 | 2025-05-22 19:05:39 |
|  7 | fogao | 4 bocas     | 1300.00 | 2025-05-22 19:03:33 | 2025-05-22 19:05:57 |
+----+-------+-------------+---------+---------------------+---------------------+
~~~
#
~~~bash
docker compose down # use para destrutir todos os containers

 ? Container laravel-app                Removed                                                                                                                                           10.3s 
 ? Container vue-frontend               Removed                                                                                                                                            1.1s 
 ? Container mysql-db                   Removed                                                                                                                                            2.6s 
 ? Network catalogo-produto-pj_default  Removed 
~~~
 
##  Observações
A API RESTful está disponível em /api/products
~~~bash
curl http://localhost:8000/api/products
~~~
Os dados são persistidos no container MySQL (produtos_db)