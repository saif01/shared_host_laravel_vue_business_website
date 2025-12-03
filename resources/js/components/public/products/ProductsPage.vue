<template>
    <div class="products-page-modern">
        <!-- Hero Section -->
        <section
            class="mb-10 page-hero position-relative d-flex align-center justify-center text-center overflow-hidden">
            <div class="hero-bg-gradient"></div>
            <div class="hero-pattern"></div>

            <!-- Animated gradient orbs -->
            <div class="gradient-orb orb-1"></div>
            <div class="gradient-orb orb-2"></div>
            <div class="gradient-orb orb-3"></div>

            <v-container class="position-relative z-index-2">
                <v-fade-transition appear>
                    <div>
                        <div class="glass-pill d-inline-flex align-center px-5 py-3 rounded-pill mb-8 animate-float">
                            <div class="pulse-dot mr-2"></div>
                            <v-icon icon="mdi-shield-check" color="amber-accent-4" size="small" class="mr-2"></v-icon>
                            <span class="text-subtitle-2 font-weight-bold tracking-wide text-white">PREMIUM
                                QUALITY</span>
                        </div>
                        <h1
                            class="text-h4 text-lg-h2 font-weight-black text-white mb-6 lh-tight text-shadow-sm animate-slide-up">
                            Explore Our <span class="text-amber-accent-3">Product Range</span>
                        </h1>
                        <p
                            class="text-h6 text-white opacity-90 mw-700 mx-auto font-weight-light animate-slide-up-delay">
                            Discover high-performance solutions designed for your business needs. Browse, compare, and
                            find the perfect product.
                        </p>
                    </div>
                </v-fade-transition>
            </v-container>
        </section>

        <!-- Main Content -->
        <section class="pb-12 bg-grey-lighten-5 min-vh-100">
            <v-container fluid>
                <v-row class="products-layout">
                    <!-- Left Sidebar - Filter Bar -->
                    <v-col cols="12" lg="3" xl="2" class="filter-sidebar">
                        <FilterBar :categories="categories" :active-category="activeCategory"
                            :search-query="searchQuery" :sort-by="sortBy" :sort-options="sortOptions"
                            :sort-by-label="sortByLabel" :has-active-filters="hasActiveFilters"
                            :active-filters-count="activeFiltersCount" :comparison-count="comparisonProducts.length"
                            :results-count="advancedFilteredProducts.length" :price-range="priceRange"
                            :availability="availability" :brands="availableBrands" :selected-brands="selectedBrands"
                            :min-rating="minRating" :features="availableFeatures" :selected-features="selectedFeatures"
                            :discount="discount" @update:active-category="setActiveCategory"
                            @update:search-query="setSearchQuery" @update:sort-by="setSortBy"
                            @update:price-range="setPriceRange" @update:availability="setAvailability"
                            @update:brands="setSelectedBrands" @update:min-rating="setMinRating"
                            @update:features="setSelectedFeatures" @update:discount="setDiscount"
                            @clear-filters="clearAllFilters" @open-comparison="openComparison" />
                    </v-col>

                    <!-- Right Content - Products -->
                    <v-col cols="12" lg="9" xl="10" class="products-content">
                        <!-- Loading State -->
                        <div v-if="loading" class="text-center py-16">
                            <v-progress-circular indeterminate color="primary" size="64" />
                            <p class="text-body-1 text-medium-emphasis mt-4">Loading products...</p>
                        </div>

                        <!-- Empty State -->
                        <div v-else-if="advancedFilteredProducts.length === 0" class="text-center py-16">
                            <v-icon icon="mdi-package-variant" size="64" color="grey-lighten-1" class="mb-4" />
                            <h3 class="text-h6 font-weight-bold text-grey-darken-2 mb-2">No products found</h3>
                            <p class="text-body-2 text-medium-emphasis mb-6">
                                Try adjusting your filters or search terms.
                            </p>
                            <v-btn variant="outlined" color="primary" @click="clearAllFilters">
                                Clear Filters
                            </v-btn>
                        </div>

                        <!-- Products Grid -->
                        <v-row v-else>
                            <v-col v-for="product in displayedProducts" :key="product.id" cols="12" sm="6" xl="4"
                                xxl="3">
                                <ProductCard :product="product"
                                    :comparison-disabled="!canAddMore && !isInComparison(product)"
                                    @toggle-comparison="handleToggleComparison" />
                            </v-col>
                        </v-row>

                        <!-- Load More Section (if needed) -->
                        <div v-if="displayedProducts.length > 0 && hasMoreProducts" class="text-center mt-12">
                            <v-btn variant="outlined" color="primary" size="large" rounded="pill"
                                class="px-10 font-weight-bold" @click="loadMore" :loading="loadingMore">
                                Load More Products
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Comparison Dialog Component -->
        <ComparisonDialog v-model="showComparison" :products="comparisonProducts" :comparison-specs="comparisonSpecs"
            @remove-product="removeFromComparison" @clear-all="clearComparison" />
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useProducts } from '../../../composables/useProducts';
import { useCategories } from '../../../composables/useCategories';
import { useProductFilters } from '../../../composables/useProductFilters';
import { useProductComparison } from '../../../composables/useProductComparison';
import ProductCard from './ProductCard.vue';
import FilterBar from './FilterBar.vue';
import ComparisonDialog from './ComparisonDialog.vue';

