<template>
    <Head title="Создание игры" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Игра {{ uuid }}
            </h2>
        </template>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import BreezeButton from "@/Components/Button"
import { uuid } from 'vue-uuid'

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        Head,
    },
    props: ['uuid'],
    computed: {
        user() {
            return this.$page.props.auth.user
        }
    },
}
</script>
