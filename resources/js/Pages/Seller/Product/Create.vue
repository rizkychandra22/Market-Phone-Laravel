<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user ?? null;
const role = user?.roles ?? null;
</script>

<template>
    <Head :title="`Dashboard ${role} — Tambah Produk`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Tambah Produk
                </h2>

                <Link
                    :href="route('seller.products.index')"
                    class="inline-flex items-center gap-1 px-2 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>
        </template>

        <!-- CONTENT -->
        <div class="py-9">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!-- GRID 3 KOLOM -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <!-- CREATE BRAND -->
                            <div class="bg-gray-100 shadow-sm border rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Tambah Brand</h3>
                                <div class="grid gap-3">
                                    <div>
                                        <label>Nama Brand</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Nama Brand">
                                    </div>
                                    <div>
                                        <label>Logo Brand</label>
                                        <input type="file" class="border rounded-md p-2 text-sm w-full">
                                    </div>
                                    <button class="gap-1 px-2 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        <i class="fas fa-save"></i>
                                        <span>Simpan Brand</span>
                                    </button>
                                </div>
                            </div>

                            <!-- CREATE PRODUK -->
                            <div class="bg-gray-100 shadow-sm border rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Tambah Produk</h3>
                                <div class="grid gap-3">
                                    <div>
                                        <label>Nama Produk</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Nama Produk">
                                    </div>
                                    <div>
                                        <label>Nama Slug</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Nama Slug">
                                    </div>
                                    <div>
                                        <label>Deskripsi Produk</label>
                                        <!-- <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Nama Produk"> -->
                                        <textarea name="deskripsi_produk" class="border rounded-md p-2 text-sm w-full" placeholder="Deskripsi produk yang akan dijual" id="deskripsi_produk"></textarea>
                                    </div>
                                    <div>
                                        <label>Harga</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Harga">
                                    </div>
                                    <div>
                                        <label>Stok</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Stok">
                                    </div>
                                    <div>
                                        <label>Gambar Produk</label>
                                        <input type="file" class="border rounded-md p-2 text-sm w-full">
                                    </div>
                                    <button class="gap-1 px-2 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        <i class="fas fa-save"></i>
                                        <span>Simpan Produk</span>
                                    </button>
                                </div>
                            </div>

                            <!-- CREATE SPESIFIKASI -->
                            <div class="bg-gray-100 shadow-sm border rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Tambah Spesifikasi</h3>
                                <div class="grid gap-3">
                                    <div>
                                        <label>Prosessor</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Berat">
                                    </div>
                                    <div>
                                        <label>Memori</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Berat">
                                    </div>
                                    <div>
                                        <label>Display</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Ukuran">
                                    </div>
                                    <div>
                                        <label>Kamera</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Warna">
                                    </div>
                                    <div>
                                        <label>baterai</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Berat">
                                    </div>
                                    <div>
                                        <label>Software</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Ukuran">
                                    </div>
                                    <div>
                                        <label>Konektifitas</label>
                                        <input type="text" class="border rounded-md p-2 text-sm w-full" placeholder="Warna">
                                    </div>
                                    <button class="gap-1 px-2 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        <i class="fas fa-save"></i>
                                        <span>Simpan Spesifikasi</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- END GRID -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
