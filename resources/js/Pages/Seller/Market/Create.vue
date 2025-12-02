<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user ?? null;
const role = user?.roles ?? null;  
</script>

<template>
    <Head :title="`Dashboard ${role} — Tambah Penjualan`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Tambah Penjualan
                </h2>

                <Link
                    :href="route('seller.market.index')"
                    class="inline-flex items-center gap-1 px-2 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>
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
