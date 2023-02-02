<script setup>
import { onMounted, ref } from "vue";
import AddItem from "./components/AddItem.vue";
import Item from "./components/Item.vue";

const items = ref(null);

function itemAdded(value) {
  items.value.push(value);
  localStorage.items = JSON.stringify(items.value);
}

function itemRemoved(index) {
  if (items.value) {
    items.value.splice(index, 1);
    localStorage.items = JSON.stringify(items.value);
  }
}

onMounted(() => {
  items.value = localStorage.items ? JSON.parse(localStorage.items) : [];
});
</script>

<template>
  <div class="row mt-5">
    <div class="col-lg-8">
      <Item
        v-for="(item, idx) in items"
        :key="'item-' + idx"
        :item="item"
        :index="idx"
        @remove-item="itemRemoved"
      />
    </div>
    <div class="col-lg-4">
      <AddItem @add-item="itemAdded" />
    </div>
  </div>
</template>
