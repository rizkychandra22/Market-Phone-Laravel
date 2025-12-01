<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, onBeforeUnmount } from 'vue';
import { Head } from '@inertiajs/vue3';

import $ from 'jquery';
import 'datatables.net-dt';
import 'datatables.net-responsive';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.css';

const sa = 'Super'

let table = null;

onMounted(() => {
    table = $('#users-table-seller').DataTable({
        ajax: {
            url: '/dashboard/users/sellers/data',
            dataSrc: 'data',
            error: function (xhr) {
                console.error('AJAX ERROR:', xhr.status, xhr.responseText);
            }
        },
        columns: [
            { 
                data: null,
                title: 'No',
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'name', title: 'Nama' },
            { data: 'username', title: 'Username', },
            { data: 'email', title: 'Email', },
            { data: 'phone', title: 'Telpon', },
            { data: 'address', title: 'Address', },
            { 
                data: 'created_at',
                title: 'Terdaftar',
                render: function (data) {
                    if (!data) return '-';
                    const d = new Date(data);
                    return d.toLocaleString();
                }
            }
        ],
        pageLength: 10,
        processing: true,
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Tidak ada data ditemukan",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            loadingRecords: "Memuat...",
        }
    });
});

onBeforeUnmount(() => {
    if (table) {
        table.destroy(true);
        table = null;
    }
});
</script>

<template>
    <Head :title="`Dashboard ${sa} — Data Seller`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Data User Seller
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table id="users-table-seller" class="stripe hover row-border w-full text-sm !important"></table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
