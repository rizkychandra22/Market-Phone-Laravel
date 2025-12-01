<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user ?? null;
const role = user?.roles ?? null;
</script>

<template>
    <Head :title="`Home — Dashboard ${role}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 py-1">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Selamat datang, {{ user?.name ?? 'User' }}! <br>
                        Anda berada di dashboard Seller.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
