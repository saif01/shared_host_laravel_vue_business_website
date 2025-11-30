<template>
    <v-dialog v-model="dialog" max-width="1200" scrollable persistent>
        <v-card>
            <v-card-title class="d-flex align-center justify-space-between bg-primary text-white pa-4">
                <span class="text-h5 font-weight-bold">
                    {{ editingAnnouncement ? 'Edit Announcement' : 'Create New Announcement' }}
                </span>
                <v-btn icon="mdi-close" variant="text" color="white" @click="closeDialog"
                    :disabled="saving || loading"></v-btn>
            </v-card-title>

            <v-card-text class="pa-0">
                <!-- Loading State -->
                <div v-if="loading" class="d-flex align-center justify-center pa-12">
                    <div class="text-center">
                        <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                        <p class="text-body-1 text-medium-emphasis mt-4">Loading announcement data...</p>
                    </div>
                </div>

                <!-- Form Content -->
                <v-form ref="formRef" v-else>
                    <v-tabs v-model="activeTab" bg-color="grey-lighten-4">
                        <v-tab value="basic">Basic Information</v-tab>
                        <v-tab value="content">Content</v-tab>
                        <v-tab value="settings">Settings & Schedule</v-tab>
                        <v-tab value="seo">SEO & Meta</v-tab>
                    </v-tabs>

                    <v-window v-model="activeTab">
                        <!-- Basic Information Tab -->
                        <v-window-item value="basic">
                            <div class="pa-6">
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="form.title" label="Announcement Title *" variant="outlined"
                                            :rules="[v => !!v || 'Title is required']" hint="Enter the announcement title"
                                            @blur="generateSlug" persistent-hint></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.slug" label="Slug *" variant="outlined"
                                            :rules="[v => !!v || 'Slug is required', v => !v || /^[a-z0-9-]+$/.test(v) || 'Slug can only contain lowercase letters, numbers, and hyphens']"
                                            hint="URL-friendly version (auto-generated from title)" persistent-hint>
                                            <template v-slot:append>
                                                <v-btn icon="mdi-refresh" size="small" variant="text"
                                                    @click="generateSlug" :disabled="!form.title"></v-btn>
                                            </template>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.type" :items="typeOptions" label="Type *" variant="outlined"
                                            :rules="[v => !!v || 'Type is required']" hint="Announcement category"
                                            persistent-hint></v-select>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.display_type" :items="displayTypeOptions" 
                                            label="Display Type *" variant="outlined"
                                            :rules="[v => !!v || 'Display type is required']" 
                                            hint="How the announcement will be displayed"
                                            persistent-hint></v-select>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.priority" label="Priority" type="number"
                                            variant="outlined" hint="Higher priority shows first (0-999)"
                                            persistent-hint min="0" max="999"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea v-model="form.short_description" label="Short Description"
                                            variant="outlined" rows="3"
                                            hint="Brief description shown in listings"
                                            persistent-hint></v-textarea>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-window-item>

                        <!-- Content Tab -->
                        <v-window-item value="content">
                            <div class="pa-6">
                                <v-row>
                                    <v-col cols="12">
                                        <v-label class="mb-2">Full Content</v-label>
                                        <RichTextEditor v-model="form.content" :active="activeTab === 'content'"
                                            placeholder="Enter detailed announcement content..." />
                                    </v-col>
                                    <v-col cols="12">
                                        <v-divider class="my-4"></v-divider>
                                        <h3 class="text-h6 font-weight-bold mb-4">Media</h3>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-label class="mb-2">Image</v-label>
                                        <v-card variant="outlined" class="pa-4">
                                            <div v-if="imagePreview || form.image" class="mb-4">
                                                <v-img :src="imagePreview || resolveImageUrl(form.image)"
                                                    max-width="300" max-height="300" class="rounded elevation-2"
                                                    cover></v-img>
                                                <v-btn color="error" size="small" variant="text" class="mt-2"
                                                    @click="removeImage" prepend-icon="mdi-delete">
                                                    Remove Image
                                                </v-btn>
                                            </div>
                                            <v-file-input v-model="imageFile" accept="image/*"
                                                label="Select Image" variant="outlined"
                                                prepend-inner-icon="mdi-image" show-size clearable
                                                @update:model-value="handleImageSelect">
                                                <template v-slot:append>
                                                    <v-progress-circular v-if="uploadingImage" indeterminate
                                                        color="primary" size="24"></v-progress-circular>
                                                </template>
                                            </v-file-input>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-label class="mb-2">Video (URL or file path)</v-label>
                                        <v-text-field v-model="form.video" label="Video URL or Path" variant="outlined"
                                            hint="Enter video URL or upload file path" persistent-hint></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-divider class="my-4"></v-divider>
                                        <h3 class="text-h6 font-weight-bold mb-4">External Link</h3>
                                    </v-col>
                                    <v-col cols="12" md="10">
                                        <v-text-field v-model="form.external_link" label="External Link URL" variant="outlined"
                                            hint="Optional link to external page" persistent-hint type="url"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-switch v-model="form.open_in_new_tab" label="Open in new tab" color="primary"
                                            hint="Open link in new browser tab" persistent-hint></v-switch>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-window-item>

                        <!-- Settings & Schedule Tab -->
                        <v-window-item value="settings">
                            <div class="pa-6">
                                <v-row>
                                    <v-col cols="12">
                                        <h3 class="text-h6 font-weight-bold mb-4">Schedule</h3>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.start_date" label="Start Date & Time" variant="outlined"
                                            type="datetime-local" hint="When announcement becomes active"
                                            persistent-hint></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.end_date" label="End Date & Time" variant="outlined"
                                            type="datetime-local" hint="When announcement expires"
                                            persistent-hint :min="form.start_date"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-divider class="my-4"></v-divider>
                                        <h3 class="text-h6 font-weight-bold mb-4">Status & Settings</h3>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-switch v-model="form.is_active" label="Active" color="success"
                                            hint="Make this announcement active"
                                            persistent-hint></v-switch>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.language" label="Language" variant="outlined"
                                            hint="Language code (e.g., en, ar) - optional for multi-language"
                                            persistent-hint></v-text-field>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-window-item>

                        <!-- SEO & Meta Tab -->
                        <v-window-item value="seo">
                            <div class="pa-6">
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="form.meta_title" label="Meta Title" variant="outlined"
                                            hint="SEO title for search engines (recommended: 50-60 characters)"
                                            persistent-hint :counter="60"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea v-model="form.meta_description" label="Meta Description"
                                            variant="outlined" rows="4"
                                            hint="SEO description for search results (recommended: 150-160 characters)"
                                            persistent-hint :counter="160"></v-textarea>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="form.meta_keywords" label="Meta Keywords"
                                            variant="outlined"
                                            hint="Comma-separated keywords for SEO"
                                            persistent-hint></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-label class="mb-2">Open Graph Image</v-label>
                                        <v-card variant="outlined" class="pa-4">
                                            <div v-if="ogImagePreview || form.og_image" class="mb-4">
                                                    <v-img :src="ogImagePreview || resolveImageUrl(form.og_image)"
                                                    max-width="300" max-height="300" class="rounded elevation-2"
                                                    cover></v-img>
                                                <v-btn color="error" size="small" variant="text" class="mt-2"
                                                    @click="removeOgImage" prepend-icon="mdi-delete">
                                                    Remove Image
                                                </v-btn>
                                            </div>
                                            <v-file-input v-model="ogImageFile" accept="image/*"
                                                label="Select Open Graph Image" variant="outlined"
                                                prepend-inner-icon="mdi-image" show-size clearable
                                                @update:model-value="handleOgImageSelect">
                                                <template v-slot:append>
                                                    <v-progress-circular v-if="uploadingOgImage" indeterminate
                                                        color="primary" size="24"></v-progress-circular>
                                                </template>
                                            </v-file-input>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-window-item>
                    </v-window>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions class="pa-4">
                <v-spacer></v-spacer>
                <v-btn color="grey" variant="text" @click="closeDialog" :disabled="saving">
                    Cancel
                </v-btn>
                <v-btn color="primary" variant="elevated" @click="saveAnnouncement" :loading="saving"
                    :disabled="loading">
                    {{ editingAnnouncement ? 'Update' : 'Create' }} Announcement
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import adminPaginationMixin from '../../../mixins/adminPaginationMixin';
import RichTextEditor from '../../common/RichTextEditor.vue';
import { resolveUploadUrl } from '../../../utils/uploads';

