<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  notification: App.Data.NotificationData;
}>();
</script>

<template>
  <div
    class="flex flex-col md:flex-row justify-between gap-5 bg-[#0e1423] border-2 border-[#b3925c] h-auto w-11/12 md:w-full max-w-4xl p-4 md:pl-8 md:pr-15 m-auto mb-6 text-white rounded-lg">

    <div class="flex flex-col gap-2 w-full">
      <strong class="font-bold md:mb-2 text-[#b3925c]">Endereços:</strong>

      <ul class="flex flex-col gap-3 w-full">
        <li v-for="address in notification.addresses" :key="address.id">
          <Link
            :href="route('notifications.diligence.show', {
              notification: notification.protocol,
              address: address.id
            })"
            class="block bg-slate-800 rounded-lg hover:bg-[#3a4050] border border-gray-700 text-left text-sm text-white px-4 py-3"
          >
            {{ address.address }}
          </Link>
        </li>

        <li v-if="!notification.addresses.length" class="italic text-gray-400">
          Nenhum endereço cadastrado
        </li>
      </ul>
    </div>
  </div>
</template>
