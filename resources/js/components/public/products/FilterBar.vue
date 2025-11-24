<template>
    <div class="filter-shell mb-4 mb-md-5">
        <v-card class="filter-card rounded-xl elevation-2" :class="{ 'sticky-filter': isSticky }">
            <div class="filter-top">
                <div class="filter-meta">
                    <span class="eyebrow">Product filters</span>
                    <div class="title-row">
                        <div class="icon-pill">
                            <v-icon icon="mdi-filter-variant" color="primary" size="18" />
                        </div>
                        <div>
                            <div class="title-text">Filter & Search</div>
                            <div class="subtitle">Shape the catalog in a couple taps</div>
                        </div>
                    </div>
                </div>
                <div class="top-actions">
                    <v-chip v-if="resultsCount" size="small" color="primary" variant="tonal" class="metric-chip">
                        {{ resultsCount }} results
                    </v-chip>
                    <v-chip v-if="hasActiveFilters" size="small" color="primary" variant="flat" class="metric-chip">
                        {{ activeFiltersCount }} active
                    </v-chip>
                    <v-btn variant="text" color="grey-darken-1" size="small" prepend-icon="mdi-close-circle"
                        class="text-capitalize clear-btn" :disabled="!hasActiveFilters"
                        @click="$emit('clear-filters')">
                        Clear filters
                    </v-btn>
                </div>
            </div>

            <div class="filter-grid">
                <div class="panel search-panel">
                    <div class="panel-header">
                        <span class="panel-title">Search</span>
                        <span class="panel-hint">Names, tags or SKU</span>
                    </div>
                    <v-text-field :model-value="searchQuery" density="compact" variant="solo-filled"
                        placeholder="Search products..." prepend-inner-icon="mdi-magnify" hide-details="auto"
                        bg-color="white" class="search-field rounded-lg" clearable
                        @update:model-value="handleSearchInput" @click:clear="$emit('update:searchQuery', '')">
                        <template v-if="searchQuery" #append-inner>
                            <v-chip size="x-small" color="primary" variant="flat" class="results-chip mr-1">
                                {{ resultsCount }}
                            </v-chip>
                        </template>
                    </v-text-field>
                </div>

                <div class="panel sort-panel">
                    <div class="panel-header">
                        <span class="panel-title">Sort & compare</span>
                        <span class="panel-hint">Tune order & shortlist</span>
                    </div>
                    <div class="sort-actions">
                        <v-menu location="bottom end" :close-on-content-click="true">
                            <template #activator="{ props: menuProps }">
                                <v-btn v-bind="menuProps" variant="outlined" color="grey-darken-2"
                                    class="sort-btn bg-white text-grey-darken-2 font-weight-medium"
                                    append-icon="mdi-chevron-down" size="small" density="comfortable">
                                    <v-icon icon="mdi-sort" size="16" class="mr-1 d-none d-sm-inline" />
                                    <span class="d-none d-sm-inline">{{ sortByLabel }}</span>
                                    <span class="d-sm-none">Sort</span>
                                </v-btn>
                            </template>
                            <v-list class="sort-menu-list" density="compact">
                                <v-list-item v-for="item in sortOptions" :key="item.value"
                                    :class="{ 'sort-option-active': sortBy === item.value }" class="sort-option"
                                    @click="$emit('update:sortBy', item.value)">
                                    <template #prepend>
                                        <v-icon v-if="sortBy === item.value" icon="mdi-check" color="primary"
                                            size="small" class="mr-2" />
                                        <div v-else class="mr-2" style="width: 20px;" />
                                    </template>
                                    <v-list-item-title class="font-weight-medium text-body-2">
                                        {{ item.label }}
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <v-btn v-if="comparisonCount > 0" color="primary" variant="flat"
                            prepend-icon="mdi-scale-balance" class="comparison-btn text-white font-weight-bold"
                            size="small" density="comfortable" @click="$emit('open-comparison')">
                            <span class="d-none d-sm-inline mr-1">Compare</span>
                            <v-chip size="x-small" color="white" variant="flat" class="comparison-badge ml-1">
                                {{ comparisonCount }}
                            </v-chip>
                        </v-btn>
                    </div>
                </div>
            </div>

            <div class="category-block">
                <div class="category-heading">
                    <div>
                        <span class="eyebrow">Categories</span>
                        <div class="subcopy">Swipe through the full catalog</div>
                    </div>
                    <v-chip v-if="hasActiveFilters" size="x-small" color="primary" variant="tonal"
                        class="d-none d-sm-inline-flex">
                        Active filter set
                    </v-chip>
                </div>
                <div class="category-filter-wrapper">
                    <div class="category-filter d-flex align-center overflow-x-auto w-100 pb-1 gap-2">
                        <v-btn v-for="category in categories" :key="category.id" variant="flat"
                            :color="activeCategory === category.id ? 'primary' : 'grey-lighten-3'"
                            class="category-btn font-weight-bold text-capitalize rounded-pill flex-shrink-0"
                            :class="{
                                'category-btn-active': activeCategory === category.id,
                                'category-btn-inactive': activeCategory !== category.id
                            }" size="small" density="comfortable"
                            @click="$emit('update:activeCategory', category.id)" :aria-pressed="activeCategory === category.id">
                            <v-icon v-if="category.icon" :icon="category.icon" size="14" class="mr-1" />
                            <span class="category-text">{{ category.name }}</span>
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-card>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { debounce } from '../../../utils/debounce';

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    activeCategory: {
        type: [String, Number],
        default: 'all'
    },
    searchQuery: {
        type: String,
        default: ''
    },
    sortBy: {
        type: String,
        default: 'newest'
    },
    sortOptions: {
        type: Array,
        default: () => []
    },
    sortByLabel: {
        type: String,
        default: 'Newest First'
    },
    hasActiveFilters: {
        type: Boolean,
        default: false
    },
    activeFiltersCount: {
        type: Number,
        default: 0
    },
    comparisonCount: {
        type: Number,
        default: 0
    },
    resultsCount: {
        type: Number,
        default: 0
    }
});

