<template>
    <v-app>
        <v-app-bar app color="primary">
            <v-toolbar-title>{{ siteName }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-for="item in menuItems" :key="item.id" :to="item.url" variant="text">
                {{ item.label }}
            </v-btn>
        </v-app-bar>

        <v-main>
            <router-view />
        </v-main>

        <v-footer app color="primary" class="text-center">
            <v-container>
                <div class="text-caption">© {{ new Date().getFullYear() }} {{ siteName }}. All rights reserved.</div>
            </v-container>
        </v-footer>
    </v-app>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            siteName: 'Business Website',
            menuItems: []
        };
    },
    async mounted() {
        await this.loadMenu();
        await this.loadSettings();
    },
    methods: {
        async loadMenu() {
            try {
                // This would load from API
                // const response = await axios.get('/api/public/menu');
                // this.menuItems = response.data;
                this.menuItems = [
                    { id: 1, label: 'Home', url: '/' },
                    { id: 2, label: 'About', url: '/about' },
                    { id: 3, label: 'Services', url: '/services' },
                    { id: 4, label: 'Contact', url: '/contact' },
                ];
            } catch (error) {
                console.error('Error loading menu:', error);
            }
        },
        async loadSettings() {
            try {
                const response = await axios.get('/api/public/settings');
                if (response.data.site_name) {
                    this.siteName = response.data.site_name;
                }
            } catch (error) {
                console.error('Error loading settings:', error);
            }
        }
    }
};
</script>

