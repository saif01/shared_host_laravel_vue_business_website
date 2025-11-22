<template>
    <div>
        <v-container>
            <h1 class="text-h4 mb-6">Contact Us</h1>
            <v-row>
                <v-col cols="12" md="8">
                    <v-card>
                        <v-card-title>Send us a Message</v-card-title>
                        <v-card-text>
                            <v-form @submit.prevent="submit">
                                <v-text-field v-model="form.name" label="Name" required></v-text-field>
                                <v-text-field v-model="form.email" label="Email" type="email" required></v-text-field>
                                <v-text-field v-model="form.phone" label="Phone"></v-text-field>
                                <v-text-field v-model="form.subject" label="Subject"></v-text-field>
                                <v-textarea v-model="form.message" label="Message" required></v-textarea>
                                <v-btn type="submit" color="primary" :loading="loading">Send Message</v-btn>
                            </v-form>
                        </v-card-text>
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
            form: {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: ''
            },
            loading: false
        };
    },
    methods: {
        async submit() {
            this.loading = true;
            try {
                await axios.post('/api/public/contact', this.form);
                alert('Thank you for your message! We will get back to you soon.');
                this.form = {
                    name: '',
                    email: '',
                    phone: '',
                    subject: '',
                    message: ''
                };
            } catch (error) {
                alert('Error sending message. Please try again.');
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

