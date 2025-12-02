<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage, Link, router } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'

const page = usePage()
const user = page.props.auth?.user ?? null
const role = user?.roles ?? null
const brands = page.props.brands ?? []

// brand form
const brandForm = reactive({ 
    name: '', 
    logo_brand: null, 
})
const logoPreview = ref(null)
const isLoadingBrand = ref(false)
const brandErrors = reactive({})

// brand handlers
function handleLogoChange(e) {
    const file = e.target.files?.[0]
    if (!file) return
    brandForm.logo_brand = file
    const reader = new FileReader()
    reader.onload = (ev) => (logoPreview.value = ev.target.result)
    reader.readAsDataURL(file)
}

// brand submit
function submitBrand() {
    isLoadingBrand.value = true
    brandErrors.name = null
    brandErrors.logo_brand = null

    const fd = new FormData()
    fd.append('name', brandForm.name)
    if (brandForm.logo_brand) fd.append('logo_brand', brandForm.logo_brand)
    if (brandForm.user_id) fd.append('user_id', brandForm.user_id)

    router.post(route('seller.brands.store'), fd, {
        onSuccess: (page) => {
            const newBrand = page.props?.flash?.brand ?? null
            if (newBrand) brands.unshift(newBrand)
            brandForm.name = ''
            brandForm.logo_brand = null
            logoPreview.value = null
            isLoadingBrand.value = false
        },
        onError: (err) => {
            Object.assign(brandErrors, err || {})
            isLoadingBrand.value = false
        },
    })
}

// product form
const productForm = reactive({
    user_id: user?.id || null,
    brand_id: '',
    name: '',
    description: '',
    chipset: '',
    software: '',
    display: '',
    dimensi: '',
    camera: '',
    baterai: '',
    network: '',
    konektivitas: '',
})
const productErrors = reactive({})
const isLoading = ref(false)

// submit product
function submitProduct() {
    isLoading.value = true
    router.post(route('seller.products.store'), productForm, {
        onSuccess: () => {
            Object.keys(productForm).forEach((key) => {
                if (key !== 'user_id') productForm[key] = ''
            })
            isLoading.value = false
        },
        onError: (err) => {
            Object.assign(productErrors, err)
            isLoading.value = false
        }
    })
}
</script>

