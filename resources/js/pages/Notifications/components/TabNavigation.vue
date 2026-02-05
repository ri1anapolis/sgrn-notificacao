<script setup lang="ts">
const props = defineProps<{
    tabs: string[];
    diligencesCount: number;
    activeTab: string;
    isClosed?: boolean;
}>();

const emit = defineEmits<{
    (e: 'tabChange', value: string): void;
}>();

const isTabDisabled = (index: number) => {
    return index > props.diligencesCount;
};

const isClosedAndPending = (index: number) => {
    return props.isClosed && index >= props.diligencesCount;
};

const selectTab = (tab: string, index: number) => {
    if (isTabDisabled(index)) {
        return;
    }
    emit('tabChange', tab);
};
</script>

<template>
    <div class="mx-auto w-11/12 md:w-4xl border-b border-zinc-700">
        <nav class="flex gap-x-10 text-lg" aria-label="Tabs">
            <button
                v-for="(tab, index) in tabs"
                :key="tab"
                @click="selectTab(tab, index)"
                :disabled="isTabDisabled(index)"
                :class="[
                    'border-b-2 py-2 px-1 font-bold transition-colors duration-200',

                    isClosedAndPending(index)
                        ? 'text-red-500 border-transparent cursor-not-allowed hover:text-red-400'

                        : isTabDisabled(index)
                            ? 'text-red-500 border-transparent cursor-not-allowed hover:text-red-400'

                            : index === diligencesCount
                                ? {
                                    'text-[#d3a240] hover:text-[#FEE33E] hover:cursor-pointer': true,
                                    'border-[#d3a240]': activeTab === tab,
                                    'border-transparent': activeTab !== tab,
                                }

                                : {
                                    'text-green-500 hover:text-green-200 hover:cursor-pointer': true,
                                    'border-green-500': activeTab === tab,
                                    'border-transparent': activeTab !== tab,
                                }
            ]">
                {{ tab }}
            </button>
        </nav>
    </div>
</template>
