<template>
    <div class="contact-page">
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
                            <v-icon icon="mdi-message-text-outline" color="amber-accent-4" size="small"
                                class="mr-2"></v-icon>
                            <span class="text-subtitle-2 font-weight-bold tracking-wide text-white">GET IN TOUCH</span>
                        </div>
                        <h1
                            class="text-h4 text-lg-h2 font-weight-black text-white mb-6 lh-tight text-shadow-sm animate-slide-up">
                            {{ contactInfo.contact_hero_title || defaultTitle }}
                        </h1>
                        <p
                            class="text-h6 text-white opacity-90 mw-700 mx-auto font-weight-light animate-slide-up-delay">
                            {{ contactInfo.contact_hero_subtitle || defaultSubtitle }}
                        </p>
                    </div>
                </v-fade-transition>
            </v-container>
        </section>

        <!-- Contact Info Cards -->
        <section class="py-12 mt-n16 position-relative z-index-3">
            <v-container>
                <v-row>
                    <v-col cols="12" md="4" v-for="(card, index) in contactCards" :key="index">
                        <v-card
                            class="contact-card-modern h-100 text-center pa-8 rounded-xl elevation-8 overflow-hidden"
                            :style="{ animationDelay: `${index * 0.1}s` }">
                            <!-- Card gradient background -->
                            <div class="card-gradient" :class="`gradient-${index + 1}`"></div>

                            <div class="position-relative">
                                <div class="icon-box-modern mb-6 mx-auto" :class="card.iconClass">
                                    <div class="icon-bg"></div>
                                    <v-icon :icon="card.icon" size="36" class="icon-main"></v-icon>
                                </div>
                                <h3 class="text-h5 font-weight-bold mb-3 text-grey-darken-4">{{ card.title }}</h3>
                                <p class="text-body-2 text-medium-emphasis mb-5">{{ card.subtitle }}</p>
                                <a :href="card.href"
                                    class="contact-link text-h6 font-weight-bold text-decoration-none d-inline-flex align-center">
                                    <span>{{ card.value }}</span>
                                    <v-icon icon="mdi-arrow-right" size="20" class="ml-2 arrow-icon"></v-icon>
                                </a>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Form & Map Section -->
        <section class="py-20 bg-gradient-light position-relative overflow-hidden">
            <div class="bg-pattern-dots"></div>

            <!-- Decorative elements -->
            <div class="decorative-circle circle-1"></div>
            <div class="decorative-circle circle-2"></div>

            <v-container class="position-relative z-index-2">
                <v-row align="stretch">
                    <!-- Contact Form -->
                    <v-col cols="12" md="6" class="mb-8 mb-md-0">
                        <div class="pr-md-8">
                            <div class="d-inline-flex align-center mb-4">
                                <div class="badge-accent"></div>
                                <span class="text-overline text-primary font-weight-bold tracking-widest ml-3">SEND A
                                    MESSAGE</span>
                            </div>
                            <h2 class="text-h3 font-weight-black mb-4 text-grey-darken-4">How Can We Help?</h2>
                            <p class="text-body-1 text-grey-darken-1 mb-8 lh-relaxed">
                                Fill out the form below and our team will get back to you within 24 hours.
                            </p>

                            <v-form ref="form" v-model="valid" @submit.prevent="submitForm">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.name" label="Your Name" variant="outlined"
                                            color="primary" bg-color="white" density="comfortable"
                                            class="form-field-modern" prepend-inner-icon="mdi-account-outline"
                                            :rules="[v => !!v || 'Name is required']">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.email" label="Email Address" variant="outlined"
                                            color="primary" bg-color="white" density="comfortable"
                                            class="form-field-modern" prepend-inner-icon="mdi-email-outline"
                                            :rules="[v => !!v || 'Email is required', v => /.+@.+\..+/.test(v) || 'E-mail must be valid']">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="form.subject" label="Subject" variant="outlined"
                                            color="primary" bg-color="white" density="comfortable"
                                            class="form-field-modern" prepend-inner-icon="mdi-text-box-outline">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea v-model="form.message" label="Your Message" variant="outlined"
                                            color="primary" bg-color="white" rows="5" class="form-field-modern"
                                            prepend-inner-icon="mdi-message-outline"
                                            :rules="[v => !!v || 'Message is required']">
                                        </v-textarea>
                                    </v-col>
                                </v-row>
                                <v-btn type="submit" size="x-large" color="primary" rounded="pill"
                                    class="mt-4 px-10 py-6 font-weight-bold text-h6 btn-modern elevation-8"
                                    :loading="loading">
                                    <span>Send Message</span>
                                    <v-icon end icon="mdi-send" size="24" class="ml-2"></v-icon>
                                </v-btn>
                            </v-form>
                        </div>
                    </v-col>

                    <!-- Map -->
                    <v-col cols="12" md="6">
                        <v-card class="map-card-modern h-100 rounded-xl overflow-hidden elevation-12 position-relative">
                            <div class="map-overlay"></div>
                            <iframe
                                :src="contactInfo.contact_map_url || 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937950147!2d-73.98731968482413!3d40.75889497932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1622646639943!5m2!1sen!2sus'"
                                width="100%" height="100%" style="border:0; min-height: 500px;" allowfullscreen=""
                                loading="lazy">
                            </iframe>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Success Snackbar -->
        <v-snackbar v-model="showSuccess" :timeout="4000" color="success" location="top" class="success-snackbar">
            <div class="d-flex align-center">
                <v-icon icon="mdi-check-circle" class="mr-3" size="24"></v-icon>
                <div>
                    <div class="font-weight-bold">Message Sent Successfully!</div>
                    <div class="text-caption">We'll get back to you within 24 hours.</div>
                </div>
            </div>
        </v-snackbar>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ContactPage',
    data() {
        return {
            valid: false,
            loading: false,
            showSuccess: false,
            defaultTitle: "Let's Start a Conversation",
            defaultSubtitle: "We're here to help and answer any questions you might have",
            contactInfo: {
                contact_email: '',
                contact_phone: '',
                contact_address: '',
                contact_hero_title: '',
                contact_hero_subtitle: '',
                contact_map_url: ''
            },
            form: {
                name: '',
                email: '',
                subject: '',
                message: ''
            }
        };
    },
    computed: {
        contactCards() {
            return [
                {
                    icon: 'mdi-phone-in-talk',
                    iconClass: 'icon-blue',
                    title: 'Call Us',
                    subtitle: 'Available 24/7 for support',
                    value: this.contactInfo.contact_phone || '+1 (234) 567-890',
                    href: `tel:${this.contactInfo.contact_phone}`
                },
                {
                    icon: 'mdi-email-outline',
                    iconClass: 'icon-amber',
                    title: 'Email Us',
                    subtitle: 'We\'ll respond within 24 hours',
                    value: this.contactInfo.contact_email || 'info@microcontrol.com',
                    href: `mailto:${this.contactInfo.contact_email}`
                },
                {
                    icon: 'mdi-map-marker-radius',
                    iconClass: 'icon-green',
                    title: 'Visit Us',
                    subtitle: 'Our Corporate Office',
                    value: this.contactInfo.contact_address || '123 Power Lane, Tech City',
                    href: '#map'
                }
            ];
        }
    },
    mounted() {
        this.loadContactInfo();
    },
    methods: {
        async loadContactInfo() {
            try {
                const response = await axios.get('/api/openapi/settings', {
                    params: { group: 'contact_page' }
                });
                // Response is a key-value object, merge it with contactInfo
                if (response.data) {
                    this.contactInfo = { ...this.contactInfo, ...response.data };
                }
            } catch (error) {
                console.error('Error loading contact info:', error);
                // Keep default values if API fails
            }
        },
        async submitForm() {
            if (!this.valid) return;

            this.loading = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1500));

                // Reset form
                this.form = { name: '', email: '', subject: '', message: '' };
                this.$refs.form.resetValidation();

                // Show success message
                this.showSuccess = true;
            } catch (error) {
                console.error('Error submitting form:', error);
                // You can add error handling here
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
/* ============================================
   HERO SECTION MODERN STYLES
   ============================================ */

/* Animated gradient orbs */
.gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.3;
    animation: float-orb 20s ease-in-out infinite;
}

