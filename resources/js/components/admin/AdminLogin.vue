<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card>
                    <v-card-title class="text-h5">Admin Login</v-card-title>
                    <v-card-text>
                        <v-form @submit.prevent="login">
                            <v-text-field
                                v-model="form.email"
                                label="Email"
                                type="email"
                                required
                                prepend-inner-icon="mdi-email"
                            ></v-text-field>
                            <v-text-field
                                v-model="form.password"
                                label="Password"
                                type="password"
                                required
                                prepend-inner-icon="mdi-lock"
                            ></v-text-field>
                            <v-btn type="submit" color="primary" block :loading="loading">Login</v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            loading: false
        };
    },
    methods: {
        async login() {
            this.loading = true;
            try {
                const response = await axios.post('/api/v1/auth/login', this.form);
                localStorage.setItem('admin_token', response.data.token);
                this.$router.push({ name: 'AdminDashboard' });
            } catch (error) {
                if (error.response) {
                    alert(error.response.data.message || 'Login failed');
                } else {
                    alert('An error occurred');
                }
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

