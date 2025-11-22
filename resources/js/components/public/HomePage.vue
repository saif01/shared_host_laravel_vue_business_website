<template>
    <div>
        <v-container>
            <!-- Hero Section -->
            <v-row class="mb-12">
                <v-col cols="12">
                    <v-card class="hero-section" height="400">
                        <v-card-text class="d-flex align-center justify-center h-100">
                            <div class="text-center">
                                <h1 class="text-h2 mb-4">{{ heroTitle }}</h1>
                                <p class="text-h6 mb-6">{{ heroSubtitle }}</p>
                                <v-btn size="large" color="primary" :to="{ name: 'Contact' }">Contact Us</v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Services Section -->
            <v-row v-if="services.length > 0" class="mb-12">
                <v-col cols="12">
                    <h2 class="text-h4 mb-6">Our Services</h2>
                    <v-row>
                        <v-col v-for="service in services" :key="service.id" cols="12" md="4">
                            <v-card :to="`/services/${service.slug}`">
                                <v-card-title>{{ service.title }}</v-card-title>
                                <v-card-text>{{ service.short_description }}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>

            <!-- Products Section -->
            <v-row v-if="products.length > 0" class="mb-12">
                <v-col cols="12">
                    <h2 class="text-h4 mb-6">Featured Products</h2>
                    <v-row>
                        <v-col v-for="product in products" :key="product.id" cols="12" md="4">
                            <v-card :to="`/products/${product.slug}`">
                                <v-img v-if="product.thumbnail" :src="product.thumbnail" height="200"></v-img>
                                <v-card-title>{{ product.title }}</v-card-title>
                                <v-card-text>{{ product.short_description }}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            heroTitle: 'Welcome to Our Business',
            heroSubtitle: 'We provide excellent services and products',
            services: [],
            products: []
        };
    },
    async mounted() {
        await this.loadHomeData();
    },
    methods: {
        async loadHomeData() {
            try {
                const response = await axios.get('/api/public/home');
                if (response.data.homePage) {
                    this.heroTitle = response.data.homePage.title || this.heroTitle;
                    this.heroSubtitle = response.data.homePage.content || this.heroSubtitle;
                }
                if (response.data.services) {
                    this.services = response.data.services;
                }
                if (response.data.products) {
                    this.products = response.data.products;
                }
            } catch (error) {
                console.error('Error loading home data:', error);
            }
        }
    }
};
</script>

<style scoped>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
</style>

