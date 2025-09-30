<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const props = defineProps({
  textButton: {
    type: String,
    required: true,
  },

  linkButton: {
    type: String,
    required: true,
  },

  method: {
    type: String,
    required: false,
    deafult: 'get',
    validator: (value: string) => ['get', 'post'].includes(value),
  },

  hasParameter: {
    type: Boolean,
    required: false,
    default: false,
  },
});

function methodChange() { // TO DO: improve this later
  if (props.method === 'post') {
    if (props.hasParameter) {
      router.post(props.linkButton);
    } else {
      router.post(route(props.linkButton));
    }
  } else {
    if (props.hasParameter) {
      router.get(props.linkButton);
    } else {
      router.get(route(props.linkButton));
    }
  }
}

</script>

<template>
  <div class="flex flex-row justify-center">
    <button
      class="p-2 rounded-xl border border-sky-500 text-sky-500 bg-[#ffffff15] flex items-center flex-row-reverse gap-x-1 btn-shine"
      @click="methodChange()">
      <span>{{ props.textButton }}</span>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3-3-3 3m0 0 3 3m-3-3H21" />
      </svg>
    </button>
  </div>
</template>

<style scoped>
.btn-shine:hover {
  cursor: pointer;
}

.btn-shine {
  position: relative;
  overflow: hidden;
}

.btn-shine::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;


  background-image: linear-gradient(110deg,
      transparent 20%,
      rgba(255, 255, 255, 0.4) 50%,
      transparent 80%);

  transform: translateX(-100%);


  transition: transform 500ms ease-in-out;
}

.btn-shine:hover::before {

  transform: translateX(100%);
}
</style>