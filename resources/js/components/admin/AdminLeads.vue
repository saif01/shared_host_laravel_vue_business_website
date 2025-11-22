<template>
    <div>
        <h1 class="text-h4 mb-6">Leads Management</h1>
        <v-btn color="primary" class="mb-4" @click="exportLeads">Export to CSV</v-btn>
        <v-table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="lead in leads" :key="lead.id">
                    <td>{{ lead.name }}</td>
                    <td>{{ lead.email }}</td>
                    <td>{{ lead.type }}</td>
                    <td>{{ lead.status }}</td>
                    <td>{{ new Date(lead.created_at).toLocaleDateString() }}</td>
                    <td>
                        <v-btn size="small" @click="viewLead(lead)">View</v-btn>
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
            leads: []
        };
    },
    async mounted() {
        await this.loadLeads();
    },
    methods: {
        async loadLeads() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await axios.get('/api/v1/leads', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.leads = response.data;
            } catch (error) {
                console.error('Error loading leads:', error);
            }
        },
        viewLead(lead) {
            alert(`Lead Details:\nName: ${lead.name}\nEmail: ${lead.email}\nMessage: ${lead.message}`);
        },
        async exportLeads() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await axios.get('/api/v1/leads/export/csv', {
                    headers: { Authorization: `Bearer ${token}` },
                    responseType: 'blob'
                });
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'leads.csv');
                document.body.appendChild(link);
                link.click();
            } catch (error) {
                alert('Error exporting leads');
            }
        }
    }
};
</script>

