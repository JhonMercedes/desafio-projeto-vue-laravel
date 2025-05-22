# Desafio Desenvolvedor Junior - Novo Mundo

## Descrição do Projeto
    A aplicação deve permitir que o usuário interaja com um catálogo de produtos
    utilizando uma interface web. O objetivo é implementar uma solução que
    permita criar, atualizar, apagar e listar produtos armazenados em um banco de
    dados.

### Requisitos:
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

## Primeira etapa:

- Criar um diretorio para iniciar o projeto.
- Nesse exemplo o nome será **catalogo-produtos**
~~~bash
~ mkdir catalogo-produtos
~ cd catalogo-produtos/
~ catalogo-produtos# |
~~~

> Configurando o arquivo docker-compose.yml

- Na raiz do projeto `/` crie o arquivo docker-compose.yml
~~~yml
version: '3.8'

services:
  mysql:
    image: mysql:8
    environment:
      MYSQL_DATABASE: produtos_db
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

  backend:
    build:
      context: ./backend
    volumes:
      - ./backend:/var/www/html
    ports:
      - "8000:8000"
    depends_on:
      - mysql
    command: php artisan serve --host=0.0.0.0 --port=8000

  frontend:
    build:
      context: ./frontend
    volumes:
      - ./frontend:/app
    ports:
      - "5173:5173"
    command: npm run dev -- --host=0.0.0.0 --port=5173 

volumes:
  db_data:

~~~

> Backend Laravel
- Na raiz do projeto `/`  `catalogo-produtos` execute o comando para criar projeto laravael: 
- `composer create-projetc laravel/laravel backend`
- Configure o arquivo `.env`
~~~env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=produtos_db
DB_USERNAME=root
DB_PASSWORD=root
~~~
- Vamos gerar o Product Model / RouteServiceProvider e Controller
~~~bash
php artisan make:model Product -m
php artisan migrate
php artisan make:provider RouteServiceProvider
php artisan make:controller API/ProductController --api
~~~
- Acesse: `database/migrations/xxxx_create_products_table.php` e altere o Schema=>
~~~php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->timestamps();
    });
}
~~~
- Acesse: `app/Model/Product.php`
~~~php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price'];
}
~~~
- Acesse: `app/Http/Controllers/API/ProductController.php`
~~~php
namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index() { return Product::all(); }
    public function store(Request $request) {
        return Product::create($request->all());
    }
    public function show($id) {
        return Product::findOrFail($id);
    }
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return $product;
    }
    public function destroy($id) {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
~~~
- Acesse: `routes/api.php`
~~~php
<?php

use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);
~~~


> Frontend
- Volte a raiz do projeto `/` e execute  `npm init vue@latest frontend`
- Acesse a pasta `frontend` e execute os comando `npm install axios`
- Crie o arquivo *ProductList.vue* dentro de: `src/views/`
~~~ vue
<template>
  <div>
    <h1>Catálogo de Produtos</h1>

    <!-- Formulário para adicionar produto -->
    <ProductForm :product="selectedProduct" @submit="createOrUpdateProduct" />
    <!-- <ProductForm @submit="createProduct" />  -->

    <!-- Lista de produtos -->
    <ul>
      <li v-for="product in products" :key="product.id">
        <strong>{{ product.name }}</strong> - R$ {{ product.price }} <br />
        {{ product.description }} <br />
        <button @click="deleteProduct(product.id)">Remover</button>
        <button @click="editProduct(product)">Editar</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import ProductForm from "../components/ProductForm.vue";

const products = ref([]);
const selectedProduct = ref(null); // produto selecionado para edicao

const fetchProducts = async () => {
  const response = await axios.get("http://localhost:8000/api/products");
  products.value = response.data;
};

const createOrUpdateProduct = async (data) => {
  if (data.id) {
    // Atualização se existir ID
    await axios.put(`http://localhost:8000/api/products/${data.id}`, data); // atualiza dados preenchidos
  } else {
    // Criação de novo produto
    await axios.post("http://localhost:8000/api/products", data); // Novo produto criado
  }

  selectedProduct.value = null; // limpa após salvar
  fetchProducts();
};
// const createProduct = async (data) => {
//   try {
//     //console.log('Enviando dados Produto:', data) // Logs para verificar dados inseridos do produto
//     await axios.post('http://localhost:8000/api/products', data)
//     await fetchProducts()
//   } catch (error) {
//     console.error('Erro ao cadastrar produto:', error)
//     alert('Erro ao cadastrar produto. Verifique se o backend está rodando.')
//   }
// }

const editProduct = (product) => {
  selectedProduct.value = { ...product }; // clona produto para edição
};

const deleteProduct = async (id) => {
  await axios.delete(`http://localhost:8000/api/products/${id}`); //deleta produtos
  fetchProducts();
};

onMounted(() => {
  fetchProducts();
});
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
li {
  margin-bottom: 20px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
}
button {
  margin-top: 5px;
}
</style>

~~~
- Crie um arquivo *index.js*  dentro de: `src/router/` 
~~~javascript
import { createRouter, createWebHistory } from 'vue-router'
import ProductList from '../views/ProductList.vue'

const routes = [
  { path: '/', component: ProductList }
]

export default createRouter({
  history: createWebHistory(),
  routes
})
~~~

Crie o arquivo ProductForm.vue dentro de: `src/components/`

~~~vue 
<template>
  <form @submit.prevent="handleSubmit" class="product-form">
    <div>
      <label>Nome:</label>
      <input v-model="form.name" required />
    </div>
    <div>
      <label>Descrição:</label>
      <input v-model="form.description" required />
    </div>
    <div>
      <label>Preço:</label>
      <input type="number" v-model="form.price" required />
    </div>
    <!-- Botão muda texto conforme modo -->
    <button type="submit">{{ form.id ? 'Atualizar' : 'Cadastrar' }}</button>

  </form>
</template>

<script setup>
import { ref, watch, computed, defineProps, defineEmits } from 'vue'

const props = defineProps({
  product: Object // Recebe dados do produto em modo edição (opcional)
})

const emit = defineEmits(['submit'])

const form = ref({
  name: '',
  description: '',
  price: ''
})

// const isEdit = computed(() => !!props.product)

watch(
  () => props.product,
  (newVal) => {
    if (newVal) {
      form.value = { ...newVal } //modo edição
    } else {
      form.value = { name: '', description: '', price: '' } //modo criar
    }
  },
  { immediate: true }
)

const handleSubmit = () => {
  emit('submit', form.value) // emite evento com dados preenchidos
}
</script>

<style scoped>
.product-form {
  margin-bottom: 20px;
}
.product-form div {
  margin-bottom: 10px;
}
</style>
~~~

## Subindo o projeto 

- Execute o comando 
~~~bash
docker compose up --build 
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
~~~
 
