<template>
    <AppLayout title="Customer Create">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Article / Create
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
                    <span class="text-gray-700" aria-current="page">Create</span>
                </li>
                </ol>
            </nav>
            </div>

            <!-- Form -->
            <div class="max-w-lg mx-auto">
              <form  @submit.prevent="submit">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                  <div class="bg-white py-6 px-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Customer Information</h3>

                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-3">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title  *</label>
                        <input v-model="form.title" type="text" name="title" id="title" autocomplete="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      </div>
                    </div>
                  </div>
                  <div class="col-span-full">
                    <CKEditor v-model="form.content" />
                </div>
                  <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button  type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                      Submit
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
  <script lang='ts' setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import {ref } from 'vue';
  import CKEditor from '@/Components/Input/CKEditor.vue';
import submitForm from '../../../Helpers/submitForm';
import { Form } from '../../../Interface/Data/Form';
import { useForm, usePage } from '@inertiajs/vue3';


const { form, submit } = submitForm(
    {
        title: '',
        content: '',
    } as Form,
    route("admin.article.store")
);

  </script>
