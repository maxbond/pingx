<script setup>
import { onMounted, reactive, ref } from "vue";
import axios from "axios";

const props = defineProps({
  item: Object,
  index: Number,
});

const emit = defineEmits(["removeItem"]);

const paused = ref(false);

const hostStatus = reactive({ alive: false, delay: null, error: null });

const API_URL = `${import.meta.env.VITE_PING_API_URL}`;

let interval = null;

async function ping() {
  if (paused.value) return;

  await axios
    .get(API_URL + props.item.host)
    .then((res) => {
      let data = res.data;
      hostStatus.error = null;
      hostStatus.alive = data.alive;
      hostStatus.delay = data.alive ? data.delay : null;
    })
    .catch((err) => {
      hostStatus.error =
        "response" in err ? err.response.data.error : err.message;
      hostStatus.alive = false;
      hostStatus.delay = null;
    });
}

function removeItem() {
  emit("removeItem", props.index);
  clearInterval(interval);
}

onMounted(() => {
  interval = setInterval(ping, 1000);
});
</script>
<template>
  <div
    class="alert"
    :class="{
      'alert-danger': !hostStatus.alive,
      'alert-success': hostStatus.alive,
    }"
  >
    <span v-if="hostStatus.error" class="badge rounded-pill bg-primary">{{
      hostStatus.error
    }}</span>
    <span v-if="item.name">{{ item.name }}: </span>{{ item.host }}
    <span v-if="hostStatus.delay"> [{{ hostStatus.delay }} ms]</span>
    <span class="float-end">
      [<a href="#" @click.prevent="paused = !paused">
        <img :src="paused ? '/play.png' : '/pause.png'" width="16" /> </a
      >] [<a href="#" @click.prevent="removeItem"><img src="/trash.png" width="16" /></a>]
    </span>
  </div>
</template>
