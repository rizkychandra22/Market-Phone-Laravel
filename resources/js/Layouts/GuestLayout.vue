<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center py-6">
        
        <!-- Logo -->
        <div class="mb-4">
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 text-gray-600" />
            </Link>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md bg-white shadow-md rounded-lg border p-6">
            <slot />
        </div>

    </div>
</template>
