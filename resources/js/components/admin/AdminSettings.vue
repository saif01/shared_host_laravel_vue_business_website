<template>
    <div>
        <h1 class="text-h4 mb-6">Settings</h1>
        <v-card>
            <v-card-text>
                <v-form @submit.prevent="saveSettings">
                    <v-text-field v-model="settings.site_name" label="Site Name"></v-text-field>
                    <v-text-field v-model="settings.contact_email" label="Contact Email"></v-text-field>
                    <v-textarea v-model="settings.address" label="Address"></v-textarea>
                    <v-btn type="submit" color="primary" :loading="loading">Save Settings</v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            settings: {
                site_name: '',
                contact_email: '',
                address: ''
            },
            loading: false
        };
    },
    async mounted() {
        await this.loadSettings();
    },
    methods: {
        async loadSettings() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await axios.get('/api/v1/settings', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.settings = response.data;
            } catch (error) {
                console.error('Error loading settings:', error);
            }
        },
        async saveSettings() {
            this.loading = true;
            try {
                const token = localStorage.getItem('admin_token');
                await axios.post('/api/v1/settings', {
                    settings: this.settings
                }, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                alert('Settings saved successfully');
            } catch (error) {
                alert('Error saving settings');
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

