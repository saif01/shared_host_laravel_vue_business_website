<template>
    <a v-if="whatsappUrl" :href="whatsappUrl" target="_blank"
        class="whatsapp-float elevation-6 d-flex align-center justify-center text-decoration-none">
        <v-icon icon="mdi-whatsapp" color="white" size="32"></v-icon>
    </a>
</template>

<script>
export default {
    name: 'WhatsAppFloat',
    props: {
        whatsappUrl: {
            type: String,
            default: ''
        }
    }
};
</script>

<style scoped>
/* WhatsApp Float */
.whatsapp-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background-color: #25d366;
    border-radius: 50%;
    z-index: 100;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    animation: pulse-green 2s infinite;
}

.whatsapp-float:hover {
    transform: scale(1.1) rotate(10deg);
    box-shadow: 0 10px 20px rgba(37, 211, 102, 0.4) !important;
}

@keyframes pulse-green {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }

    70% {
        box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}
</style>

