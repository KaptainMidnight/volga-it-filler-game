<template>
    <Head title="Создание игры" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Создание игры
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between">
                            <div>
                                <img src="storage/user.png" alt="">
                                <p class="text-center font-xl font-bold mt-5">{{ user.name }}</p>
                            </div>
                            <div>
                                <img src="storage/comp.png" alt="">
                                <p class="text-center font-xl font-bold mt-5">Компьютер</p>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-center">
                            <BreezeButton>
                                <a @click="createGame" :href="'/play/'+uuid">Создать</a>
                            </BreezeButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    data() {
      return {
          uuid: uuid.v1(),
          csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    },
    computed: {
        user() {
            return this.$page.props.auth.user
        }
    },
    methods: {
        createGame() {
            this.$inertia.post('/api/game', {
                uuid: this.uuid,
                user_id: this.user.id,
                _token: this.csrf
            })
        }
    }
}
</script>