<template>
    <Head :title="`Dashboard ${role} — Tambah Produk`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Tambah Produk</h2>
                <Link :href="route('seller.products.index')" class="inline-flex items-center gap-1 px-2 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>
        </template>

        <div class="py-9">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <!-- Column Row Brand -->
                            <form @submit.prevent="submitBrand" class="bg-gray-100 shadow-sm border rounded-lg p-6 md:col-span-1">
                                <h3 class="text-lg text-center font-semibold mb-4">Tambah Brand</h3>
                                <div class="grid gap-3">
                                    <!-- Form Nama Brand -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Brand</label>
                                        <input v-model="brandForm.name" type="text" name="name" 
                                            class="border rounded-md p-2 text-sm w-full" 
                                            :class="{ 'border-red-500': brandErrors.name }" />
                                        <p v-if="brandErrors.name" class="text-red-500 text-xs mt-1">{{ brandErrors.name }}</p>
                                    </div>
                                    <!-- Form Logo Brand -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Brand</label>
                                        <input @change="handleLogoChange" type="file" name="logo_brand" accept="image/*" 
                                            class="border rounded-md p-2 text-sm w-full" 
                                            :class="{ 'border-red-500': brandErrors.logo_brand }" />
                                        <p v-if="brandErrors.logo_brand" class="text-red-500 text-xs mt-1">{{ brandErrors.logo_brand }}</p>
                                    </div>
                                    <div v-if="logoPreview" class="flex justify-center">
                                        <img :src="logoPreview" alt="Logo Preview" class="mt-2 h-24 w-24 object-cover rounded-md border-2 border-blue-300" />
                                    </div>
                                    <button type="submit" :disabled="isLoadingBrand" 
                                        class="flex items-center justify-center gap-2 px-3 py-2 mt-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="fas fa-save"></i>
                                        <span>{{ isLoadingBrand ? 'Menyimpan...' : 'Simpan Brand' }}</span>
                                    </button>

                                    <!-- Brand list -->
                                    <div class="mt-4">
                                        <h3 class="text-lg font-semibold mb-3 text-gray-800">Daftar Brand</h3>
                                        <div v-if="brands.length > 0" class="max-h-100 overflow-y-auto border rounded-lg p-3 bg-white">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div v-for="b in brands" :key="b.id" class="flex flex-col items-center p-4 border rounded-lg shadow-sm hover:shadow-md transition">
                                                    <div v-if="b.logo_brand" class="mb-2 flex items-center justify-center">
                                                        <img :src="`/storage/brands/${b.logo_brand}`" :alt="b.name" class="h-30 w-30 object-cover rounded-md" />
                                                    </div>
                                                    <div v-else class="mb-2">
                                                        <div class="h-30 w-30 bg-gray-200 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-900 text-center mt-2 truncate w-full">{{ b.name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                                            <p class="text-gray-500">Belum ada brand yang ditambahkan</p>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Column Row Product -->
                            <form @submit.prevent="submitProduct" class="bg-gray-100 shadow-sm border rounded-lg p-6 md:col-span-1">
                                <h3 class="text-lg text-center font-semibold mb-4">Tambah Produk</h3>
                                <div class="grid gap-3">
                                    <input type="hidden" name="user_id" :value="productForm.user_id" />
                                    <!-- Brand Produk -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand Produk</label>
                                        <select v-model="productForm.brand_id"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.brand_id }">
                                            <option disabled value="">Pilih Brand</option>
                                            <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                                        </select>
                                        <p v-if="productErrors.brand_id" class="text-red-500 text-xs mt-1">{{ productErrors.brand_id }}</p>
                                    </div>
                                    <!-- Nama Produk -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                                        <input v-model="productForm.name" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.name }" />
                                        <p v-if="productErrors.name" class="text-red-500 text-xs mt-1">{{ productErrors.name }}</p>
                                    </div>
                                    <!-- Chipset -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Chipset</label>
                                        <input v-model="productForm.chipset" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.chipset }" />
                                        <p v-if="productErrors.chipset" class="text-red-500 text-xs mt-1">{{ productErrors.chipset }}</p>
                                    </div>
                                    <!-- Deskripsi -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                                        <textarea v-model="productForm.description"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.description }"></textarea>
                                        <p v-if="productErrors.description" class="text-red-500 text-xs mt-1">{{ productErrors.description }}</p>
                                    </div>
                                    <!-- Software -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Software</label>
                                        <textarea v-model="productForm.software" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.software }"></textarea>
                                        <p v-if="productErrors.software" class="text-red-500 text-xs mt-1">{{ productErrors.software }}</p>
                                    </div>
                                    <!-- Display -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Display</label>
                                        <textarea v-model="productForm.display" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.display }"></textarea>
                                        <p v-if="productErrors.display" class="text-red-500 text-xs mt-1">{{ productErrors.display }}</p>
                                    </div>
                                    <!-- Dimensi -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Dimensi</label>
                                        <textarea v-model="productForm.dimensi" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.dimensi }"></textarea>
                                        <p v-if="productErrors.dimensi" class="text-red-500 text-xs mt-1">{{ productErrors.dimensi }}</p>
                                    </div>
                                    <!-- Kamera -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kamera</label>
                                        <textarea v-model="productForm.camera" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.camera }"></textarea>
                                        <p v-if="productErrors.camera" class="text-red-500 text-xs mt-1">{{ productErrors.camera }}</p>
                                    </div>
                                    <!-- Network -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Network</label>
                                        <textarea v-model="productForm.network" type="text"
                                        class="border rounded-md p-2 text-sm w-full"
                                        :class="{ 'border-red-500': productErrors.network }"></textarea>
                                        <p v-if="productErrors.network" class="text-red-500 text-xs mt-1">{{ productErrors.network }}</p>
                                    </div>
                                    <!-- Konektivitas -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Konektivitas</label>
                                        <textarea v-model="productForm.konektivitas" type="text"
                                        class="border rounded-md p-2 text-sm w-full"
                                        :class="{ 'border-red-500': productErrors.konektivitas }"></textarea>
                                        <p v-if="productErrors.konektivitas" class="text-red-500 text-xs mt-1">{{ productErrors.konektivitas }}</p>
                                    </div>
                                    <!-- Baterai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Baterai</label>
                                        <input v-model="productForm.baterai" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': productErrors.baterai }" />
                                        <p v-if="productErrors.baterai" class="text-red-500 text-xs mt-1">{{ productErrors.baterai }}</p>
                                    </div>

                                    <button type="submit" :disabled="isLoading"
                                        class="gap-1 px-2 py-2 bg-blue-600 mt-2 text-white text-sm rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="fas fa-save"></i>
                                        <span>{{ isLoading ? 'Menyimpan...' : 'Simpan Spesifikasi' }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="bg-gray-100 shadow-sm border rounded-lg p-6 mt-6">
                            <h3 class="text-lg text-center font-semibold mb-4">Detail Produk & Spesifikasi</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
