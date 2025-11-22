<template>
    <div>
        <h1 class="text-h4 mb-6">Dashboard</h1>
        
        <v-row>
            <v-col cols="12" md="3">
                <v-card>
                    <v-card-text>
                        <div class="text-h6">Total Pages</div>
                        <div class="text-h3">{{ stats.pages }}</div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card>
                    <v-card-text>
                        <div class="text-h6">Total Services</div>
                        <div class="text-h3">{{ stats.services }}</div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card>
                    <v-card-text>
                        <div class="text-h6">Total Products</div>
                        <div class="text-h3">{{ stats.products }}</div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card>
                    <v-card-text>
                        <div class="text-h6">New Leads</div>
                        <div class="text-h3">{{ stats.leads }}</div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <v-row class="mt-4">
            <v-col cols="12">
                <v-card>
                    <v-card-title>Recent Leads</v-card-title>
                    <v-card-text>
                        <v-table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lead in recentLeads" :key="lead.id">
                                    <td>{{ lead.name }}</td>
                                    <td>{{ lead.email }}</td>
                                    <td>{{ lead.type }}</td>
                                    <td>{{ lead.status }}</td>
                                    <td>{{ new Date(lead.created_at).toLocaleDateString() }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            stats: {
                pages: 0,
                services: 0,
                products: 0,
                leads: 0,
            },
            recentLeads: []
        };
    },
    async mounted() {
        await this.loadDashboard();
    },
    methods: {
        async loadDashboard() {
            try {
                const token = localStorage.getItem('admin_token');
                const [pagesRes, servicesRes, productsRes, leadsRes] = await Promise.all([
                    axios.get('/api/v1/pages', { headers: { Authorization: `Bearer ${token}` } }),
                    axios.get('/api/v1/services', { headers: { Authorization: `Bearer ${token}` } }),
                    axios.get('/api/v1/products', { headers: { Authorization: `Bearer ${token}` } }),
                    axios.get('/api/v1/leads', { headers: { Authorization: `Bearer ${token}` } })
                ]);

                this.stats.pages = pagesRes.data.length;
                this.stats.services = servicesRes.data.length;
                this.stats.products = productsRes.data.length;
                this.stats.leads = leadsRes.data.filter(l => l.status === 'new').length;
                this.recentLeads = leadsRes.data.slice(0, 5);
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }
    }
};
</script>

