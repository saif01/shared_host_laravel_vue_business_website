<template>
    <div class="products-page">
        <!-- Hero Section -->
        <section class="page-hero position-relative d-flex align-center justify-center text-center">
            <div class="hero-bg-gradient"></div>
            <div class="hero-pattern"></div>
            <v-container class="position-relative z-index-2">
                <v-fade-transition appear>
                    <div>
                        <div class="glass-pill d-inline-flex align-center px-4 py-2 rounded-pill mb-6">
                            <v-icon icon="mdi-package-variant-closed" color="amber-accent-4" size="small" class="mr-2"></v-icon>
                            <span class="text-subtitle-2 font-weight-bold tracking-wide text-white">PREMIUM CATALOG</span>
                        </div>
                        <h1 class="text-h3 text-md-h2 font-weight-black text-white mb-6">Power Products</h1>
                        <p class="text-h6 text-white opacity-80 mw-800 mx-auto font-weight-light">
                            Explore our wide range of high-quality power backup and management systems designed for reliability.
                        </p>
                    </div>
                </v-fade-transition>
            </v-container>
        </section>

        <!-- Products Section -->
        <section class="py-16 bg-grey-lighten-5">
            <v-container>
                <v-row>
                    <!-- Sidebar Filters (Desktop) -->
                    <v-col cols="12" md="3" class="d-none d-md-block">
                        <v-card class="rounded-xl pa-6 elevation-2 sticky-top" style="top: 100px;">
                            <h3 class="text-h6 font-weight-bold mb-4 text-grey-darken-3">Categories</h3>
                            <v-list density="compact" class="pa-0">
                                <v-list-item 
                                    v-for="category in categories" 
                                    :key="category.id"
                                    :value="category.id"
                                    active-color="primary"
                                    rounded="lg"
                                    class="mb-1"
                                    @click="activeCategory = category.id"
                                    :active="activeCategory === category.id"
                                >
                                    <template v-slot:prepend>
                                        <v-icon :icon="category.icon" size="small" class="mr-2"></v-icon>
                                    </template>
                                    <v-list-item-title class="font-weight-medium">{{ category.name }}</v-list-item-title>
                                    <template v-slot:append>
                                        <v-chip size="x-small" variant="flat" color="grey-lighten-3">{{ category.count }}</v-chip>
                                    </template>
                                </v-list-item>
                            </v-list>

                            <v-divider class="my-6"></v-divider>

                            <h3 class="text-h6 font-weight-bold mb-4 text-grey-darken-3">Price Range</h3>
                            <v-range-slider
                                v-model="priceRange"
                                :min="0"
                                :max="5000"
                                step="100"
                                color="primary"
                                thumb-label="always"
                                class="mt-8"
                            ></v-range-slider>
                        </v-card>
                    </v-col>

                    <!-- Mobile Filter Toggle -->
                    <v-col cols="12" class="d-md-none mb-4">
                        <v-btn block variant="outlined" color="primary" prepend-icon="mdi-filter-variant">
                            Filter Products
                        </v-btn>
                    </v-col>

                    <!-- Product Grid -->
                    <v-col cols="12" md="9">
                        <div class="d-flex justify-space-between align-center mb-6">
                            <div class="text-subtitle-1 font-weight-bold text-grey-darken-2">
                                Showing {{ filteredProducts.length }} Results
                            </div>
                            <div style="width: 200px;">
                                <v-select
                                    v-model="sortBy"
                                    :items="['Newest', 'Price: Low to High', 'Price: High to Low']"
                                    label="Sort By"
                                    variant="outlined"
                                    density="compact"
                                    hide-details
                                    bg-color="white"
                                ></v-select>
                            </div>
                        </div>

                        <v-row>
                            <v-col v-for="product in filteredProducts" :key="product.id" cols="12" sm="6" lg="4">
                                <v-hover v-slot="{ isHovering, props }">
                                    <v-card
                                        v-bind="props"
                                        :elevation="isHovering ? 12 : 0"
                                        class="h-100 product-card-modern bg-white rounded-xl overflow-hidden border-thin"
                                        :to="`/products/${product.slug}`"
                                    >
                                        <div class="img-wrapper overflow-hidden position-relative" style="height: 240px;">
                                            <v-chip
                                                v-if="product.isNew"
                                                color="amber-accent-4"
                                                class="position-absolute top-0 left-0 ma-4 z-index-2 font-weight-bold"
                                                size="small"
                                            >
                                                NEW
                                            </v-chip>
                                            <v-img
                                                :src="product.thumbnail || 'https://via.placeholder.com/400x300?text=Product'"
                                                height="100%"
                                                cover
                                                class="product-img transition-transform"
                                                :class="{ 'scale-110': isHovering }"
                                            >
                                                <template v-slot:placeholder>
                                                    <div class="d-flex align-center justify-center fill-height bg-grey-lighten-3">
                                                        <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                                    </div>
                                                </template>
                                            </v-img>
                                            <div class="overlay-actions d-flex align-center justify-center">
                                                <v-btn icon="mdi-eye" color="white" variant="flat" class="mr-2 hover-scale"></v-btn>
                                                <v-btn icon="mdi-cart" color="primary" variant="flat" class="hover-scale"></v-btn>
                                            </div>
                                        </div>
                                        <v-card-item class="pt-5 px-5">
                                            <div class="text-caption text-primary font-weight-bold mb-1 text-uppercase">{{ product.category }}</div>
                                            <v-card-title class="text-h6 font-weight-bold mb-2 text-grey-darken-3 px-0 lh-tight">
                                                {{ product.title }}
                                            </v-card-title>
                                            <v-card-text class="text-body-2 text-medium-emphasis px-0 text-truncate-2 mb-2">
                                                {{ product.short_description }}
                                            </v-card-text>
                                            <div class="d-flex align-center mb-2">
                                                <v-rating
                                                    :model-value="4.5"
                                                    color="amber"
                                                    density="compact"
                                                    half-increments
                                                    readonly
                                                    size="small"
                                                ></v-rating>
                                                <span class="text-caption text-medium-emphasis ml-2">(24)</span>
                                            </div>
                                        </v-card-item>
                                        <v-card-actions class="px-5 pb-5 pt-0 d-flex align-center">
                                            <div class="font-weight-black text-h6 text-primary">${{ product.price || '99.00' }}</div>
                                            <v-spacer></v-spacer>
                                            <v-btn variant="tonal" color="primary" size="small" class="font-weight-bold rounded-lg">
                                                Details
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-hover>
                            </v-col>
                        </v-row>
                        
                        <div class="text-center mt-12">
                            <v-btn variant="outlined" color="primary" rounded="pill" class="px-8 font-weight-bold">
                                Load More Products
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Features Strip -->
        <section class="py-12 bg-surface border-top">
            <v-container>
                <v-row>
                    <v-col cols="12" md="4" class="d-flex align-center justify-center text-center text-md-left">
                        <v-icon icon="mdi-shield-check-outline" size="40" color="primary" class="mr-4"></v-icon>
                        <div>
                            <h4 class="text-subtitle-1 font-weight-bold">Official Warranty</h4>
                            <p class="text-caption text-medium-emphasis">Guaranteed protection on all items</p>
                        </div>
                    </v-col>
                    <v-col cols="12" md="4" class="d-flex align-center justify-center text-center text-md-left">
                        <v-icon icon="mdi-truck-fast-outline" size="40" color="primary" class="mr-4"></v-icon>
                        <div>
                            <h4 class="text-subtitle-1 font-weight-bold">Fast Delivery</h4>
                            <p class="text-caption text-medium-emphasis">Nationwide shipping available</p>
                        </div>
                    </v-col>
                    <v-col cols="12" md="4" class="d-flex align-center justify-center text-center text-md-left">
                        <v-icon icon="mdi-headset" size="40" color="primary" class="mr-4"></v-icon>
                        <div>
                            <h4 class="text-subtitle-1 font-weight-bold">Expert Support</h4>
                            <p class="text-caption text-medium-emphasis">24/7 technical assistance</p>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </section>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ProductsPage',
    data() {
        return {
            products: [],
            activeCategory: 'all',
            sortBy: 'Newest',
            priceRange: [0, 5000],
            categories: [
                { id: 'all', name: 'All Products', count: 42, icon: 'mdi-view-grid' },
                { id: 'ups', name: 'UPS Systems', count: 15, icon: 'mdi-battery-charging-high' },
                { id: 'batteries', name: 'Batteries', count: 12, icon: 'mdi-battery' },
                { id: 'inverters', name: 'Inverters', count: 8, icon: 'mdi-lightning-bolt' },
                { id: 'stabilizers', name: 'Stabilizers', count: 7, icon: 'mdi-sine-wave' }
            ]
        };
    },
    computed: {
        filteredProducts() {
            // In a real app, filtering would happen here or via API
            return this.products;
        }
    },
    async mounted() {
        await this.loadProducts();
    },
    methods: {
        async loadProducts() {
            try {
                const response = await axios.get('/api/public/products');
                this.products = response.data.map(p => ({
                    ...p,
                    category: 'UPS Systems', // Mock category
                    isNew: Math.random() > 0.7, // Mock new badge
                    price: (Math.random() * 500 + 100).toFixed(2) // Mock price
                }));
            } catch (error) {
                console.error('Error loading products:', error);
                // Fallback data
                this.products = Array.from({ length: 6 }).map((_, i) => ({
                    id: i,
                    title: `Power Product ${i + 1}`,
                    slug: `product-${i + 1}`,
                    short_description: 'High-performance power solution for your business needs.',
                    category: i % 2 === 0 ? 'UPS Systems' : 'Batteries',
                    isNew: i === 0,
                    price: (Math.random() * 500 + 100).toFixed(2)
                }));
            }
        }
    }
};
</script>

<style scoped>
.page-hero {
    height: 350px;
    background: #0f172a;
}

.hero-bg-gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
    opacity: 0.9;
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.3;
}

.glass-pill {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.product-card-modern .overlay-actions {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.3);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.product-card-modern:hover .overlay-actions {
    opacity: 1;
}

.scale-110 {
    transform: scale(1.1);
}

.hover-scale:hover {
    transform: scale(1.1);
}

.border-thin {
    border: 1px solid rgba(0,0,0,0.08) !important;
}

.sticky-top {
    position: sticky;
    top: 100px;
}

.mw-800 { max-width: 800px; }
.lh-tight { line-height: 1.2; }
.tracking-wide { letter-spacing: 0.05em; }
.z-index-2 { z-index: 2; }
</style>
