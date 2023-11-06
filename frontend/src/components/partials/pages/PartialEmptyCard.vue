<template>
    <partial-card class="border-0 px-3 py-6 w-full">
        <template #body>
            <partial-empty-rows
                image="/public/empty-statuses/empty-cart.svg"
                message="سبد خرید شما خالی می‌باشد"
            />

            <div class="flex justify-center mt-3">
                <base-button
                    type="link"
                    :to="{name: 'search'}"
                    class="!text-black group flex items-center gap-2 border-2 px-6"
                >
                    <ArrowLongRightIcon class="w-6 h-6 group-hover:translate-x-1 transition"/>
                    <span class="mx-auto">ادامه خرید</span>
                </base-button>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {ArrowLongRightIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButton from "../../base/BaseButton.vue";
import PartialEmptyRows from "../PartialEmptyRows.vue";
import PartialCard from "../PartialCard.vue";

</script>

<style scoped>

</style>
