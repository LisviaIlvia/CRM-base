<template>
	<v-dialog
		v-model="setup.dialog"
		width="30%"
		@click:outside="onOutsideClick"
	>
		<v-container class="pt-5">
			<v-sheet rounded>
				<v-container class="px-0">
					<v-row align="center" class="mt-n10">
						<v-col cols="auto">
							<v-avatar
							  rounded
							  :color="crudDialog.color"
							  elevation="5"
							  class="ml-4 px-10 py-10 elevation-4 dialog-avatar"
							>
								<v-icon color="surface" size="x-large" :icon="crudDialog.icon"></v-icon>
							</v-avatar>
						</v-col>
						<v-col class="mt-6">
							<h3 v-html="dialogCustomTitle ?? text.title"></h3>
						</v-col>
						<v-col cols="auto" class="text-right mt-n3">
							<v-btn color="secondary" icon="fa-solid fa-circle-info" variant="text">
							</v-btn>
							<v-tooltip
								activator="parent"
								location="right"
							>{{text.infoTooltip}}</v-tooltip>
						</v-col>
					</v-row>
					<v-divider class="mt-n5"></v-divider>
				</v-container>
				<v-form @submit.prevent="sendForm">
					<v-container class="px-10">
						<v-row>
							<v-col>
								<p>{{text.content}}</p>
							</v-col>
						</v-row>
					</v-container>
					<v-container :class="crudDialog.bg + ' rounded-be rounded-bs'">
						<v-row>
							<v-spacer></v-spacer>
							<v-col cols="auto" class="text-right">
								<v-btn
									color="secondary"
									variant="outlined"
									@click="closeDialog"
								>Chiudi</v-btn>
							</v-col>
							<v-col cols="auto" class="text-right">
								<v-btn
									type="submit" 
									:color="crudDialog.color"
									elevation="4"
									:loading="crudDialog.loadingSubmit"
								>Elimina</v-btn>
							</v-col>
						</v-row>
					</v-container>
				</v-form>
			</v-sheet>
		</v-container>
      </v-dialog>
</template>

<style scoped>
	.dialog-avatar {
		z-index: 10;
	}
</style>

<script>
export default {
	name: 'DialogDelete',
	props: {
		dialogTitle: {
			type: String,
			required: true
		},
		dialogType: {
			type: String,
			required: true
		},
		tooltip: {
			type: Object,
			required: true
		},
		textRequest: {
			type: String,
			required: true
		},
		textConfirm: {
			type: String,
			required: true
		},
		dialogCustomTitle: {
			type: String,
			default: null
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					dialog: false,
					nameField: null
				},			
				text: this.$tooltipDialog.delete(this.dialogTitle, this.dialogType),
				crudDialog: new this.$crudDialog('delete')
			};
		},
		resetState() {
			this.setup.dialog = false;
			setTimeout(() => {
				Object.assign(this.$data, this.initialState());
			}, 200);
		},
		onOutsideClick(event) {
			if (event.target.className.includes('v-dialog--active')) {
				return;
			}
			this.resetState();
		},
		openDialog(data, urlForm) {
			this.setup.nameField = data;
			this.text.content = "Vuoi eliminare "+this.textRequest+" "+data+"?";
			this.crudDialog.open(urlForm, () => {
				this.setup.dialog = true;
				this.$emit('dialog-delete-opened');
			});
		},
		sendForm() {
			this.crudDialog.send(this.form, (data) => {
				this.resetState();
				this.$emit('destroy-record', data.record.id);
				let text = 'eliminato';
				if(this.tooltip.type == 'a') text = 'eliminata';
				this.$emit('show-notification', {
					type: 'success',
					text: `${this.textConfirm} ${this.setup.nameField} ${text} con successo.`,
				});
			});
		},
		closeDialog() {
			this.resetState();
		}
	}
}
</script>