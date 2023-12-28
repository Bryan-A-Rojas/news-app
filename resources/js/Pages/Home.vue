<script lang="ts" setup>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import Input from '@/Components/Input.vue'
import { type GuardianArticle } from "@/Interfaces/GuardianArticle.ts"
import Article from '@/Components/Article.vue'
import { groupBy } from 'lodash'
import { router } from '@inertiajs/vue3'

type GuardianResponse = {
  articles: {
    response: {
      status: string
      total: number
      startIndex: number
      pageSize: number
      currentPage: number
      pages: number
      orderBy: string
      results: GuardianArticle[],
    }
  }
}

const props = defineProps<GuardianResponse>()

const articles: { results: GuardianArticle[] } = reactive({ results: [] })
const sectionedArticles = computed(() => {
  return groupBy(articles.results, (article: GuardianArticle) => article.sectionName);
})
const pinnedArticles: GuardianArticle[] = reactive([])

const toggleArticlePin = ({ id, pinned }: { id: string, pinned: boolean }) => {
  const articleIdx = articles.results.findIndex(a => a.id === id)
  const searchResultArticle = articles.results[articleIdx]

  if (searchResultArticle) {
    searchResultArticle.pinned = pinned
  }

  if (pinned) {
    pinnedArticles.push(articles.results[articleIdx]);
  } else {
    const pinnedArticleIdx = pinnedArticles.findIndex(a => a.id === id)

    pinnedArticles.splice(pinnedArticleIdx, 1)
  }
}

const mapArticlesResponse = (articles: GuardianArticle[]) => {
  return articles.map((a) => ({ ...a, pinned: false, }))
}

onMounted(() => {
  articles.results = mapArticlesResponse(props.articles.response.results)  
})

const loading = ref(false)
const search = ref('')
const submitSearch = async () => {
  if (search.value.trim() === '') return

  loading.value = true

  const response = await fetch('/api/search?' + new URLSearchParams({
    search: search.value,
  }))

  const responseJson = await response.json();

  articles.results = mapArticlesResponse(responseJson.response.results)

  loading.value = false
}
</script>

<template>
  <Head title="News App" />

  <Layout>
    <div class="px-6">
      <div class="flex items-center justify-end md:ml-auto mb-12">
        <Input
          name="search"
          v-model="search"
          placeholder="Search"
          class="w-full md:max-w-96"
          @keypress.enter="submitSearch"
          :disabled="loading"
        />
        <button @disabled="loading" class="ml-2" @click="submitSearch">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </button>
      </div>
  
      <div v-if="pinnedArticles.length > 0" class="mb-12">
        <h1 class="text-3xl font-bold mb-5">Pinned Articles</h1>
  
        <section class="grid md:grid-cols-4 gap-x-5 gap-y-5">
          <Article
            v-for="(article, idx) in pinnedArticles"
            v-bind="article"
            :key="idx"
            @update:pinned="toggleArticlePin"
          ></Article>
        </section>
      </div>

      <div class="w-full border-gray-500 border mb-12"></div>

      <div class="flex justify-center items-center" v-if="loading">
        <svg class="animate-spin -ml-1 mr-3 h-64 w-64 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <div
        v-else
        v-for="section in Object.keys(sectionedArticles)"
        class="mb-12"
      >
        <h1 class="text-3xl font-bold mb-5">{{ section }}</h1>
  
        <section class="grid md:grid-cols-4 gap-x-5 gap-y-5">
    
          <Article
            v-for="(article, idx) in sectionedArticles[section]"
            v-bind="article"
            :key="idx"
            @update:pinned="toggleArticlePin"
          ></Article>
        </section>
      </div>
    </div>
  </Layout>
</template>