<template>
  <div class="p-8">
    <h1> Pizza Planet Order</h1>

    <select v-model="pizza_id">
      <option value="">Make your own</option>
      <option v-for="p in pizzas.data" :value="p.id">{{ p.name }} (£{{ p.price }})</option>
    </select>

    <div v-if="!pizza_id" class="mt-4">
      <h3>Select up to 4 toppings:</h3>
      <label v-for="t in toppings.data" :key="t.id">
        <input type="checkbox" :value="t.id" v-model="selectedToppings" :disabled="selectedToppings.length >= 4 && !selectedToppings.includes(t.id)" />
        {{ t.name }}
      </label>
    </div>

    <div class="mt-4">
      <label><input type="radio" value="card" v-model="payment_method" /> Card</label>
      <label><input type="radio" value="paypal" v-model="payment_method" /> PayPal</label>
    </div>

    <button class="mt-4" @click="placeOrder">Place Order</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const pizzas = ref([])
const toppings = ref([])
const pizza_id = ref('')
const selectedToppings = ref([])
const payment_method = ref('card')

onMounted(async () => {
  pizzas.value = (await axios.get('/api/pizzas')).data
  toppings.value = (await axios.get('/api/toppings')).data
})

const placeOrder = async () => {
  const res = await axios.post('/api/orders', {
    pizza_id: pizza_id.value || null,
    toppings: pizza_id.value ? [] : selectedToppings.value,
    payment_method: payment_method.value,
  })
  alert(res.data.message)
}
</script>
