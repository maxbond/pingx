<script setup>
import { reactive, ref } from "vue";

const item = reactive({ host: "", name: "" });

const error = ref(false);

const emit = defineEmits(["addItem"]);

function addItem() {
  if (item.host) {
    emit("addItem", { name: item.name, host: item.host });
    item.name = item.host = "";
    error.value = false;
  } else {
    error.value = true;
  }
}
</script>
<template>
  <div>
    <form @submit.prevent="addItem">
      <div class="mb-3">
        <input
          placeholder="Host description"
          class="form-control"
          type="text"
          v-model="item.name"
        />
      </div>
      <div class="mb-3">
        <input
          placeholder="IP/HOST"
          class="form-control"
          :class="{ 'is-invalid': error }"
          type="text"
          v-model="item.host"
        />
        <div class="invalid-feedback">Please specify item ip or host</div>
      </div>
      <button class="btn btn-primary">Add</button>
    </form>
  </div>
</template>
