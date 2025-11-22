<template>
    <v-app>
        <!-- Router View - Layouts will be rendered here -->
        <router-view />
        <vue-progress-bar />
    </v-app>
</template>

<script>
export default {
    mounted() {
        console.log('App component mounted.')
    }
}
</script>
