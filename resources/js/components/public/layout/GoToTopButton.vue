<template>
    <v-btn v-show="showButton" @click="scrollToTop" class="go-to-top-btn elevation-6" color="primary" size="large"
        :class="{ 'show': showButton }">
        <v-icon icon="mdi-chevron-up" size="28"></v-icon>
    </v-btn>
</template>

<script>
export default {
    name: 'GoToTopButton',
    data() {
        return {
            showButton: false
        };
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
        // Check initial scroll position
        this.handleScroll();
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        handleScroll() {
            // Show button when user scrolls down more than 300px
            this.showButton = window.scrollY > 300;
        },
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
};
</script>

<style scoped>
.go-to-top-btn {
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    z-index: 100;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    opacity: 0;
    transform: translateY(20px);
    pointer-events: none;
    min-width: 56px !important;
}

.go-to-top-btn.show {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.go-to-top-btn:hover {
    transform: translateY(-5px) scale(1.1) !important;
    box-shadow: 0 10px 20px rgba(var(--v-theme-primary), 0.4) !important;
}

/* Responsive Styles */
@media (max-width: 600px) {
    .go-to-top-btn {
        width: 50px;
        height: 50px;
        min-width: 50px !important;
        bottom: 20px;
        left: 20px;
    }

    .go-to-top-btn :deep(.v-icon) {
        font-size: 24px !important;
    }
}
</style>

