<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage, Link, router } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'
import axios from 'axios'

const page = usePage()
const user = page.props.auth?.user ?? null
const role = user?.roles ?? null
const brands = ref(page.props.brands ?? [])
const products = ref(page.props.products ?? [])

// brand form
const brandForm = reactive({ 
    name: '', 
    logo_brand: null, 
})
const logoPreview = ref(null)
const logoInput = ref(null)
const isLoadingBrand = ref(false)
const brandErrors = reactive({})
const brandSuccessMessage = ref('')

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
async function submitBrand() {
    isLoadingBrand.value = true
    Object.keys(brandErrors).forEach(k => delete brandErrors[k])
    brandSuccessMessage.value = ''

    const fd = new FormData()
    fd.append('name', brandForm.name)
    if (brandForm.logo_brand) fd.append('logo_brand', brandForm.logo_brand)

    try {
        const res = await axios.post(route('seller.brands.store'), fd, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })

        if (res.status === 201 || res.status === 200) {
            const newBrand = res.data.brand ?? null

            if (newBrand) {
                if (!newBrand.logo_brand && res.data.logo_url) {
                    newBrand.logo_brand = res.data.logo_url.split('/').pop()
                }
                brands.value.unshift(newBrand)
            }

            // set success message
            brandSuccessMessage.value = res.data.success || 'Brand berhasil ditambahkan!'

            // reset form
            brandForm.name = ''
            brandForm.logo_brand = null
            logoPreview.value = null
            if (logoInput.value) logoInput.value.value = ''

            // hide success message after 3 seconds
            setTimeout(() => {
                brandSuccessMessage.value = ''
            }, 5000)
        }
    } catch (err) {
        if (err.response && err.response.status === 422) {
            const errors = err.response.data.errors || {}
            Object.keys(errors).forEach((k) => {
                brandErrors[k] = Array.isArray(errors[k]) ? errors[k][0] : errors[k]
            })
        } else {
            brandErrors.general = err.response?.data?.message || 'Terjadi kesalahan saat menyimpan brand'
        }
    } finally {
        isLoadingBrand.value = false
    }
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
const productSuccessMessage = ref('')
const isLoading = ref(false)

// submit product
function submitProduct() {
    isLoading.value = true
    Object.keys(productErrors).forEach(k => delete productErrors[k])
    productSuccessMessage.value = ''

    router.post(route('seller.products.store'), productForm, {
        onSuccess: () => {
            productSuccessMessage.value = 'Produk berhasil ditambahkan!'
            
            Object.keys(productForm).forEach((key) => {
                if (key !== 'user_id') productForm[key] = ''
            })
            router.reload({ only: ['products'] })

            // hide success message after 3 seconds
            setTimeout(() => {
                productSuccessMessage.value = ''
            }, 5000)
            
            isLoading.value = false
        },
        onError: (err) => {
            Object.assign(productErrors, err)
            productErrors.general = 'Terjadi kesalahan saat menyimpan produk'
            isLoading.value = false
        }
    })
}

// variant form
const variantForm = reactive({
    product_id: '',
    color: '',
    memori: '',
    price: '',
    stock: '',
})

const variantErrors = reactive({})
const isLoadingVariant = ref(false)
const imagePreviews = ref([])
const variantImages = ref([])
const variantImagesInput = ref(null)
const variantSuccessMessage = ref('')

// handle variant images
function handleVariantImages(e) {
    const files = Array.from(e.target.files || [])
    
    // append ke existing images
    variantImages.value = [...variantImages.value, ...files]

    // buat previews
    files.forEach(file => {
        const reader = new FileReader()
        reader.onload = ev => {
            imagePreviews.value.push(ev.target.result)
        }
        reader.readAsDataURL(file)
    })
    
    // reset input value agar bisa select file yang sama lagi
    if (variantImagesInput.value) variantImagesInput.value.value = ''
}

// remove image preview
function removeVariantImage(index) {
    variantImages.value.splice(index, 1)
    imagePreviews.value.splice(index, 1)
}

