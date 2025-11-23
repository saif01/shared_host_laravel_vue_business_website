<template>
    <div class="home-page">
        <!-- Hero Section -->
        <section class="hero-section position-relative overflow-hidden">
            <div class="hero-bg-overlay"></div>
            <v-container class="h-100">
                <v-row class="h-100 align-center">
                    <v-col cols="12" md="6" class="position-relative z-index-1">
                        <v-fade-transition appear>
                            <div>
                                <div class="text-overline text-primary mb-2 font-weight-bold tracking-wider">
                                    INNOVATIVE SOLUTIONS
                                </div>
                                <h1 class="text-h2 text-md-h1 font-weight-bold mb-4 text-white lh-tight">
                                    {{ heroTitle }}
                                </h1>
                                <p class="text-h6 text-grey-lighten-1 mb-8 mw-600">
                                    {{ heroSubtitle }}
                                </p>
                                <div class="d-flex gap-4 flex-wrap">
                                    <v-btn
                                        size="x-large"
                                        color="primary"
                                        elevation="4"
                                        rounded="pill"
                                        class="px-8 text-none font-weight-bold"
                                        :to="{ name: 'Contact' }"
                                    >
                                        Get Started
                                        <v-icon end icon="mdi-arrow-right" class="ml-2"></v-icon>
                                    </v-btn>
                                    <v-btn
                                        size="x-large"
                                        variant="outlined"
                                        color="white"
                                        rounded="pill"
                                        class="px-8 text-none font-weight-bold"
                                        to="/services"
                                    >
                                        Our Services
                                    </v-btn>
                                </div>
                            </div>
                        </v-fade-transition>
                    </v-col>
                    <!-- Decorative Element (Optional Image Placeholder) -->
                    <v-col cols="12" md="6" class="d-none d-md-flex justify-center align-center position-relative z-index-1">
                         <div class="hero-visual">
                            <div class="floating-card card-1">
                                <v-icon icon="mdi-rocket-launch" size="32" color="primary"></v-icon>
                                <div class="text-subtitle-2 font-weight-bold mt-2">Fast Growth</div>
                            </div>
                            <div class="floating-card card-2">
                                <v-icon icon="mdi-shield-check" size="32" color="success"></v-icon>
                                <div class="text-subtitle-2 font-weight-bold mt-2">Secure</div>
                            </div>
                            <div class="hero-circle"></div>
                         </div>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Stats Section -->
        <section class="py-12 bg-surface-variant">
            <v-container>
                <v-row justify="center">
                    <v-col v-for="(stat, index) in stats" :key="index" cols="6" md="3" class="text-center">
                        <div class="text-h3 font-weight-bold text-primary mb-1">{{ stat.value }}</div>
                        <div class="text-subtitle-1 text-medium-emphasis">{{ stat.label }}</div>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Services Section -->
        <section class="py-16 section-services">
            <v-container>
                <div class="text-center mb-12">
                    <div class="text-overline text-primary mb-2">WHAT WE DO</div>
                    <h2 class="text-h3 font-weight-bold mb-4">Our Premium Services</h2>
                    <p class="text-subtitle-1 text-medium-emphasis mx-auto mw-800">
                        We deliver high-quality solutions tailored to your business needs.
                    </p>
                </div>

                <v-row v-if="services.length > 0">
                    <v-col v-for="service in services" :key="service.id" cols="12" md="4">
                        <v-hover v-slot="{ isHovering, props }">
                            <v-card
                                v-bind="props"
                                :elevation="isHovering ? 8 : 2"
                                class="h-100 service-card transition-swing"
                                :to="`/services/${service.slug}`"
                                rounded="xl"
                            >
                                <v-card-item class="pt-6">
                                    <div class="service-icon-wrapper mb-4 bg-primary-lighten-5 rounded-circle d-inline-flex pa-4">
                                        <v-icon icon="mdi-star-four-points" color="primary" size="32"></v-icon>
                                    </div>
                                    <v-card-title class="text-h5 font-weight-bold mb-2">
                                        {{ service.title }}
                                    </v-card-title>
                                    <v-card-subtitle class="text-body-1 text-wrap mb-4 opacity-80">
                                        {{ service.short_description }}
                                    </v-card-subtitle>
                                </v-card-item>
                                <v-card-actions class="px-4 pb-6">
                                    <v-btn variant="text" color="primary" class="font-weight-bold">
                                        Learn More <v-icon end icon="mdi-arrow-right"></v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-hover>
                    </v-col>
                </v-row>
                <div v-else class="text-center text-medium-emphasis">
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                </div>
            </v-container>
        </section>

        <!-- Why Choose Us Section -->
        <section class="py-16 bg-grey-lighten-4 position-relative overflow-hidden">
             <v-container>
                <v-row align="center">
                    <v-col cols="12" md="6">
                        <div class="text-overline text-primary mb-2">WHY CHOOSE US</div>
                        <h2 class="text-h3 font-weight-bold mb-6">We Help You Scale Your Business</h2>
                        <v-list bg-color="transparent">
                            <v-list-item v-for="(feature, i) in features" :key="i" class="px-0 mb-4">
                                <template v-slot:prepend>
                                    <v-icon icon="mdi-check-circle" color="primary" class="mr-4"></v-icon>
                                </template>
                                <v-list-item-title class="text-h6 font-weight-bold">{{ feature.title }}</v-list-item-title>
                                <v-list-item-subtitle class="text-body-1 mt-1">{{ feature.desc }}</v-list-item-subtitle>
                            </v-list-item>
                        </v-list>
                    </v-col>
                    <v-col cols="12" md="6" class="text-center">
                        <v-card elevation="0" color="transparent" class="mx-auto" max-width="500">
                             <!-- Abstract Visual Representation -->
                             <div class="abstract-grid">
                                <div class="grid-item item-1 bg-primary"></div>
                                <div class="grid-item item-2 bg-secondary"></div>
                                <div class="grid-item item-3 bg-info"></div>
                             </div>
                        </v-card>
                    </v-col>
                </v-row>
             </v-container>
        </section>

        <!-- Featured Products Section -->
        <section class="py-16">
            <v-container>
                <div class="d-flex justify-space-between align-end mb-12">
                    <div>
                        <div class="text-overline text-primary mb-2">OUR WORK</div>
                        <h2 class="text-h3 font-weight-bold">Featured Products</h2>
                    </div>
                    <v-btn variant="text" color="primary" to="/products" class="d-none d-sm-flex">
                        View All <v-icon end icon="mdi-arrow-right"></v-icon>
                    </v-btn>
                </div>

                <v-row v-if="products.length > 0">
                    <v-col v-for="product in products" :key="product.id" cols="12" md="4">
                        <v-hover v-slot="{ isHovering, props }">
                            <v-card
                                v-bind="props"
                                :elevation="isHovering ? 12 : 4"
                                class="h-100 product-card transition-swing overflow-hidden"
                                :to="`/products/${product.slug}`"
                                rounded="lg"
                            >
                                <v-img
                                    :src="product.thumbnail || 'https://via.placeholder.com/400x300?text=Product'"
                                    height="240"
                                    cover
                                    class="align-end"
                                >
                                    <template v-slot:placeholder>
                                        <div class="d-flex align-center justify-center fill-height bg-grey-lighten-2">
                                            <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                        </div>
                                    </template>
                                    <div class="product-overlay pa-4">
                                        <v-chip color="primary" size="small" class="mb-2">New</v-chip>
                                    </div>
                                </v-img>
                                <v-card-item class="pt-4">
                                    <v-card-title class="text-h6 font-weight-bold">{{ product.title }}</v-card-title>
                                    <v-card-text class="text-body-2 text-medium-emphasis mt-2 text-truncate-2">
                                        {{ product.short_description }}
                                    </v-card-text>
                                </v-card-item>
                                <v-card-actions class="px-4 pb-4">
                                    <v-btn block variant="tonal" color="primary">View Details</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-hover>
                    </v-col>
                </v-row>
                <div v-else class="text-center py-12">
                     <p class="text-h6 text-medium-emphasis">No products featured yet.</p>
                </div>
                
                <div class="text-center mt-8 d-sm-none">
                    <v-btn variant="outlined" color="primary" to="/products">
                        View All Products
                    </v-btn>
                </div>
            </v-container>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-primary position-relative overflow-hidden">
            <div class="cta-pattern"></div>
            <v-container class="position-relative z-index-1">
                <v-row justify="center" class="text-center">
                    <v-col cols="12" md="8">
                        <h2 class="text-h3 font-weight-bold text-white mb-6">Ready to Transform Your Business?</h2>
                        <p class="text-h6 text-white opacity-90 mb-8">
                            Join hundreds of satisfied clients who have elevated their operations with our solutions.
                        </p>
                        <v-btn
                            size="x-large"
                            color="white"
                            variant="flat"
                            class="text-primary font-weight-bold px-8"
                            rounded="pill"
                            :to="{ name: 'Contact' }"
                        >
                            Contact Us Now
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </section>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'HomePage',
    data() {
        return {
            heroTitle: 'Welcome to Our Business',
            heroSubtitle: 'We provide excellent services and products to help you grow and succeed in the digital age.',
            services: [],
            products: [],
            stats: [
                { value: '500+', label: 'Happy Clients' },
                { value: '120+', label: 'Projects Done' },
                { value: '24/7', label: 'Support' },
                { value: '10+', label: 'Years Experience' }
            ],
            features: [
                { title: 'Expert Team', desc: 'Our team consists of industry experts with years of experience.' },
                { title: 'Tailored Solutions', desc: 'We customize every solution to fit your specific business needs.' },
                { title: 'Proven Track Record', desc: 'We have a history of delivering successful projects on time.' }
            ]
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
    background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%);
    min-height: 600px;
    display: flex;
    align-items: center;
}

