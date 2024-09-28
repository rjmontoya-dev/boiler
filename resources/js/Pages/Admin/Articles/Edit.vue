<template>
    <AppLayout title="Customer Create">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Article / Edit
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 ">
          <div >
            <!-- Breadcrumb -->
            <div class="py-2 px-4">
            <nav class="text-sm font-semibold text-gray-500" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <router-link to="/accounts" class="text-gray-500 hover:text-gray-700">
                    Articles
                    </router-link>
                    <svg
                    class="fill-current w-3 h-3 mx-3"
                    viewBox="0 0 320 512"
                    xmlns="http://www.w3.org/2000/svg"
                    >
                    <path
                        d="M285.476 272.971l-194.343 194.343c-9.373 9.373-24.569 9.373-33.941 0l-22.628-22.628c-9.373-9.373-9.373-24.569 0-33.941L198.515 256 34.564 92.049c-9.373-9.373-9.373-24.569 0-33.941l22.628-22.628c9.373-9.373 24.569-9.373 33.941 0l194.343 194.343c9.373 9.372 9.373 24.568.001 33.948z"
                    />
                    </svg>
                </li>
                <li>
                    <span class="text-gray-700" aria-current="page">Edit</span>
                </li>
                </ol>
            </nav>
            </div>

            <!-- Form -->
            <div class="max-w-lg mx-auto">
              <form action="#" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                  <div class="bg-white py-6 px-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Customer Information</h3>

                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-3">
                        <label for="code" class="block text-sm font-medium text-gray-700">Code *</label>
                        <input type="text" name="code" id="code" autocomplete="given-code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      </div>

                      <div class="col-span-6 sm:col-span-3">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username *</label>
                        <input type="text" name="username" id="username" autocomplete="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      </div>

                      <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" autocomplete="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      </div>

                      <div class="col-span-6 sm:col-span-3">
                        <label for="email-address" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email-address" id="email-address" autocomplete="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      </div>

                      <div class="col-span-6 sm:col-span-3">
                        <label for="mobile-number" class="block text-sm font-medium text-gray-700">Mobile Number *</label>
                        <input type="text" name="mobile-number" id="mobile-number" autocomplete="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      </div>
                    </div>
                  </div>

                  <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                      Update
                    </button>
                    <button type="button" class="inline-flex justify-center py-2 px-4 ml-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                      Cancel
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  <script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import {ref } from 'vue';
  const props = defineProps({
    item: Object,
  });
  console.log(props);
  const form = ref({
    customer_type: null,
    code: null,
    username: null,
    full_name: null,
    email: null,
    mobile_number: null
  });

  </script>