.orb-1 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    top: -10%;
    left: -5%;
    animation-delay: 0s;
}

.orb-2 {
    width: 350px;
    height: 350px;
    background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
    top: 20%;
    right: -5%;
    animation-delay: 3s;
}

.orb-3 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
    bottom: -10%;
    left: 40%;
    animation-delay: 6s;
}

@keyframes float-orb {

    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }

    33% {
        transform: translate(30px, -30px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

/* Pulse dot animation */
.pulse-dot {
    width: 8px;
    height: 8px;
    background: #f59e0b;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
    box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
}

@keyframes pulse {

    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }

    50% {
        transform: scale(1.3);
        opacity: 0.7;
    }
}

/* Glass pill enhanced */
.glass-pill {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

/* Animations */
.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out;
}

.animate-slide-up-delay {
    animation: slide-up 0.8s ease-out 0.2s both;
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mw-700 {
    max-width: 700px;
}

/* ============================================
   CONTACT CARDS MODERN STYLES
   ============================================ */

.contact-card-modern {
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fade-in-up 0.6s ease-out both;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.contact-card-modern:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
    border-color: transparent;
}

/* Card gradient backgrounds */
.card-gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    transition: height 0.4s ease;
}

.contact-card-modern:hover .card-gradient {
    height: 100%;
    opacity: 0.05;
}

.gradient-1 {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

.gradient-2 {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.gradient-3 {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Icon box modern */
.icon-box-modern {
    width: 90px;
    height: 90px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.icon-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    opacity: 0.1;
    transition: all 0.4s ease;
}

.icon-main {
    position: relative;
    z-index: 1;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.icon-blue .icon-bg {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

.icon-blue .icon-main {
    color: #2563eb !important;
}

.icon-amber .icon-bg {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.icon-amber .icon-main {
    color: #f59e0b !important;
}

.icon-green .icon-bg {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.icon-green .icon-main {
    color: #10b981 !important;
}

.contact-card-modern:hover .icon-box-modern {
    transform: scale(1.15) rotate(10deg);
}

.contact-card-modern:hover .icon-bg {
    opacity: 0.2;
    transform: scale(1.2);
}

.contact-card-modern:hover .icon-main {
    transform: scale(1.1);
}

/* Contact link */
.contact-link {
    color: #1e293b;
    transition: all 0.3s ease;
    position: relative;
}

.contact-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #2563eb, #f59e0b);
    transition: width 0.3s ease;
}

.contact-link:hover {
    color: #2563eb;
}

.contact-link:hover::after {
    width: 100%;
}

.arrow-icon {
    transition: transform 0.3s ease;
}

.contact-link:hover .arrow-icon {
    transform: translateX(5px);
}

/* ============================================
   FORM SECTION MODERN STYLES
   ============================================ */

.bg-gradient-light {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Badge accent */
.badge-accent {
    width: 4px;
    height: 24px;
    background: linear-gradient(180deg, #2563eb 0%, #f59e0b 100%);
    border-radius: 2px;
}

/* Decorative circles */
.decorative-circle {
    position: absolute;
    border-radius: 50%;
    border: 2px solid rgba(37, 99, 235, 0.1);
    pointer-events: none;
}

.circle-1 {
    width: 400px;
    height: 400px;
    top: -100px;
    right: -100px;
}

.circle-2 {
    width: 300px;
    height: 300px;
    bottom: -80px;
    left: -80px;
    border-color: rgba(245, 158, 11, 0.1);
}

/* Form fields modern */
.form-field-modern :deep(.v-field) {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    border-radius: 12px !important;
}

.form-field-modern :deep(.v-field:hover) {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.form-field-modern :deep(.v-field--focused) {
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15) !important;
}

.form-field-modern :deep(.v-field__prepend-inner) {
    color: #94a3b8;
    transition: color 0.3s ease;
}

.form-field-modern :deep(.v-field--focused .v-field__prepend-inner) {
    color: #2563eb;
}

/* Button modern */
.btn-modern {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-modern:hover::before {
    width: 300px;
    height: 300px;
}

.btn-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(37, 99, 235, 0.4) !important;
}

.btn-modern:active {
    transform: translateY(-1px);
}

/* Map card modern */
.map-card-modern {
    border: 3px solid white;
    transition: all 0.4s ease;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
}

.map-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
}

.map-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
    pointer-events: none;
    z-index: 1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.map-card-modern:hover .map-overlay {
    opacity: 1;
}

/* ============================================
   RESPONSIVE STYLES
   ============================================ */

@media (max-width: 960px) {

    .orb-1,
    .orb-2,
    .orb-3 {
        width: 250px;
        height: 250px;
    }

    .contact-card-modern {
        margin-bottom: 24px;
    }

    .icon-box-modern {
        width: 75px;
        height: 75px;
    }

    .icon-main {
        font-size: 28px !important;
    }

    .map-card-modern iframe {
        min-height: 400px !important;
    }

    .decorative-circle {
        display: none;
    }
}

@media (max-width: 600px) {
    .page-hero {
        padding: 60px 0 !important;
    }

    .orb-1,
    .orb-2,
    .orb-3 {
        width: 200px;
        height: 200px;
        filter: blur(60px);
    }

    .glass-pill {
        padding: 12px 20px !important;
    }

    .contact-card-modern {
        padding: 32px 24px !important;
    }

    .icon-box-modern {
        width: 70px;
        height: 70px;
        margin-bottom: 24px !important;
    }

    .icon-main {
        font-size: 26px !important;
    }

    .map-card-modern {
        min-height: 350px;
        margin-top: 32px;
    }

    .map-card-modern iframe {
        min-height: 350px !important;
    }

    .pr-md-8 {
        padding-right: 0 !important;
    }

    .btn-modern {
        width: 100%;
    }
}

/* ============================================
   SUCCESS SNACKBAR
   ============================================ */

.success-snackbar :deep(.v-snackbar__wrapper) {
    border-radius: 12px !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
}

.success-snackbar :deep(.v-snackbar__content) {
    padding: 20px 24px !important;
}
</style>
