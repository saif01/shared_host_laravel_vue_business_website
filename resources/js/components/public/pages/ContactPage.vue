<template>
    <div class="contact-page">
        <!-- Hero Section -->
        <section class="page-hero position-relative d-flex align-center justify-center text-center">
            <div class="hero-bg-gradient"></div>
            <div class="hero-pattern"></div>
            <v-container class="position-relative z-index-2">
                <v-fade-transition appear>
                    <div>
                        <div class="glass-pill d-inline-flex align-center px-4 py-2 rounded-pill mb-6">
                            <v-icon icon="mdi-message-text-outline" color="amber-accent-4" size="small"
                                class="mr-2"></v-icon>
                            <span class="text-subtitle-2 font-weight-bold tracking-wide text-white">GET IN TOUCH</span>
                        </div>
                        <h1 class="text-h4 text-lg-h3 font-weight-black text-white mb-6 lh-tight text-shadow-sm">
                            {{ contactInfo.contact_hero_title || 'Contact Us' }}
                        </h1>
                        <p class="text-h6 text-white opacity-80 mw-600 mx-auto font-weight-light">
                            {{ contactInfo.contact_hero_subtitle }}
                        </p>
                    </div>
                </v-fade-transition>
            </v-container>
        </section>

        <!-- Contact Info Cards -->
        <section class="py-12 mt-n16 position-relative z-index-3">
            <v-container>
                <v-row>
                    <v-col cols="12" md="4">
                        <v-card class="contact-card h-100 text-center pa-8 rounded-xl elevation-6" hover>
                            <div class="icon-box mb-6 mx-auto bg-blue-lighten-5 text-primary">
                                <v-icon icon="mdi-phone-in-talk" size="32"></v-icon>
                            </div>
                            <h3 class="text-h6 font-weight-bold mb-2 text-grey-darken-3">Call Us</h3>
                            <p class="text-body-2 text-medium-emphasis mb-4">Available 24/7 for support</p>
                            <a :href="`tel:${contactInfo.contact_phone}`"
                                class="text-h6 font-weight-bold text-primary text-decoration-none hover-link">
                                {{ contactInfo.contact_phone || '+1 (234) 567-890' }}
                            </a>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card class="contact-card h-100 text-center pa-8 rounded-xl elevation-6" hover>
                            <div class="icon-box mb-6 mx-auto bg-amber-lighten-5 text-amber-darken-3">
                                <v-icon icon="mdi-email-outline" size="32"></v-icon>
                            </div>
                            <h3 class="text-h6 font-weight-bold mb-2 text-grey-darken-3">Email Us</h3>
                            <p class="text-body-2 text-medium-emphasis mb-4">We'll respond within 24 hours</p>
                            <a :href="`mailto:${contactInfo.contact_email}`"
                                class="text-h6 font-weight-bold text-primary text-decoration-none hover-link">
                                {{ contactInfo.contact_email || 'info@microcontrol.com' }}
                            </a>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card class="contact-card h-100 text-center pa-8 rounded-xl elevation-6" hover>
                            <div class="icon-box mb-6 mx-auto bg-green-lighten-5 text-green-darken-3">
                                <v-icon icon="mdi-map-marker-radius" size="32"></v-icon>
                            </div>
                            <h3 class="text-h6 font-weight-bold mb-2 text-grey-darken-3">Visit Us</h3>
                            <p class="text-body-2 text-medium-emphasis mb-4">Our Corporate Office</p>
                            <div class="text-body-1 font-weight-bold text-grey-darken-3" style="white-space: pre-line;">
                                {{ contactInfo.contact_address || '123 Power Lane, Tech City' }}
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Form & Map Section -->
        <section class="py-20 bg-grey-lighten-5 position-relative overflow-hidden">
            <div class="bg-pattern-dots"></div>
            <v-container class="position-relative z-index-2">
                <v-row>
                    <!-- Contact Form -->
                    <v-col cols="12" md="6" class="mb-8 mb-md-0">
                        <div class="pr-md-8">
                            <div class="text-overline text-primary font-weight-bold mb-2 tracking-widest">SEND A MESSAGE
                            </div>
                            <h2 class="text-h4 font-weight-bold mb-6 text-grey-darken-3">How Can We Help?</h2>
                            <p class="text-body-1 text-medium-emphasis mb-8">
                                Fill out the form below and our team will get back to you shortly.
                            </p>

                            <v-form ref="form" v-model="valid" @submit.prevent="submitForm">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.name" label="Your Name" variant="outlined"
                                            color="primary" bg-color="white"
                                            :rules="[v => !!v || 'Name is required']"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.email" label="Email Address" variant="outlined"
                                            color="primary" bg-color="white"
                                            :rules="[v => !!v || 'Email is required', v => /.+@.+\..+/.test(v) || 'E-mail must be valid']"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="form.subject" label="Subject" variant="outlined"
                                            color="primary" bg-color="white"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea v-model="form.message" label="Message" variant="outlined"
                                            color="primary" bg-color="white" rows="4"
                                            :rules="[v => !!v || 'Message is required']"></v-textarea>
                                    </v-col>
                                </v-row>
                                <v-btn type="submit" size="x-large" color="primary" rounded="pill"
                                    class="mt-4 px-8 font-weight-bold elevation-4 hover-lift" :loading="loading">
                                    Send Message
                                    <v-icon end icon="mdi-send" class="ml-2"></v-icon>
                                </v-btn>
                            </v-form>
                        </div>
                    </v-col>

                    <!-- Map -->
                    <v-col cols="12" md="6">
                        <v-card class="map-card h-100 rounded-xl overflow-hidden elevation-10 border-thin">
                            <iframe
                                :src="contactInfo.contact_map_url || 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937950147!2d-73.98731968482413!3d40.75889497932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1622646639943!5m2!1sen!2sus'"
                                width="100%" height="100%" style="border:0; min-height: 400px;" allowfullscreen=""
                                loading="lazy"></iframe>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </section>
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
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Reset form
            this.loading = false;
            this.form = { name: '', email: '', subject: '', message: '' };
            alert('Message sent successfully!'); // Replace with a proper toast notification in a real app
        }
    }
};
</script>

<style scoped>
/* Hero styles moved to app.css */

.contact-card {
    background: white;
}

/* Common card, icon-box, hover-link, and map-card styles moved to app.css */

/* Responsive Styles */
@media (max-width: 960px) {
    .contact-card {
        margin-bottom: 24px;
    }

    .icon-box {
        width: 70px;
        height: 70px;
    }

    .icon-box .v-icon {
        font-size: 28px !important;
    }

    .map-card {
        margin-top: 24px;
        min-height: 350px;
    }

    .map-card iframe {
        min-height: 350px !important;
    }
}

@media (max-width: 600px) {
    .page-hero {
        padding: 40px 0;
    }

    .contact-card {
        padding: 24px !important;
    }

    .icon-box {
        width: 60px;
        height: 60px;
        margin-bottom: 16px !important;
    }

    .icon-box .v-icon {
        font-size: 24px !important;
    }

    .map-card {
        min-height: 300px;
    }

    .map-card iframe {
        min-height: 300px !important;
    }

    .pr-md-8 {
        padding-right: 0 !important;
    }
}
</style>
