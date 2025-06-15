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
					v-model="form.email"
					label="Email"
					prepend-inner-icon="fa-solid fa-envelope"
					:error-messages="errors.email"
				></v-text-field>
			<v-btn
				type="submit" 
				color="primary" 
				block
				:loading="isLoadingRequestReset"
			>Richiedi</v-btn>
			</v-form>
		</v-card-text>
		<v-card-actions>
			<v-btn 
				class="text-body-2"
				block
				:loading="isLoadingReturnLogin"
				@click="returnLogin"
			>Torna alla login</v-btn>
		</v-card-actions>
	</v-card>
</template>

<script>
export default {
	name: 'ForgotPassword',
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					pageTitle: 'Recupera Password'
				},
				card: {
					title: 'Reset password',
					subtitle: 'Inserisci la tua email per impostare la password',
				},
				form: {
					email: ''
				},
				isLoadingRequestReset: false,
				isLoadingReturnLogin: false,
				errors: {}
			}
		},
		resetPassword() {
			this.isLoadingRequestReset = true;
			const data = {
				email: this.form.email
			};
			this.$axiosService.post({
				url: this.$route('password.email'),
				data: data, 
				success: (response) => {
					this.isLoadingRequestReset = false;
					this.$inertia.visit(this.$route('password.request.thankyou'));
				},
				error: (error) => {
					this.isLoadingRequestReset = false;
					this.errors = error.errors;
				}
			});
		},
		returnLogin() {
			this.isLoadingReturnLogin = true;
			this.$inertia.visit(this.$route('login'));
		}
	}
};
</script>