// submit variant
async function submitVariant() {
    isLoadingVariant.value = true
    Object.keys(variantErrors).forEach(k => delete variantErrors[k])
    variantSuccessMessage.value = ''

    const fd = new FormData()
    fd.append('product_id', variantForm.product_id)
    fd.append('color', variantForm.color)
    fd.append('memori', variantForm.memori)
    fd.append('price', variantForm.price)
    fd.append('stock', variantForm.stock)

    // append all images
    if (variantImages.value && variantImages.value.length > 0) {
        variantImages.value.forEach((img, idx) => {
            fd.append(`images[${idx}]`, img)
        })
    }

    try {
        const res = await axios.post(route('seller.variants.store'), fd, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })

        if (res.status === 201 || res.status === 200) {
            variantSuccessMessage.value = res.data.success || 'Varian berhasil ditambahkan!'
            
            // reset form
            variantForm.product_id = ''
            variantForm.color = ''
            variantForm.memori = ''
            variantForm.price = ''
            variantForm.stock = ''
            variantImages.value = []
            imagePreviews.value = []
            if (variantImagesInput.value) variantImagesInput.value.value = ''

            // hide success message after 3 seconds
            setTimeout(() => {
                variantSuccessMessage.value = ''
            }, 5000)
        }
    } catch (err) {
        console.error('Error:', err)
        if (err.response?.status === 422) {
            const errs = err.response.data.errors || {}
            Object.keys(errs).forEach(k => {
                variantErrors[k] = Array.isArray(errs[k]) ? errs[k][0] : errs[k]
            })
        } else {
            variantErrors.general = err.response?.data?.message || 'Terjadi kesalahan saat menyimpan varian'
        }
    } finally {
        isLoadingVariant.value = false
    }
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
                                
                                <!-- Success Message -->
                                <div v-if="brandSuccessMessage" class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-md text-xs mb-3">
                                    <i class="fas fa-check-circle mr-1"></i>{{ brandSuccessMessage }}
                                </div>

                                <!-- General Error -->
                                <div v-if="brandErrors.general" class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-md text-xs mb-3">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ brandErrors.general }}
                                </div>

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
                                        <input ref="logoInput" @change="handleLogoChange" type="file" name="logo_brand" accept="image/*" 
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
                                        <h3 class="text-lg font-semibold mb-1 text-gray-800">Daftar Brand</h3>
                                        <span class="inline-flex items-center justify-center mb-3 px-2.5 py-0.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ brands.length }} Brand yang tersedia untuk digunakan, atau tambah brand jika belum ada.
                                        </span>
                                        <div v-if="brands.length > 0" class="max-h-screen overflow-y-auto border rounded-lg p-3 bg-white">
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
                                
                                <!-- Success Message -->
                                <div v-if="productSuccessMessage" class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-md text-xs mb-3">
                                    <i class="fas fa-check-circle mr-1"></i>{{ productSuccessMessage }}
                                </div>

                                <!-- General Error -->
                                <div v-if="productErrors.general" class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-md text-xs mb-3">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ productErrors.general }}
                                </div>

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

                            <!-- Column Row Variant -->
                            <form @submit.prevent="submitVariant" class="bg-gray-100 shadow-sm border rounded-lg p-6 md:col-span-1">
                                <h3 class="text-lg text-center font-semibold mb-4">Varian Produk</h3>
                                
                                <!-- Success Message -->
                                <div v-if="variantSuccessMessage" class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-md text-xs mb-3">
                                    <i class="fas fa-check-circle mr-1"></i>{{ variantSuccessMessage }}
                                </div>

                                <!-- General Error -->
                                <div v-if="variantErrors.general" class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-md text-xs mb-3">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ variantErrors.general }}
                                </div>

                                <div class="grid gap-3">
                                    <!-- Pilih Produk -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                                        <select v-model="variantForm.product_id"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': variantErrors.product_id }">
                                            <option disabled value="">Pilih Product</option>
                                            <option v-for="p in products" :key="p.id" :value="p.id">
                                                {{ p.name }}
                                            </option>
                                        </select>
                                        <p v-if="variantErrors.product_id" class="text-red-500 text-xs mt-1">{{ variantErrors.product_id }}</p>
                                    </div>
                                    <!-- Warna Produk -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Varian Warna</label>
                                        <input v-model="variantForm.color" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': variantErrors.color }" />
                                        <p v-if="variantErrors.color" class="text-red-500 text-xs mt-1">{{ variantErrors.color }}</p>
                                    </div>
                                    <!-- Memori -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Penyimpanan (RAM & ROM)</label>
                                        <input v-model="variantForm.memori" type="text"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': variantErrors.memori }" />
                                        <p v-if="variantErrors.memori" class="text-red-500 text-xs mt-1">{{ variantErrors.memori }}</p>
                                    </div>
                                    <!-- Harga -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Produk</label>
                                        <input v-model.number="variantForm.price" type="number" step="0.01" min="0"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': variantErrors.price }"/>
                                        <p v-if="variantErrors.price" class="text-red-500 text-xs mt-1">{{ variantErrors.price }}</p>
                                    </div>
                                    <!-- Stok -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Produk</label>
                                        <input v-model.number="variantForm.stock" type="number" min="0"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': variantErrors.stock }"/>
                                        <p v-if="variantErrors.stock" class="text-red-500 text-xs mt-1">{{ variantErrors.stock }}</p>
                                    </div>
                                    <!-- Form Gambar Produk -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk (Multiple)</label>
                                        <input ref="variantImagesInput"
                                            type="file"
                                            multiple
                                            accept="image/*"
                                            @change="handleVariantImages"
                                            class="border rounded-md p-2 text-sm w-full"
                                            :class="{ 'border-red-500': variantErrors.images }" />
                                        <p class="text-gray-500 text-xs mt-1">Pilih 1 atau lebih gambar, bisa berkali-kali</p>
                                        <p v-if="variantErrors.images" class="text-red-500 text-xs mt-1">{{ variantErrors.images }}</p>
                                    </div>
                                    
                                    <!-- Jumlah Images -->
                                    <div v-if="imagePreviews.length > 0" class="bg-blue-50 border border-blue-200 rounded-md p-2">
                                        <p class="text-blue-700 text-xs font-medium">
                                            <i class="fas fa-images mr-1"></i>{{ imagePreviews.length }} Gambar terpilih
                                        </p>
                                    </div>

                                    <!-- Image Preview -->
                                    <div v-if="imagePreviews.length > 0" class="grid grid-cols-3 gap-2 mt-2 border rounded-md p-3 bg-white max-h-48 overflow-y-auto">
                                        <div v-for="(preview, idx) in imagePreviews" :key="idx" class="relative group">
                                            <img :src="preview" alt="Preview" class="h-24 w-24 object-cover rounded-md border border-gray-300" />
                                            <button type="button" @click="removeVariantImage(idx)" 
                                                class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700 text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit" :disabled="isLoadingVariant"
                                        class="flex items-center justify-center gap-2 px-2 py-2 bg-blue-600 mt-2 text-white text-sm rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="fas fa-save"></i>
                                        <span>{{ isLoadingVariant ? 'Menyimpan...' : 'Simpan Varian' }}</span>
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