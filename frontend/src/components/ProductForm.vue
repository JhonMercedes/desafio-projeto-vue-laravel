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
