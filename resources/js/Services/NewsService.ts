import { router } from '@inertiajs/vue3'

export async function search(search: string) {
    router.get(`/?search=${search}`)
}

export default {
    search,
}
