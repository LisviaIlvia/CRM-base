<template>
	<v-dialog
		v-model="setup.dialog"
		:width="effectiveDialogSetup.width"
		:fullscreen="effectiveDialogSetup.fullscreen"
		:scrim="effectiveDialogSetup.scrim"
		@click:outside="onOutsideClick"
	>
		<v-container :fluid="effectiveDialogSetup.fullscreen" 
		:class="{
			'overflow-dialog': true, 
			'pa-0 min-h100 overflow-auto': effectiveDialogSetup.fullscreen, 
			'pt-5': !effectiveDialogSetup.fullscreen
		}">
			<v-sheet :rounded="!effectiveDialogSetup.fullscreen" :class="{ 'min-h100': effectiveDialogSetup.fullscreen }">
				<v-container :fluid="effectiveDialogSetup.fullscreen" :class="effectiveDialogSetup.fullscreen ? 'sticky-container px-0 pb-0' : 'px-0 pb-0'">
					<v-row align="center" :class="effectiveDialogSetup.fullscreen ? 'mx-0' : 'mt-n10'">
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
						<v-col cols="auto" class="text-right" v-if="effectiveDialogSetup.fullscreen">
							<v-row>
								<v-col cols="auto">
									<v-btn
										type="submit" 
										elevation="4"
										:color="crudDialog.color"
										:loading="crudDialog.loadingSubmit"
										@click="sendForm"
									>Salva</v-btn>
								</v-col>
								<v-col cols="auto" class="text-right mr-7">
									<v-btn
										color="secondary"
										variant="outlined"
										@click="closeDialog"
									>Chiudi</v-btn>
								</v-col>
							</v-row>
						</v-col>
						<v-col cols="auto" class="text-right mt-n3" v-else>
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
					<v-container :fluid="effectiveDialogSetup.fullscreen" :class="['px-10 mt-8', { 'mb-4': !effectiveDialogSetup.fullscreen }]">
						<slot />
					</v-container>
					<v-container :class="crudDialog.bg + ' rounded-be rounded-bs'" v-if="!effectiveDialogSetup.fullscreen">
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
									elevation="4"
									:color="crudDialog.color"
									:loading="crudDialog.loadingSubmit"
								>Salva</v-btn>
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
	.min-h100 {
		min-height: 100%;
	}
	.sticky-container {
		position: -webkit-sticky;
		position: sticky;
		top: 0;
		z-index: 1000;
		background-color: #fff;
	}
	.overflow-dialog {
		overflow-y: auto;
	}
	::-webkit-scrollbar {
		width: 12px;
	}
	::-webkit-scrollbar-track {
		background: #FFF8E1;
		border-radius: 4px;
	}
	::-webkit-scrollbar-thumb {
		background-color: #FFECB3;
		border-radius: 4px;
	}
	::-webkit-scrollbar-thumb:hover {
		background-color: #FFE082;
		border-radius: 4px;
	}
</style>

<script>
export default {
	name: 'DialogEdit',
	props: {
		dialogTitle: {
			type: String,
			required: true
		},
		dialogType: {
			type: String,
			required: true
		},
		dialogSetup: {
			type: Object,
			default: () => ({})
		},
		crudDialog: {
			type: Object,
			required: true
		},
		sendForm: {
			type: Function,
			required: true
		},
		dialogCustomTitle: {
			type: String,
			default: null
		}
	},
	computed: {
		effectiveDialogSetup() {
			const defaults = {
				width: '50%',
				fullscreen: false,
				scrim: true
			};
			return { ...defaults, ...this.dialogSetup };
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					dialog: false
				},
				text: this.$tooltipDialog.edit(this.dialogTitle, this.dialogType)
			};
		},
		resetState() {
			this.setup.dialog = false;
			setTimeout(() => {
				Object.assign(this.$data, this.initialState());
				this.$emit('reset');
			}, 200);
		},
		onOutsideClick(event) {
			if (event.target.className.includes('v-dialog--active')) {
				return;
			}
			this.resetState();
		},
		openDialogEdit(urlOpen, urlForm, callback, options = {}) {
			this.crudDialog.urlOpen = urlOpen;
			this.crudDialog.open(urlForm, (data) => {
				callback(data);
				this.setup.dialog = true;
				this.$emit('dialog-edit-opened');
			}, options);
		},
		sendFormEdit(form, options = {}) {
			const {
				field = null, 
				nameField = null, 
				callbackError = null,
				containsFile = false
			} = options;

			this.crudDialog.send(form, (data) => {
				this.resetState();
				if (field && typeof field === "object" && 'name' in field && 'value' in field) {
					this.$emit('update-record', data.record, field.name, field.value);
				} else {
					this.$emit('update-record', data.record);
				}
				let recordName = nameField ? data.record[nameField] : data.record.nome;
				let title = this.dialogTitle ? this.dialogTitle : null;
				this.$emit('show-notification', {
					type: 'success',
					text: `${title} ${recordName} modificato con successo.`,
				});
			}, {
				'callbackError': callbackError,
				'containsFile': containsFile
			});
		},
		closeDialog() {
			this.resetState();
		}
	}
}
</script>