.hero-bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.lh-tight {
    line-height: 1.1;
}

.mw-600 {
    max-width: 600px;
}

.mw-800 {
    max-width: 800px;
}

.z-index-1 {
    z-index: 1;
}

.hero-visual {
    position: relative;
    width: 400px;
    height: 400px;
}

.hero-circle {
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    backdrop-filter: blur(10px);
}

.floating-card {
    position: absolute;
    background: white;
    padding: 1rem;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 120px;
    animation: float 6s ease-in-out infinite;
    z-index: 2;
}

.card-1 {
    top: 20%;
    left: 0;
    animation-delay: 0s;
}

.card-2 {
    bottom: 20%;
    right: 0;
    animation-delay: 3s;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

.service-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border: 1px solid rgba(0,0,0,0.05);
}

.service-card:hover {
    transform: translateY(-8px);
}

.abstract-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    width: 300px;
    height: 300px;
    margin: 0 auto;
    transform: rotate(45deg);
}

.grid-item {
    border-radius: 2rem;
    opacity: 0.8;
}

.item-1 { grid-column: 1 / 2; grid-row: 1 / 3; }
.item-2 { grid-column: 2 / 3; grid-row: 1 / 2; }
.item-3 { grid-column: 2 / 3; grid-row: 2 / 3; }

.product-card .v-img {
    transition: transform 0.5s ease;
}

.product-card:hover .v-img {
    transform: scale(1.05);
}

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.cta-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.1;
    background-image: radial-gradient(#ffffff 1px, transparent 1px);
    background-size: 20px 20px;
}
</style>
