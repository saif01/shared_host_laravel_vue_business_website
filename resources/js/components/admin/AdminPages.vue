<template>
    <div>
        <h1 class="text-h4 mb-6">Pages Management</h1>
        <v-btn color="primary" class="mb-4" @click="showDialog = true">Create Page</v-btn>
        <v-table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Type</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="page in pages" :key="page.id">
                    <td>{{ page.title }}</td>
                    <td>{{ page.slug }}</td>
                    <td>{{ page.page_type }}</td>
                    <td>{{ page.published ? 'Yes' : 'No' }}</td>
                    <td>
                        <v-btn size="small" @click="editPage(page)">Edit</v-btn>
                        <v-btn size="small" color="error" @click="deletePage(page.id)">Delete</v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            pages: [],
            showDialog: false
        };
    },
    async mounted() {
        await this.loadPages();
    },
    methods: {
        async loadPages() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await axios.get('/api/v1/pages', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.pages = response.data;
            } catch (error) {
                console.error('Error loading pages:', error);
            }
        },
        editPage(page) {
            // TODO: Implement edit functionality
            alert('Edit functionality coming soon');
        },
        async deletePage(id) {
            if (confirm('Are you sure you want to delete this page?')) {
                try {
                    const token = localStorage.getItem('admin_token');
                    await axios.delete(`/api/v1/pages/${id}`, {
                        headers: { Authorization: `Bearer ${token}` }
                    });
                    await this.loadPages();
                } catch (error) {
                    alert('Error deleting page');
                }
            }
        }
    }
};
</script>

