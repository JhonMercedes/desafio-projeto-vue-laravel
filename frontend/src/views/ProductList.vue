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
