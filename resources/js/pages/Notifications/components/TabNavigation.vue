<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<Props>();

interface Props {
    tabs: string[];
}

const emit = defineEmits<{
    (e: 'tabChange', value: string): void
}>();

const activeTab = ref<string>(props.tabs[0])

const selectTab = (tab: string) => {
    activeTab.value = tab;
    emit('tabChange', tab);
}
</script>

<template>
    <div class="border-b border-zinc-700 max-w-4xl m-auto">
        <nav class="flex gap-x-10 text-lg"aria-label="Tabs" >
            <button
                v-for="tab in tabs"
                :key="tab"
                @click="selectTab(tab)"
                :class="[
                    'border-b-2',
                    activeTab === tab
                    ? 'border-[#d3a240] text-[#d3a240]'
                    : 'border-transparent text-white'

                ]"
            >
                {{ tab }}
            </button>

        </nav>
    </div>
</template>