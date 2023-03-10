<template>
    <DefaultLayout currentRoute="venues">
        <form class="flex flex-col min-w-[350px]  max-w-6xl h-full mx-auto" @submit.prevent="submit">
            <div class="flex justify-between">
                <div class="flex items-center 2xl:mb-12 mb-5 ">
                    <h2 class="font-bold text-2xl capitalize">{{ venue.name }}</h2> <span>&nbsp-&nbsp </span>
                    <h3 class="text-2xl capitalize">{{ venue.city }} </h3>
                </div>
                <div v-click-away="onClickAway" class="relative">
                    <button class="hover:bg-gray-100 rounded py-1" type="button" @click="open = !open">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"
                                stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div v-show="open"
                         class="z-20 absolute right-0 border rounded p-1 whitespace-nowrap flex flex-col gap-2 text-left">
                        <button class="text-left hover:bg-gray-100 w-full px-2 py-1 rounded text-sm">Option 1</button>
                        <button class="text-left hover:bg-gray-100 w-full px-2 py-1 rounded text-sm">Option 2</button>
                        <button class="text-left hover:bg-gray-100 w-full px-2 py-1 rounded text-red-700 text-sm"
                                type="button" @click="openModal = true">
                            Delete
                        </button>
                        <Teleport to="#app">
                            <Modal
                                v-if="openModal"
                                :text="'Are you sure you want to delete'"
                                :type="'event'"
                                @close-modal="openModal = false"
                                @action-modal="deleteEvent"
                            />
                        </Teleport>
                    </div>
                </div>
            </div>

            <div class="relative mt-[-10px] z-10 w-full  min-h-[450px] 2xl:h-[550px] bg-white rounded-md shadow-[7px_7px_33px_-10px_rgba(0,0,0,0.25)]">
                <div class="h-full w-full grid grid-cols-3 gap-12 flex-col p-6">
                    <div class="col-span-3 flex flex-col gap-6 2xl:gap-7">
                        <div class="flex flex-col">
                            <label class="text-xs text-g mb-2">Name</label>
                            <Input v-model="form.name"/>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs text-g mb-2">Address</label>
                            <Input v-model="form.address"/>
                        </div>
                        <div class="grid grid-cols-4 gap-5">
                            <div class="flex flex-col col-span-1">
                                <label class="text-xs text-g mb-2">Zipcode</label>
                                <Input v-model="form.zipcode"/>
                            </div>
                            <div class="flex flex-col col-span-3">
                                <label class="text-xs text-g mb-2">City</label>
                                <Input v-model="form.city"/>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs text-g mb-2">Country</label>
                            <Select v-model="form.country" :options="countries" class="w-2/12"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-8 mt-8">
                <LinkBtn :type="'back'" to="admin::venues.index"/>

                <Btn :text="'save'" :type="'submit'"/>
            </div>

        </form>
    </DefaultLayout>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";
import DefaultLayout from "../../Layouts/DefaultLayout";
import Btn from "../../Components/Partials/Btn";
import Input from "../../Components/Partials/Input";
import Select from "../../Components/Partials/Select";
import {directive} from "vue3-click-away";
import Modal from "../../Components/Modal";
import LinkBtn from "../../Components/Partials/LinkBtn";
import Tab from "../../Components/Partials/Tab";
import TextArea from "../../Components/Partials/TextArea";

export default {
    // included child components
    components: {
        TextArea,
        Tab,
        LinkBtn,
        Modal,
        Select,
        Input,
        Btn,
        DefaultLayout,
        Link,
    },
    // passed from controller
    props: {
        venue: Object,
        countries: Object,
    },
    // custom set
    data() {
        return {
            form: this.$inertia.form({
                name: this.venue.name,
                address: this.venue.address,
                zipcode: this.venue.zipcode,
                city: this.venue.city,
                country: this.venue.country,
            }),

            open: false,
            openModal: false,
        };
    },

    // methods
    methods: {
        updateActiveTab(e) {
            this.activeTab = e;
        },

        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    _method: "patch",
                }))
                .post(
                    this.$route("admin::venues.update", {
                        venue: this.venue.id,
                    })
                );
        },

        deleteEvent() {
            this.$inertia.delete(
                this.$route("admin::events.destroy", {
                    user: this.user.id,
                })
            );
        },
        onClickAway(event) {
            this.open = false;
        },
    },
    directives: {
        ClickAway: directive,
    },


};
</script>
