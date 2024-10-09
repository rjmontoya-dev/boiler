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
            <form-section
                :form="form"
            >
                <action-button @click="submit()">
                    Submit
                </action-button>
                <cancel-button
                    :route-link="route('admin.article.index')"
                />
            </form-section>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  <script lang='ts' setup>
  import {ref } from 'vue';
import submitForm from '../../../Helpers/submitForm';
import { Form } from '../../../Interface/Data/Form';
import { useForm, usePage } from '@inertiajs/vue3';
import FormSection from './FormSection.vue';

const { form, submit } = submitForm(
    {
        title: '',
        content: '',
        image:'',
    } as Form,
    route("admin.article.store")
);

  </script>
