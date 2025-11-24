<template>
    <v-navigation-drawer v-model="localDrawer" location="right" temporary class="mobile-drawer">
        <div class="pa-6">
            <div class="d-flex align-center mb-8">
                <div class="logo-box mr-3">
                    <v-icon icon="mdi-flash" color="amber-accent-4" size="24"></v-icon>
                </div>
                <span class="text-h6 font-weight-black text-grey-darken-4">{{ siteName.toUpperCase() }}</span>
                <v-spacer></v-spacer>
                <v-btn icon="mdi-close" variant="text" @click="closeDrawer"></v-btn>
            </div>

            <v-list nav class="bg-transparent">
                <v-list-item v-for="item in menuItems" :key="item.id" :to="item.url" class="mb-2 rounded-lg"
                    active-class="bg-primary-lighten-5 text-primary">
                    <v-list-item-title class="font-weight-bold">{{ item.label }}</v-list-item-title>
                </v-list-item>
            </v-list>

            <div class="mt-8">
                <v-btn block color="primary" size="large" rounded="lg" :to="{ name: 'Contact' }"
                    class="elevation-4">
                    Get a Quote
                </v-btn>
            </div>
        </div>
    </v-navigation-drawer>
</template>

<script>
export default {
    name: 'MobileDrawer',
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        siteName: {
            type: String,
            required: true
        },
        menuItems: {
            type: Array,
            required: true
        }
    },
    emits: ['update:modelValue'],
    computed: {
        localDrawer: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },
    methods: {
        closeDrawer() {
            this.$emit('update:modelValue', false);
        }
    }
};
</script>

<style scoped>
.mobile-drawer {
    /* Add any specific mobile drawer styles here if needed */
}

.logo-box {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

