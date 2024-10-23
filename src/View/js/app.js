const app = Vue.createApp({
    data() {
        return {
            userName: '',
            email: '',
            userPassword: ''
        };
    },
    methods: {
        submitForm() {
            const formData = new FormData();
            formData.append('email', this.email);
            formData.append('userPassword', this.userPassword);

            fetch('http://localhost:8000/login', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    },
    template: `
    <div class="container">
        <p>Login</p>
        <form @submit.prevent="submitForm">
            <label>E-mail:</label>
            <input type="email" v-model="email">
            <br />
            <label>Senha:</label>
            <input type="password" v-model="userPassword">
            <br />
            <button type="submit">Enviar</button>
        </form>
    </div>
    `,
});
app.mount('#app');
