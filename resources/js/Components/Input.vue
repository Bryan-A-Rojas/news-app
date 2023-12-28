<template>
  <div>
    <label v-if="props.label" :for="name" class="block text-sm font-medium leading-6 mb-2">{{ props.label }}</label>
    <div class="relative rounded-md shadow-sm">
      <input
        type="text"
        v-model="value"
        :name="name"
        :id="name"
        :class="[
          'block w-full rounded-md border-0',
          'py-1.5 pl-7 pr-20',
          'text-gray-900 ring-1 ring-inset ring-gray-300',
          'placeholder:text-gray-400',
          'focus:ring-2 focus:ring-inset focus:ring-indigo-600',
          'sm:text-sm sm:leading-6'
        ]"
        :placeholder="props.placeholder"
        :disabled="props.disabled"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
interface Props {
  label?: string
  name: string
  modelValue?: string
  placeholder?: string
  disabled?: boolean
}

const props = defineProps<Props>()
const emits = defineEmits(['update:modelValue'])

const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emits('update:modelValue', value)
  }
})
</script>