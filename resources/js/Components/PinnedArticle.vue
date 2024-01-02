<template>
  <article class="flex flex-col bg-gray-200 text-black rounded-lg overflow-hidden shadow-lg hover:shadow-2xl hover:scale-[101%] transition-transform ease-in-out">
    <img class="w-full object-contain" :src="props.thumbnail" />

    <section class="flex-1 px-4 py-4">
      <div class="flex justify-between mb-3">
        <div class="bg-blue-400 capitalize rounded-lg px-2 py-1 font-bold">
          {{ props.platform.replace(/_/g, ' ') }}
        </div>

        <div class="text-gray-500 capitalize">
          {{ props.section }}
        </div>
      </div>

      <div class="flex justify-between mb-3">
        <p class="text-gray-500 text-sm">Published: {{ publicationDate }}</p>

        <div @mouseover="hovered = true" @mouseleave="hovered = false">
          <svg
            v-if="hovered"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 text-red-500 cursor-pointer"
            @click="emits('update:pinned', { id: props.article_id, pinned: false })"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 1.664 1.664M21 21l-1.5-1.5m-5.485-1.242L12 17.25 4.5 21V8.742m.164-4.078a2.15 2.15 0 0 1 1.743-1.342 48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185V19.5M4.664 4.664 19.5 19.5" />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 hover:text-red-500 text-red-500 cursor-pointer"
            @click="() => {
              emits('update:pinned', { id: props.article_id, pinned: true })
              hovered = false
            }"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
          </svg>
        </div>
      </div>

      <h1 class="text-xl font-bold mb-3">{{ props.title }}</h1>

      <a
        class="text-blue-500 hover:text-blue-800 text-ellipsis block overflow-hidden whitespace-nowrap"
        :href="props.url"
        target="_blank"
      >{{ props.url }}</a>
    </section>
  </article>
</template>

<script lang="ts" setup>
import { format } from "date-fns";
import { type PinnedArticle } from "@/Interfaces/PinnedArticle.ts"

const props = defineProps<PinnedArticle>()
const emits = defineEmits(['update:pinned'])

const publicationDate = format(new Date(props.publication_date), "dd/MM/yyyy")

const hovered = ref(false)
</script>