const emit = defineEmits([
    'update:activeCategory',
    'update:searchQuery',
    'update:sortBy',
    'clear-filters',
    'open-comparison'
]);

// Sticky filter on scroll
const isSticky = ref(false);

const handleScroll = () => {
    isSticky.value = window.scrollY > 200;
};

// Debounced search input handler
const handleSearchInput = debounce((value) => {
    emit('update:searchQuery', value || '');
}, 300);

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.filter-shell {
    position: relative;
}

.filter-card {
    background: radial-gradient(120% 120% at 10% 10%, rgba(var(--v-theme-primary), 0.06), transparent),
        linear-gradient(180deg, #ffffff 0%, #f7f9fb 100%) !important;
    backdrop-filter: blur(12px);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: 0 12px 40px -24px rgba(15, 23, 42, 0.55) !important;
    padding: 14px 14px 16px;
}

.filter-card.sticky-filter {
    position: sticky;
    top: 64px;
    z-index: 90;
    box-shadow: 0 18px 45px -20px rgba(0, 0, 0, 0.25) !important;
    border-color: rgba(var(--v-theme-primary), 0.18);
}

.filter-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.filter-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.eyebrow {
    display: inline-flex;
    align-items: center;
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #6b7280;
    font-weight: 700;
}

.title-row {
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.icon-pill {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(var(--v-theme-primary), 0.12);
    box-shadow: 0 8px 18px -10px rgba(var(--v-theme-primary), 0.45);
}

.title-text {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
}

.subtitle {
    font-size: 0.85rem;
    color: #6b7280;
}

.top-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.metric-chip {
    font-weight: 700;
    letter-spacing: 0.01em;
}

.clear-btn {
    font-weight: 700;
}

.filter-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.9fr);
    gap: 12px;
    padding: 12px 0 4px;
}

.panel {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0.04);
    border-radius: 14px;
    padding: 10px;
    box-shadow: 0 6px 18px -14px rgba(15, 23, 42, 0.7);
}

.panel-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-bottom: 6px;
    gap: 6px;
}

.panel-title {
    font-weight: 700;
    color: #0f172a;
}

.panel-hint {
    font-size: 0.8rem;
    color: #6b7280;
}

.search-field {
    width: 100%;
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}

.search-field :deep(.v-field) {
    border-radius: 10px;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 8px 20px -14px rgba(15, 23, 42, 0.5);
}

