<template>
    <div>
        <v-container>
            <h1 class="text-h4 mb-6">Our Products</h1>
            <v-row>
                <v-col v-for="product in products" :key="product.id" cols="12" md="4">
                    <v-card :to="`/products/${product.slug}`">
                        <v-img v-if="product.thumbnail" :src="product.thumbnail" height="200"></v-img>
                        <v-card-title>{{ product.title }}</v-card-title>
                        <v-card-text>{{ product.short_description }}</v-card-text>
                    </v-card>
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
            products: []
        };
    },
    async mounted() {
        await this.loadProducts();
    },
    methods: {
        async loadProducts() {
            try {
                const response = await axios.get('/api/public/products');
                this.products = response.data;
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }
    }
};
</script>

