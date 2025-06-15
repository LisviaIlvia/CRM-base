<template>
	<v-row>
		<v-col cols="12" align="center">
			<img class="mb-2" height="50" src="/images-public/logo.png">
		</v-col>
	</v-row>
	<v-card class="pa-5">
		<v-card-title class="text-center pb-0">{{card.title}}</v-card-title>
		<v-card-subtitle class="text-center">{{card.subtitle}}</v-card-subtitle>
		<v-card-text class="mt-2">
			<v-form @submit.prevent="resetPassword">
				<v-text-field
					label="Email"
					prepend-inner-icon="fa-solid fa-envelope"
					v-model="form.email"
					:error-messages="errors.email"
				></v-text-field>
				<v-text-field
					label="Password"
					prepend-inner-icon="fa-solid fa-lock"
					v-model="form.password"
					:type="form.showPassword ? 'text' : 'password'"
					:append-inner-icon="form.showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
					@click:append-inner="form.showPassword = !form.showPassword"
					:error-messages="errors.password"
				></v-text-field>
				<v-text-field
					label="Conferma Password"
					prepend-inner-icon="fa-solid fa-lock"
					v-model="form.password_confirmation"
					:type="form.showPassword ? 'text' : 'password'"
					:append-inner-icon="form.showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
					@click:append-inner="form.showPassword = !form.showPassword"
					:error-messages="errors.password_confirmation"
				></v-text-field>
			<v-btn
				type="submit" 
				color="primary" 
				block
			>Imposta</v-btn>
			</v-form>
		</v-card-text>

	</v-card>
</template>

<script>
export default {
	name: 'ResetPassword',
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					pageTitle: 'Reset Password'
				},
				card: {
					title: 'Imposta la tua password',
					subtitle: 'Inserisci la tua email per reimpostare la password',
				},
				form: {
					showPassword: false,
					email: this.$usePage().props.email,
					password: '',
					password_confirmation: '',
					token: this.$usePage().props.token
				},
				errors: {}
			}
		},
		resetPassword() {
			const data = {
				email: this.form.email,
				password: this.form.password,
				password_confirmation: this.form.password_confirmation,
				token: this.form.token
			};
			this.$axiosService.post({
				url: this.$route('password.store'),
				data: data, 
				success: (response) => {
					this.$inertia.visit(this.$route('login'));
				},
				error: (error) => {
					this.errors = error.errors;
				}
			});
		}
	}
};
</script>