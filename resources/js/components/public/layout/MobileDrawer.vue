<template>
    <v-navigation-drawer v-model="localDrawer" location="right" temporary class="mobile-drawer">
        <div class="pa-6">
            <div class="d-flex align-center mb-8">
                <div class="logo-box mr-3" v-if="logo">
                    <v-img :src="logo" alt="Logo" cover class="logo-image rounded-logo"></v-img>
                </div>
                <div class="logo-box mr-3" v-else>
                    <v-icon icon="mdi-flash" color="amber-accent-4" size="24"></v-icon>
                </div>
                <span class="text-h6 font-weight-black text-grey-darken-4">{{ siteName.toUpperCase() }}</span>
                <v-spacer></v-spacer>
                <v-btn icon="mdi-close" variant="text" @click="closeDrawer"></v-btn>
            </div>

            <v-list nav class="bg-transparent">
                <v-list-item v-for="item in menuItems" :key="item.id" :to="item.url" class="mb-2 rounded-lg"
                    :class="{ 'bg-primary-lighten-5 text-primary': isActiveRoute(item.url) }">
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
        },
        logo: {
            type: String,
            default: null
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
        },
        isActiveRoute(url) {
            const currentPath = this.$route.path;
            // For home route, only match exactly "/"
            if (url === '/') {
                return currentPath === '/';
            }
            // For other routes, match if current path starts with the route
            return currentPath.startsWith(url);
        }
    }
};
</script>

<style scoped>
.mobile-drawer {
    /* Add any specific mobile drawer styles here if needed */
}

/* logo-box styles moved to app.css */

.logo-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rounded-logo {
    border-radius: 8px !important;
    overflow: hidden;
}

/* Responsive Styles */
@media (max-width: 600px) {
    .mobile-drawer {
        width: 280px !important;
    }

    .logo-box {
        width: 36px;
        height: 36px;
    }

    .logo-box .v-icon {
        font-size: 20px !important;
    }

    .text-h6 {
        font-size: 0.875rem !important;
    }
}
</style>

