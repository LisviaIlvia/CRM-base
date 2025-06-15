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
			<v-form @submit.prevent="login">
				<v-text-field
					label="E-mail"
					prepend-inner-icon="fa-solid fa-envelope"
					v-model="form.email"
					autocomplete="email"
					:error-messages="errors && errors.email ? errors.email : ''"
				></v-text-field>
				<v-text-field
					label="Password"
					prepend-inner-icon="fa-solid fa-lock"
					v-model="form.password"
					:type="form.showPassword ? 'text' : 'password'"
					autocomplete="current-password"
					:append-inner-icon="form.showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
					@click:append-inner="form.showPassword = !form.showPassword"
					:error-messages="errors && errors.password ? errors.password : ''"
				></v-text-field>
				<v-checkbox label="Ricordami" v-model="form.rememberMe" hide-details class="mb-3"></v-checkbox>
				<v-btn
					type="submit" 
					color="primary" 
					block
					:loading="isLoadingLogin"
				>Accedi</v-btn>
			</v-form>
		</v-card-text>
		<v-card-actions>
			<v-btn 
				class="text-body-2"
				block
				:loading="isLoadingforgotPassword"
				@click="forgotPassword"
			>Hai dimenticato la password?</v-btn>
		</v-card-actions>
	</v-card>
</template>

<script>
export default {
	name: 'Login',
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				title: 'Login',
				card: {
					title: 'Benvenuto',
					subtitle: 'Accedi al tuo account per continuare',
				},
				form: {
					showPassword: false,
					email: '',
					password: '',
					rememberMe: false
				},
				isLoadingLogin: false,
				isLoadingforgotPassword: false,
				errors: {}
			}
		},
		login() {
			this.isLoadingLogin = true;
			const data = {
				email: this.form.email,
				password: this.form.password,
				remember: this.form.rememberMe,
			};
			this.$axiosService.post({
				url: this.$route('login'), 
				data: data, 
				success: (response) => {
					this.isLoadingLogin = false;
					this.$inertia.visit(this.$route('dashboard'));
				},
				error: (error) => {
					this.isLoadingLogin = false;
					this.errors = error.errors;
				}
			});
		},
		forgotPassword() {
			this.isLoadingforgotPassword = true;
			this.$inertia.visit(this.$route('password.request'));
		}
	}
};
</script>