<template>
    <div>
        <v-container>
            <h1 class="text-h4 mb-6">Our Services</h1>
            <v-row>
                <v-col v-for="service in services" :key="service.id" cols="12" md="4">
                    <v-card :to="`/services/${service.slug}`">
                        <v-card-title>{{ service.title }}</v-card-title>
                        <v-card-text>{{ service.short_description }}</v-card-text>
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
            services: []
        };
    },
    async mounted() {
        await this.loadServices();
    },
    methods: {
        async loadServices() {
            try {
                const response = await axios.get('/api/public/services');
                this.services = response.data;
            } catch (error) {
                console.error('Error loading services:', error);
            }
        }
    }
};
</script>

