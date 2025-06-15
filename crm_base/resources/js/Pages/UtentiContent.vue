<template>
	<v-row>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-text-field
				v-model="form.name"
				label="Nome"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.name ? crudDialog.errors.name : ''"
			></v-text-field>
		</v-col>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-text-field
				v-model="form.email"
				label="Email"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.email ? crudDialog.errors.email : ''"
			></v-text-field>
		</v-col>
		<v-col 
			v-if="readonly === false"
			cols="12" 
			class="pt-0 mb-3"
		>
			<v-expansion-panels>
				<v-expansion-panel elevation="4">
					<v-expansion-panel-title>
						<v-row>
							<v-col>
								<p class="font-weight-bold">Reimposta la password</p>
								<p class="text-caption">Se i campi resteranno vuoti la password resterà invariata</p>
							</v-col>
						</v-row>
					</v-expansion-panel-title>
					<v-expansion-panel-text class="pt-4 mb-n4">
						<v-text-field
							v-model="form.password"
							label="Password"
							prepend-inner-icon="fa-solid fa-lock"
							:color="crudDialog.color"
							:type="setup.showPassword ? 'text' : 'password'"
							:append-inner-icon="setup.showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
							@click:append-inner="setup.showPassword = !setup.showPassword"
							:error-messages="crudDialog.errors && crudDialog.errors.password ? crudDialog.errors.password : ''"
						></v-text-field>
						<v-text-field
							v-model="form.password_confirmation"
							label="Conferma Password"
							prepend-inner-icon="fa-solid fa-lock"
							:color="crudDialog.color"
							:type="setup.showPasswordConfirm ? 'text' : 'password'"
							:append-inner-icon="setup.showPasswordConfirm ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
							@click:append-inner="setup.showPasswordConfirm = !setup.showPasswordConfirm"
							:error-messages="crudDialog.errors && crudDialog.errors.password_confirmation ? crudDialog.errors.password_confirmation : ''"
						></v-text-field>
					</v-expansion-panel-text>
				</v-expansion-panel>
			</v-expansion-panels>
		</v-col>
		<v-col 
			cols="12" 
			class="pb-0"
		>
			<v-select
				v-model="form.role_id"
				label="Ruolo"
				:color="crudDialog.color"
				:items="data.roles"
				item-title="name"
				item-value="id"
				:error-messages="crudDialog.errors && crudDialog.errors.role_id ? crudDialog.errors.role_id : ''"
				:readonly="readonly"
			></v-select>	
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'UtentiContent',
	props: {
		crudDialog: {
			type: Object,
			required: true
		},
		dataElements: {
			type: Object,
			required: true
		},
		readonly: {
			type: Boolean,
			default: false
		},
		crud: {
			type: String,
			required: true
		}
	},
	mounted() {
		this.getData();
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					showPassword: false,
					showPasswordConfirm: false,
					nameField: 'name'
				},
				form: {
					name: '',
					email: '',
					password: '',
					password_confirmation: '',
					role_id: ''
				},
				data: {
					roles: []
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
			}
			this.data.roles = this.dataElements.roles;
		},
		getForm() {
			return this.form;
		}
	}
}
</script>
