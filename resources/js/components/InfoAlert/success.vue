<template>
    <div></div>
</template>

<script>
    export default {
        props: ['message'],
        created() {
            if(this.message) {
                this.flash(this.message);
            }
            window.events.$on('flash', message => this.flash(message));
        },
        methods: {
            flash(message) {
                toast.fire({
                    icon: 'success',
                    title: message
                });
            },
        },
    }
</script>
