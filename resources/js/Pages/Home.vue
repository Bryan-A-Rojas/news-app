<script lang="ts" setup>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import Input from '@/Components/Input.vue'
import { type GuardianArticle } from "@/Interfaces/GuardianArticle.ts"
import { type PinnedArticle } from "@/Interfaces/PinnedArticle.ts"
import Article from '@/Components/Article.vue'
import PinnedArticleCard from '@/Components/PinnedArticle.vue'
import { groupBy } from 'lodash'
import NewsService from '@/Services/NewsService.ts'
import PinnedArticleService from '@/Services/PinnedArticleService.ts'

type Props = {
  pinnedArticles: PinnedArticle[]
  articles: {
    response: {
      status: string
      total: number
      startIndex: number
      pageSize: number
      currentPage: number
      pages: number
      orderBy: string
      results: GuardianArticle[]
    }
  }
  search?: string
}

const props = defineProps<Props>()

const articles: { results: GuardianArticle[] } = reactive({ results: [] })
const sectionedArticles = computed(() => {
  return groupBy(articles.results, (article: GuardianArticle) => article.sectionName);
})
const pinnedArticles: PinnedArticle[] = reactive([])

const toggleArticlePin = async ({ id, pinned }: { id: string, pinned: boolean }) => {
  const articleIdx = articles.results.findIndex(a => a.id === id)
  const searchResultArticle = articles.results[articleIdx]

  if (searchResultArticle) {
    searchResultArticle.pinned = pinned
  }

  if (pinned) {
    const pinnedArticle = {
        article_id: searchResultArticle.id,
        title: searchResultArticle.webTitle,
        url: searchResultArticle.webUrl,
        section: searchResultArticle.sectionName,
        platform: 'the_guardian',
        publication_date: searchResultArticle.webPublicationDate,
        thumbnail: searchResultArticle.fields.thumbnail,
    }

    pinnedArticles.push(pinnedArticle);

    articles.results = mapArticlesResponse(props.articles.response.results)

    const article = await PinnedArticleService.pin(pinnedArticle)

    const _article = pinnedArticles.find(a => a.article_id === searchResultArticle.id)

    if (_article) {
        _article.id = article.id
    }
  } else {
    const pinnedArticleIdx = pinnedArticles.findIndex(a => a.article_id === id)
    const pinnedArticleId = pinnedArticles[pinnedArticleIdx].id

    pinnedArticles.splice(pinnedArticleIdx, 1)

    articles.results = mapArticlesResponse(props.articles.response.results)

    await PinnedArticleService.unpin(String(pinnedArticleId))
  }
}

const mapArticlesResponse = (articles: GuardianArticle[]) => {
  return articles.map((a) => ({
    ...a,
    pinned: pinnedArticles.some((p) => p.article_id === a.id),
  }))
}

onMounted(() => {
  pinnedArticles.push(...props.pinnedArticles)
  articles.results = mapArticlesResponse(props.articles.response.results)
})

const search = ref(props.search)
const submitSearch = async () => {
  if (search.value.trim() === '') return

  NewsService.search(search.value);
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
        />
        <button class="ml-2" @click="submitSearch">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </button>
      </div>

      <div v-if="pinnedArticles.length > 0" class="mb-12">
        <h1 class="text-3xl font-bold mb-5">Pinned Articles</h1>

        <section class="grid md:grid-cols-4 gap-x-5 gap-y-5">
          <PinnedArticleCard
            v-for="(article, idx) in pinnedArticles"
            v-bind="article"
            :key="idx"
            @update:pinned="toggleArticlePin"
          ></PinnedArticleCard>
        </section>
      </div>

      <div class="w-full border-gray-500 border mb-12"></div>

      <div
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