// Pagination constants
const PRODUCTS_PER_PAGE = 12;

// Initialize composables
const { products, loading, fetchProducts } = useProducts();
const { categories, fetchCategories } = useCategories();
const {
    activeCategory,
    searchQuery,
    sortBy,
    sortOptions,
    filteredProducts,
    sortByLabel,
    hasActiveFilters,
    activeFiltersCount,
    setActiveCategory,
    setSortBy,
    setSearchQuery,
    clearFilters
} = useProductFilters(products);

const {
    comparisonProducts,
    showComparison,
    canAddMore,
    isInComparison,
    toggleComparison,
    removeFromComparison,
    clearComparison,
    openComparison,
    getComparisonSpecs
} = useProductComparison(3);

// Pagination state
const displayedCount = ref(PRODUCTS_PER_PAGE);
const loadingMore = ref(false);

// Advanced filter states
const priceRange = ref([0, 10000]);
const availability = ref([]);
const selectedBrands = ref([]);
const minRating = ref(0);
const selectedFeatures = ref([]);
const discount = ref(null);

// Available filter options
const availableBrands = computed(() => {
    // Extract unique brands from products
    const brandsSet = new Set();
    products.value.forEach(product => {
        if (product.brand) {
            brandsSet.add(product.brand);
        }
    });
    return Array.from(brandsSet).sort();
});

const availableFeatures = computed(() => {
    // Define common product features
    return [
        { value: 'wireless', label: 'Wireless', icon: 'mdi-wifi' },
        { value: 'waterproof', label: 'Waterproof', icon: 'mdi-water' },
        { value: 'bluetooth', label: 'Bluetooth', icon: 'mdi-bluetooth' },
        { value: 'rechargeable', label: 'Rechargeable', icon: 'mdi-battery-charging' },
        { value: 'warranty', label: 'Warranty', icon: 'mdi-shield-check' },
        { value: 'eco_friendly', label: 'Eco-Friendly', icon: 'mdi-leaf' }
    ];
});

// Computed properties
const comparisonSpecs = computed(() => {
    return getComparisonSpecs();
});

// Apply advanced filters to filteredProducts
const advancedFilteredProducts = computed(() => {
    let filtered = [...filteredProducts.value];

    // Price range filter
    if (priceRange.value[0] > 0 || priceRange.value[1] < 10000) {
        filtered = filtered.filter(product => {
            const price = parseFloat(product.price) || 0;
            return price >= priceRange.value[0] && price <= priceRange.value[1];
        });
    }

    // Availability filter
    if (availability.value.length > 0) {
        filtered = filtered.filter(product => {
            return availability.value.includes(product.availability);
        });
    }

    // Brand filter
    if (selectedBrands.value.length > 0) {
        filtered = filtered.filter(product => {
            return selectedBrands.value.includes(product.brand);
        });
    }

    // Rating filter
    if (minRating.value > 0) {
        filtered = filtered.filter(product => {
            const rating = parseFloat(product.rating) || 0;
            return rating >= minRating.value;
        });
    }

    // Features filter
    if (selectedFeatures.value.length > 0) {
        filtered = filtered.filter(product => {
            if (!product.features || !Array.isArray(product.features)) return false;
            return selectedFeatures.value.some(feature => product.features.includes(feature));
        });
    }

    // Discount filter
    if (discount.value) {
        filtered = filtered.filter(product => {
            const discountPercent = parseFloat(product.discount_percent) || 0;
            if (discount.value === 'any') return discountPercent > 0;
            return discountPercent >= parseFloat(discount.value);
        });
    }

    return filtered;
});

