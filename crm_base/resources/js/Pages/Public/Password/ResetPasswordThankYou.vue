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
			<p>{{card.text}}</p>
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
	name: 'ResetPasswordThankYou',
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					pageTitle: 'Grazie Reset Password'
				},
				card: {
					title: 'Richiesta Reset Password Inviata!',
					subtitle: 'Controlla la tua casella di posta elettronica.',
					text: 'Se il tuo indirizzo email corrisponde a un account nel nostro sistema, ti è stata inviata una email con un link per reimpostare la tua password.'
				},
				isLoadingReturnLogin: false
			}
		},
		returnLogin() {
			this.isLoadingReturnLogin = true;
			this.$inertia.visit(this.$route('login'));
		}
	}
};
</script>