export default {
    name: 'AnnouncementFormDialog',
    components: {
        RichTextEditor
    },
    mixins: [adminPaginationMixin],
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        editingAnnouncement: {
            type: Object,
            default: null
        }
    },
    emits: ['update:modelValue', 'saved'],
    data() {
        return {
            activeTab: 'basic',
            loading: false,
            saving: false,
            imageFile: null,
            imagePreview: null,
            uploadingImage: false,
            imageError: null,
            ogImageFile: null,
            ogImagePreview: null,
            uploadingOgImage: false,
            ogImageError: null,
            typeOptions: [
                { title: 'Company News', value: 'company_news' },
                { title: 'Offers / Promotions', value: 'offers_promotions' },
                { title: 'Events', value: 'events' },
                { title: 'Holidays', value: 'holidays' },
                { title: 'Urgent Alerts', value: 'urgent_alerts' }
            ],
            displayTypeOptions: [
                { title: 'Slider Banner', value: 'slider_banner' },
                { title: 'Popup', value: 'popup' },
                { title: 'Sidebar Ticker', value: 'sidebar_ticker' },
                { title: 'Page Section', value: 'page_section' }
            ],
            form: {
                title: '',
                slug: '',
                content: '',
                short_description: '',
                type: 'company_news',
                display_type: 'slider_banner',
                image: '',
                video: '',
                external_link: '',
                open_in_new_tab: false,
                start_date: '',
                end_date: '',
                is_active: true,
                priority: 0,
                language: '',
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                og_image: ''
            }
        };
    },
    computed: {
        dialog: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },
    watch: {
        modelValue(newVal) {
            if (newVal) {
                if (this.editingAnnouncement) {
                    this.loadAnnouncementData();
                } else {
                    this.resetForm();
                }
            }
        }
    },
    methods: {
        resolveImageUrl(value) {
            return resolveUploadUrl(value);
        },
        resetForm() {
            this.form = {
                title: '',
                slug: '',
                content: '',
                short_description: '',
                type: 'company_news',
                display_type: 'slider_banner',
                image: '',
                video: '',
                external_link: '',
                open_in_new_tab: false,
                start_date: '',
                end_date: '',
                is_active: true,
                priority: 0,
                language: '',
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                og_image: ''
            };
            this.imageFile = null;
            this.imagePreview = null;
            this.ogImageFile = null;
            this.ogImagePreview = null;
            this.activeTab = 'basic';
        },
        async loadAnnouncementData() {
            if (!this.editingAnnouncement) return;

            try {
                this.loading = true;
                const response = await this.$axios.get(`/api/v1/announcements/${this.editingAnnouncement.id}`, {
                    headers: this.getAuthHeaders()
                });

                const announcement = response.data;
                this.form = {
                    title: announcement.title || '',
                    slug: announcement.slug || '',
                    content: announcement.content || '',
                    short_description: announcement.short_description || '',
                    type: announcement.type || 'company_news',
                    display_type: announcement.display_type || 'slider_banner',
                    image: announcement.image || '',
                    video: announcement.video || '',
                    external_link: announcement.external_link || '',
                    open_in_new_tab: announcement.open_in_new_tab || false,
                    start_date: announcement.start_date ? this.formatDateTimeForInput(announcement.start_date) : '',
                    end_date: announcement.end_date ? this.formatDateTimeForInput(announcement.end_date) : '',
                    is_active: announcement.is_active !== undefined ? announcement.is_active : true,
                    priority: announcement.priority || 0,
                    language: announcement.language || '',
                    meta_title: announcement.meta_title || '',
                    meta_description: announcement.meta_description || '',
                    meta_keywords: announcement.meta_keywords || '',
                    og_image: announcement.og_image || ''
                };
            } catch (error) {
                this.handleApiError(error, 'Failed to load announcement data');
                this.closeDialog();
            } finally {
                this.loading = false;
            }
        },
        formatDateTimeForInput(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        },
        generateSlug() {
            if (this.form.title && !this.editingAnnouncement) {
                this.form.slug = this.form.title.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        },
        async handleImageSelect(file) {
            if (!file || !file[0]) {
                this.imageFile = null;
                this.imagePreview = null;
                return;
            }

            this.imageFile = file[0];
            this.imageError = null;

            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(this.imageFile);

            // Upload image
            await this.uploadImage();
        },
        async uploadImage() {
            if (!this.imageFile) return;

            try {
                this.uploadingImage = true;
                this.imageError = null;

                const formData = new FormData();
                formData.append('file', this.imageFile);
                formData.append('folder', 'announcements');

                const response = await this.$axios.post('/api/v1/upload', formData, {
                    headers: {
                        ...this.getAuthHeaders(),
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.form.image = response.data.path || response.data.url;
                this.imageFile = null;
            } catch (error) {
                this.handleApiError(error, 'Failed to upload image');
                this.imageError = error.response?.data?.message || 'Upload failed';
            } finally {
                this.uploadingImage = false;
            }
        },
        removeImage() {
            this.form.image = '';
            this.imagePreview = null;
            this.imageFile = null;
        },
        async handleOgImageSelect(file) {
            if (!file || !file[0]) {
                this.ogImageFile = null;
                this.ogImagePreview = null;
                return;
            }

            this.ogImageFile = file[0];
            this.ogImageError = null;

            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.ogImagePreview = e.target.result;
            };
            reader.readAsDataURL(this.ogImageFile);

            // Upload image
            await this.uploadOgImage();
        },
        async uploadOgImage() {
            if (!this.ogImageFile) return;

            try {
                this.uploadingOgImage = true;
                this.ogImageError = null;

                const formData = new FormData();
                formData.append('file', this.ogImageFile);
                formData.append('folder', 'announcements');

                const response = await this.$axios.post('/api/v1/upload', formData, {
                    headers: {
                        ...this.getAuthHeaders(),
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.form.og_image = response.data.path || response.data.url;
                this.ogImageFile = null;
            } catch (error) {
                this.handleApiError(error, 'Failed to upload OG image');
                this.ogImageError = error.response?.data?.message || 'Upload failed';
            } finally {
                this.uploadingOgImage = false;
            }
        },
        removeOgImage() {
            this.form.og_image = '';
            this.ogImagePreview = null;
            this.ogImageFile = null;
        },
        async saveAnnouncement() {
            const { valid } = await this.$refs.formRef.validate();
            if (!valid) {
                this.showError('Please fill in all required fields');
                return;
            }

            try {
                this.saving = true;

                const payload = { ...this.form };
                
                // Convert datetime-local format to ISO string for API
                if (payload.start_date) {
                    payload.start_date = new Date(payload.start_date).toISOString();
                }
                if (payload.end_date) {
                    payload.end_date = new Date(payload.end_date).toISOString();
                }

                let response;
                if (this.editingAnnouncement) {
                    response = await this.$axios.put(`/api/v1/announcements/${this.editingAnnouncement.id}`, payload, {
                        headers: this.getAuthHeaders()
                    });
                } else {
                    response = await this.$axios.post('/api/v1/announcements', payload, {
                        headers: this.getAuthHeaders()
                    });
                }

                this.showSuccess(this.editingAnnouncement ? 'Announcement updated successfully' : 'Announcement created successfully');
                this.$emit('saved', response.data);
                this.closeDialog();
            } catch (error) {
                this.handleApiError(error, this.editingAnnouncement ? 'Failed to update announcement' : 'Failed to create announcement');
            } finally {
                this.saving = false;
            }
        },
        closeDialog() {
            if (!this.saving && !this.loading) {
                this.resetForm();
                this.dialog = false;
            }
        }
    }
};
</script>

