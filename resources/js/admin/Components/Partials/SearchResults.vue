<template>
    <div
        class="absolute top-full bg-white-secondary w-full max-h-72 overflow-y-auto z-50"
        v-click-away="onClickAway"
    >
        <div class="border-t border-white mb-2"></div>

        <ul class="pb-2">
            <li
                v-if="type === 'venues'"
                v-for="(option, index) in options"
                class="hover:bg-gray-50 flex items-center"

            >
                <button
                    class="py-2 px-4 w-full flex flex-col"
                    type="button"
                    @click="$emit('selectOption', option.id)"
                >
                    <span class="text-left font-bold">{{ option.name }}</span>
                    <span class="text-sm text-left text-gray-400"
                    >{{ option.zipcode }} {{ option.city }}</span
                    >
                </button>
                <Link
                    :class="'hover:text-primary w-6 h-6 p-1'"
                    :href="$route('admin::venues.edit', { venue: option.id })"
                >
                    <svg
                        class="h-full w-full"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z"
                        ></path>
                    </svg>
                </Link>

            </li>
                <li
                    v-if="type==='artists'"
                    v-for="(option, index) in options"
                    class=""
                >
                    <button
                        class="flex items-center w-full gap-3.5 py-3 hover:bg-gray-50 px-4"
                        type="button"
                        @click="$emit('selectOption', option)"

                    >
                        <CircleImage
                            :artist="option"
                            size="small"
                        />
                        {{ option.name }}
                    </button>
                </li>
            </ul>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";
import CircleImage from "./CircleImage";
import {directive} from "vue3-click-away";

export default {
    components: {
        CircleImage,
        Link,

    },
    props: {
        type: String,
        options: Object,
    },
    methods: {
        onClickAway(event) {

        },
    },
    directives: {
        ClickAway: directive,
    },
};
</script>