.search-field :deep(.v-field--focused) {
    box-shadow: 0 10px 30px -16px rgba(var(--v-theme-primary), 0.6) !important;
    transform: translateY(-1px);
}

.search-field :deep(.v-field__input) {
    font-size: 0.9rem;
    padding: 10px 12px;
}

.search-field :deep(.v-field__prepend-inner .v-icon) {
    font-size: 18px;
}

.results-chip {
    font-weight: 700;
    font-size: 0.75rem;
}

.sort-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    justify-content: flex-end;
}

.sort-btn {
    transition: all 0.25s ease;
    border-radius: 12px;
    min-width: 0;
    font-weight: 700;
    min-height: 42px;
}

.sort-btn:hover {
    box-shadow: 0 10px 30px -16px rgba(0, 0, 0, 0.2);
    transform: translateY(-1px);
}

.sort-menu-list {
    border-radius: 12px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2) !important;
    min-width: 220px;
    max-width: 92vw;
}

.sort-option {
    transition: all 0.2s ease;
    border-radius: 8px;
    margin: 2px 6px;
    min-height: 38px;
}

.sort-option:hover {
    background-color: rgba(var(--v-theme-primary), 0.08);
}

.sort-option-active {
    background-color: rgba(var(--v-theme-primary), 0.1);
    color: rgb(var(--v-theme-primary));
}

.comparison-btn {
    border-radius: 12px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(var(--v-theme-primary), 0.2) !important;
    min-width: 0;
    font-weight: 800;
    min-height: 42px;
}

.comparison-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 25px rgba(var(--v-theme-primary), 0.3) !important;
}

.comparison-badge {
    color: rgb(var(--v-theme-primary)) !important;
    font-weight: 800;
    min-width: 18px;
    height: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    animation: bounce 0.5s ease;
    font-size: 0.68rem;
}

@keyframes bounce {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.15);
    }
}

.category-block {
    border-top: 1px dashed rgba(0, 0, 0, 0.06);
    margin-top: 2px;
    padding-top: 12px;
}

.category-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    gap: 10px;
}

.subcopy {
    font-size: 0.82rem;
    color: #6b7280;
}

.category-filter-wrapper {
    position: relative;
    width: 100%;
}

.category-filter {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    overflow-x: auto;
    overflow-y: hidden;
    width: 100%;
    padding: 2px 0 2px;
}

@media (min-width: 960px) {
    .category-filter {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .category-filter::-webkit-scrollbar {
        display: none;
    }
}

@media (max-width: 959px) {
    .category-filter {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.16) transparent;
    }

    .category-filter::-webkit-scrollbar {
        height: 5px;
        display: block;
    }

    .category-filter::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
    }
}

.category-btn {
    white-space: nowrap;
    transition: all 0.25s ease;
    text-transform: none !important;
    letter-spacing: 0.1px;
    font-size: 0.8rem;
    min-height: 32px;
    padding: 7px 12px !important;
    flex-shrink: 0;
    min-width: auto;
}

.category-text {
    font-size: inherit;
}

.category-btn-active {
    box-shadow: 0 10px 20px -12px rgba(var(--v-theme-primary), 0.6) !important;
    transform: translateY(-1px);
}

.category-btn-inactive {
    color: #475569 !important;
}

.category-btn-inactive:hover {
    background-color: #e2e8f0 !important;
    transform: translateY(-1px);
    box-shadow: 0 8px 18px -12px rgba(0, 0, 0, 0.15) !important;
}

@media (max-width: 1024px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }

    .panel {
        padding: 9px;
    }

    .filter-card {
        padding: 12px;
    }
}

@media (max-width: 720px) {
    .filter-card.sticky-filter {
        top: 56px;
    }

    .filter-top {
        flex-direction: column;
        align-items: flex-start;
    }

    .top-actions {
        justify-content: flex-start;
    }

    .category-heading {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .filter-card {
        border-radius: 14px !important;
    }

    .title-text {
        font-size: 0.98rem;
    }

    .panel-hint,
    .subtitle {
        font-size: 0.78rem;
    }

    .sort-actions {
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 8px;
    }

    .sort-btn,
    .comparison-btn {
        width: 100%;
        justify-content: center;
        min-height: 40px;
    }
}
</style>
