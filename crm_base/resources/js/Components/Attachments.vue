<template>
	<v-col
		cols="12"
		class="py-0"
		v-if="readonly === false"
	>
		<v-file-input
			v-model="form.allegato"
			:label="labelSingle"
			:color="color"
			:error-messages="errorMessages"
			:multiple="multiple"
			@update:modelValue="onUpdateAttachments"
		></v-file-input>
	</v-col>
	<v-col
		cols="12"
		class="py-0"
	>
		<h4 class="mb-1 ms-1">{{ labelPlural }}</h4>
		<v-divider
			:thickness="4"
			class="border-opacity-75"
			color="bg-grey-lighten-3"
		></v-divider>
		<v-list density="compact" class="mb-4" v-if="form.allegati.length > 0">
			<v-list-item v-for="item in form.allegati" :key="item.id">
				<template v-slot:prepend>
					<v-btn variant="text" :href="item.url" target="_blank" :disabled="item.url == '#'">
						<v-icon :icon="getFileIcon(item.mime_type)"></v-icon>
					</v-btn>
				</template>
				<v-list-item-title v-text="item.name"></v-list-item-title>
				<template v-slot:append>
					<v-btn variant="text" @click="removeAttachment(item.id)" :disabled="readonly === true">
						<v-icon icon="fa-solid fa-circle-minus"></v-icon>
					</v-btn>
				</template>
			</v-list-item>
		</v-list>
		<p v-else class="grey--text px-1 py-3 mb-2">Non sono stati ancora aggiunti allegati</p>
	</v-col>
	
</template>

<script>
export default {
	name: 'Attachments',
	props: {
		labelSingle: {
			type: String,
			default: 'Allegato'
		},
		labelPlural: {
			type: String,
			default: 'Allegati'
		},
		allegati: {
			type: [Array],
			default: () => []
		},
		color: {
			type: String,
			required: true
		},
        errorMessages: {
            type: [String, Array],
            default: () => []
        },
		readonly: {
			type: Boolean,
			default: false
		},
		multiple: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				form: {
					allegato: null,
					allegati: this.allegati
				}
			};
		},
		onUpdateAttachments() {
			if(this.multiple === false) this.removeAttachment(0);
			if(this.form.allegato) {
				this.addAttachment();
			} else {
				this.form.allegato = null;
			}
		},
		getFileIcon(mimeType) {
			switch (mimeType) {
				case 'application/pdf':
					return 'fa-solid fa-file-pdf';
					break;
				case 'application/msword':
				case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
					return 'fa-solid fa-file-word';
					break;
				case 'image/jpeg':
				case 'image/png':
				case 'image/svg+xml':
					return 'fa-solid fa-file-image';
					break;
				case 'text/plain':
					return 'fa-solid fa-file-alt';
					break;
				default:
					return 'fa-solid fa-fa-file';
			}
		},
		addAttachment() {
			if (!this.form.allegato) return;

			if (this.multiple === true) {
				for (let i = 0; i < this.form.allegato.length; i++) {
					let file = this.form.allegato[i];

					let allegato = {
						id: this.generateUniqueId(),
						name: file.name,
						mime_type: file.type,
						url: '#',
						type: 'new',
						file: file
					};

					this.form.allegati.push(allegato);
				}
			} else {
				this.form.allegati = [];
				let file = this.form.allegato;

				let allegato = {
					id: this.generateUniqueId(),
					name: file.name,
					mime_type: file.type,
					url: '#',
					type: 'new',
					file: file
				};

				this.form.allegati.push(allegato);
			}
		},
		removeAttachment(id) {
			if(id == 0) {
				this.form.allegati = [];
			} else {
				this.form.allegati = this.form.allegati.filter(item => item.id !== id);
			}
		},
		generateUniqueId() {
			let maxId = this.form.allegati.reduce((max, allegato) => Math.max(max, allegato.id), 0);
			return maxId + 1;
		}

	},
	watch: {
		allegati: {
			handler(newAllegati) {
				this.form.allegati = [...newAllegati];
			},
			deep: true,
			immediate: true
		}
	}
}
</script>