// Display only the first N products from filtered results
const displayedProducts = computed(() => {
    return advancedFilteredProducts.value.slice(0, displayedCount.value);
});

// Check if there are more products to load
const hasMoreProducts = computed(() => {
    return advancedFilteredProducts.value.length > displayedCount.value;
});

// Advanced filter setter methods
const setPriceRange = (range) => {
    priceRange.value = range;
};

const setAvailability = (availabilityValue) => {
    availability.value = availabilityValue;
};

const setSelectedBrands = (brands) => {
    selectedBrands.value = brands;
};

const setMinRating = (rating) => {
    minRating.value = rating;
};

const setSelectedFeatures = (features) => {
    selectedFeatures.value = features;
};

const setDiscount = (discountValue) => {
    discount.value = discountValue;
};

// Methods
const handleToggleComparison = (product) => {
    const success = toggleComparison(product);
    if (!success && !isInComparison(product)) {
        // Show error message - product limit reached
        console.warn('Cannot add more than 3 products to comparison');
        // You can integrate with a toast notification system here
    }
};

const loadMore = () => {
    loadingMore.value = true;
    // Simulate a small delay for better UX (optional)
    setTimeout(() => {
        displayedCount.value += PRODUCTS_PER_PAGE;
        loadingMore.value = false;
    }, 200);
};

// Enhanced clear filters function
const clearAllFilters = () => {
    clearFilters(); // Clear basic filters
    priceRange.value = [0, 10000];
    availability.value = [];
    selectedBrands.value = [];
    minRating.value = 0;
    selectedFeatures.value = [];
    discount.value = null;
};

// Reset pagination when filters change
watch([activeCategory, searchQuery, sortBy, priceRange, availability, selectedBrands, minRating, selectedFeatures, discount], () => {
    displayedCount.value = PRODUCTS_PER_PAGE;
}, { deep: true });

// Lifecycle hooks
onMounted(async () => {
    await Promise.all([
        fetchCategories(),
        fetchProducts()
    ]);
});
</script>

<style scoped>
.products-page-modern {
    background: #f8fafc;
}

/* Products Layout */
.products-layout {
    align-items: flex-start;
}

.filter-sidebar {
    position: sticky;
    top: 80px;
    align-self: flex-start;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
    padding-right: 16px;
}

.filter-sidebar::-webkit-scrollbar {
    width: 6px;
}

.filter-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.filter-sidebar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

.filter-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

.products-content {
    padding-left: 24px;
}

/* Hero section styles moved to app.css */

/* Responsive Styles */
@media (max-width: 1280px) {
    .filter-sidebar {
        position: relative;
        top: 0;
        max-height: none;
        overflow-y: visible;
        padding-right: 12px;
    }

    .products-content {
        padding-left: 12px;
    }
}

@media (max-width: 960px) {
    .page-hero {
        min-height: 400px !important;
        padding: 48px 16px !important;
    }

    .page-hero h1 {
        font-size: 2rem !important;
        line-height: 1.2 !important;
        margin-bottom: 16px !important;
    }

    .page-hero p {
        font-size: 1.1rem !important;
        line-height: 1.5 !important;
    }

    .glass-pill {
        padding: 10px 20px !important;
        margin-bottom: 24px !important;
    }

    .glass-pill span {
        font-size: 0.75rem !important;
    }

    .gradient-orb {
        width: 200px !important;
        height: 200px !important;
    }
}

@media (max-width: 600px) {
    .page-hero {
        min-height: 350px !important;
        padding: 32px 12px !important;
    }

    .page-hero h1 {
        font-size: 1.75rem !important;
        line-height: 1.3 !important;
        margin-bottom: 12px !important;
    }

    .page-hero p {
        font-size: 1rem !important;
        line-height: 1.4 !important;
        padding: 0 8px !important;
    }

    .glass-pill {
        padding: 8px 16px !important;
        margin-bottom: 20px !important;
    }

    .glass-pill .v-icon {
        font-size: 16px !important;
    }

    .glass-pill span {
        font-size: 0.7rem !important;
    }

    .gradient-orb {
        width: 150px !important;
        height: 150px !important;
    }

    .orb-1 {
        top: -75px !important;
        left: -75px !important;
    }

    .orb-2 {
        bottom: -75px !important;
        right: -75px !important;
    }

    .orb-3 {
        top: 50% !important;
        right: -75px !important;
    }
}
</style>
