<template>
    <div>
        <h1 class="text-h4 mb-6">Settings</h1>

        <v-tabs v-model="activeTab" class="mb-6">
            <v-tab value="general">General</v-tab>
            <v-tab value="social">Social Media</v-tab>
            <v-tab value="seo">SEO</v-tab>
            <v-tab value="email">Email</v-tab>
            <v-tab value="branding">Branding</v-tab>
        </v-tabs>

        <v-card>
            <v-card-text>
                <v-form @submit.prevent="saveSettings">
                    <!-- General Settings -->
                    <v-window v-model="activeTab">
                        <v-window-item value="general">
                            <h2 class="text-h6 mb-4">General Settings</h2>
                            <v-text-field v-model="settings.general.site_name.value" label="Site Name"
                                hint="The name of your website" persistent-hint class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.general.site_tagline.value" label="Site Tagline"
                                hint="A short tagline for your website" persistent-hint class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.general.contact_email.value" label="Contact Email"
                                type="email" hint="Main contact email address" persistent-hint
                                class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.general.contact_phone.value" label="Contact Phone"
                                hint="Main contact phone number" persistent-hint class="mb-4"></v-text-field>
                            <v-textarea v-model="settings.general.address.value" label="Address" hint="Business address"
                                persistent-hint rows="3" class="mb-4"></v-textarea>
                        </v-window-item>

                        <!-- Social Media Settings -->
                        <v-window-item value="social">
                            <h2 class="text-h6 mb-4">Social Media Links</h2>
                            <v-text-field v-model="settings.social.facebook_url.value" label="Facebook URL" type="url"
                                hint="Your Facebook page URL" persistent-hint prepend-inner-icon="mdi-facebook"
                                class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.social.twitter_url.value" label="Twitter URL" type="url"
                                hint="Your Twitter profile URL" persistent-hint prepend-inner-icon="mdi-twitter"
                                class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.social.linkedin_url.value" label="LinkedIn URL" type="url"
                                hint="Your LinkedIn company page URL" persistent-hint prepend-inner-icon="mdi-linkedin"
                                class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.social.instagram_url.value" label="Instagram URL" type="url"
                                hint="Your Instagram profile URL" persistent-hint prepend-inner-icon="mdi-instagram"
                                class="mb-4"></v-text-field>
                        </v-window-item>

                        <!-- SEO Settings -->
                        <v-window-item value="seo">
                            <h2 class="text-h6 mb-4">SEO Settings</h2>
                            <v-text-field v-model="settings.seo.meta_title.value" label="Meta Title"
                                hint="Default meta title for pages" persistent-hint class="mb-4"></v-text-field>
                            <v-textarea v-model="settings.seo.meta_description.value" label="Meta Description"
                                hint="Default meta description for pages" persistent-hint rows="3"
                                class="mb-4"></v-textarea>
                            <v-text-field v-model="settings.seo.meta_keywords.value" label="Meta Keywords"
                                hint="Default meta keywords (comma-separated)" persistent-hint
                                class="mb-4"></v-text-field>
                        </v-window-item>

                        <!-- Email Settings -->
                        <v-window-item value="email">
                            <h2 class="text-h6 mb-4">Email Settings</h2>
                            <v-text-field v-model="settings.email.smtp_host.value" label="SMTP Host"
                                hint="SMTP server hostname" persistent-hint class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.email.smtp_port.value" label="SMTP Port" type="number"
                                hint="SMTP server port (usually 587 or 465)" persistent-hint
                                class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.email.smtp_username.value" label="SMTP Username"
                                hint="SMTP authentication username" persistent-hint class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.email.smtp_password.value" label="SMTP Password"
                                type="password" hint="SMTP authentication password" persistent-hint
                                class="mb-4"></v-text-field>
                            <v-select v-model="settings.email.smtp_encryption.value" :items="['none', 'tls', 'ssl']"
                                label="SMTP Encryption" hint="Encryption method" persistent-hint
                                class="mb-4"></v-select>
                        </v-window-item>

                        <!-- Branding Settings -->
                        <v-window-item value="branding">
                            <h2 class="text-h6 mb-4">Branding</h2>
                            <v-text-field v-model="settings.branding.logo.value" label="Logo URL"
                                hint="URL to your logo image" persistent-hint class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.branding.favicon.value" label="Favicon URL"
                                hint="URL to your favicon" persistent-hint class="mb-4"></v-text-field>
                            <v-text-field v-model="settings.branding.primary_color.value" label="Primary Color"
                                type="color" hint="Primary brand color" persistent-hint class="mb-4"></v-text-field>
                        </v-window-item>
                    </v-window>

                    <v-divider class="my-6"></v-divider>

                    <div class="d-flex justify-end gap-3">
                        <v-btn @click="loadSettings" variant="outlined">Reset</v-btn>
                        <v-btn type="submit" color="primary" :loading="loading">Save Settings</v-btn>
                    </div>
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
            activeTab: 'general',
            settings: {
                general: {
                    site_name: { value: '', type: 'text', group: 'general' },
                    site_tagline: { value: '', type: 'text', group: 'general' },
                    contact_email: { value: '', type: 'email', group: 'general' },
                    contact_phone: { value: '', type: 'text', group: 'general' },
                    address: { value: '', type: 'textarea', group: 'general' },
                },
                social: {
                    facebook_url: { value: '', type: 'url', group: 'social' },
                    twitter_url: { value: '', type: 'url', group: 'social' },
                    linkedin_url: { value: '', type: 'url', group: 'social' },
                    instagram_url: { value: '', type: 'url', group: 'social' },
                },
                seo: {
                    meta_title: { value: '', type: 'text', group: 'seo' },
                    meta_description: { value: '', type: 'textarea', group: 'seo' },
                    meta_keywords: { value: '', type: 'text', group: 'seo' },
                },
                email: {
                    smtp_host: { value: '', type: 'text', group: 'email' },
                    smtp_port: { value: '', type: 'text', group: 'email' },
                    smtp_username: { value: '', type: 'text', group: 'email' },
                    smtp_password: { value: '', type: 'password', group: 'email' },
                    smtp_encryption: { value: 'tls', type: 'text', group: 'email' },
                },
                branding: {
                    logo: { value: '', type: 'image', group: 'branding' },
                    favicon: { value: '', type: 'image', group: 'branding' },
                    primary_color: { value: '#1976D2', type: 'color', group: 'branding' },
                },
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

                // Merge loaded settings with defaults
                const loadedSettings = response.data;

                // Update existing settings with loaded values
                Object.keys(loadedSettings).forEach(group => {
                    if (this.settings[group]) {
                        Object.keys(loadedSettings[group]).forEach(key => {
                            if (this.settings[group][key]) {
                                // Update value from loaded settings
                                this.settings[group][key].value = loadedSettings[group][key].value || '';
                                // Update type and group if they exist
                                if (loadedSettings[group][key].type) {
                                    this.settings[group][key].type = loadedSettings[group][key].type;
                                }
                                if (loadedSettings[group][key].group) {
                                    this.settings[group][key].group = loadedSettings[group][key].group;
                                }
                            } else {
                                // Add new setting that wasn't in defaults
                                this.settings[group][key] = {
                                    value: loadedSettings[group][key].value || '',
                                    type: loadedSettings[group][key].type || 'text',
                                    group: loadedSettings[group][key].group || group,
                                };
                            }
                        });
                    } else {
                        // Add new group that wasn't in defaults
                        this.settings[group] = {};
                        Object.keys(loadedSettings[group]).forEach(key => {
                            this.settings[group][key] = {
                                value: loadedSettings[group][key].value || '',
                                type: loadedSettings[group][key].type || 'text',
                                group: loadedSettings[group][key].group || group,
                            };
                        });
                    }
                });
            } catch (error) {
                console.error('Error loading settings:', error);
                this.showError('Failed to load settings');
            }
        },
        async saveSettings() {
            this.loading = true;
            try {
                const token = localStorage.getItem('admin_token');

                // Flatten settings for API
                const settingsToSave = {};
                Object.keys(this.settings).forEach(group => {
                    Object.keys(this.settings[group]).forEach(key => {
                        settingsToSave[key] = this.settings[group][key];
                    });
                });

                await axios.post('/api/v1/settings', {
                    settings: settingsToSave
                }, {
                    headers: { Authorization: `Bearer ${token}` }
                });

                this.showSuccess('Settings saved successfully');
            } catch (error) {
                console.error('Error saving settings:', error);
                this.showError('Error saving settings');
            } finally {
                this.loading = false;
            }
        },
        showSuccess(message) {
            if (window.Toast) {
                window.Toast.fire({
                    icon: 'success',
                    title: message
                });
            } else {
                alert(message);
            }
        },
        showError(message) {
            if (window.Toast) {
                window.Toast.fire({
                    icon: 'error',
                    title: message
                });
            } else {
                alert(message);
            }
        }
    }
};
</script>

<style scoped>
.gap-3 {
    gap: 12px;
}
</style>
