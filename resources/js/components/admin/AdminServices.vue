<template>
    <div>
        <h1 class="text-h4 mb-6">Services Management</h1>
        <v-btn color="primary" class="mb-4">Create Service</v-btn>
        <v-table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="service in services" :key="service.id">
                    <td>{{ service.title }}</td>
                    <td>{{ service.slug }}</td>
                    <td>{{ service.published ? 'Yes' : 'No' }}</td>
                    <td>
                        <v-btn size="small">Edit</v-btn>
                        <v-btn size="small" color="error" @click="deleteService(service.id)">Delete</v-btn>
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
            services: []
        };
    },
    async mounted() {
        await this.loadServices();
    },
    methods: {
        async loadServices() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await axios.get('/api/v1/services', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.services = response.data;
            } catch (error) {
                console.error('Error loading services:', error);
            }
        },
        async deleteService(id) {
            if (confirm('Are you sure?')) {
                try {
                    const token = localStorage.getItem('admin_token');
                    await axios.delete(`/api/v1/services/${id}`, {
                        headers: { Authorization: `Bearer ${token}` }
                    });
                    await this.loadServices();
                } catch (error) {
                    alert('Error deleting service');
                }
            }
        }
    }
};
</script>

