<template>
    <div>
        <h1 class="text-h4 mb-6">Products Management</h1>
        <v-btn color="primary" class="mb-4">Create Product</v-btn>
        <v-table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.title }}</td>
                    <td>{{ product.sku || '-' }}</td>
                    <td>{{ product.price ? '$' + product.price : '-' }}</td>
                    <td>{{ product.published ? 'Yes' : 'No' }}</td>
                    <td>
                        <v-btn size="small">Edit</v-btn>
                        <v-btn size="small" color="error" @click="deleteProduct(product.id)">Delete</v-btn>
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
            products: []
        };
    },
    async mounted() {
        await this.loadProducts();
    },
    methods: {
        async loadProducts() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await axios.get('/api/v1/products', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.products = response.data;
            } catch (error) {
                console.error('Error loading products:', error);
            }
        },
        async deleteProduct(id) {
            if (confirm('Are you sure?')) {
                try {
                    const token = localStorage.getItem('admin_token');
                    await axios.delete(`/api/v1/products/${id}`, {
                        headers: { Authorization: `Bearer ${token}` }
                    });
                    await this.loadProducts();
                } catch (error) {
                    alert('Error deleting product');
                }
            }
        }
    }
};
</script>

