<template>
<v-row>		
	<documents-trasporto
		v-if="trasportoActive"
		:trasporto="data.trasporto"
		:color="color"
		:errors="errors"
		:readonly="readonly"
		ref="trasportoDocumentsRef"
	/>
	<v-col
	  cols="12"
	  class="py-0 mt-6 mb-6"
	>
		<v-card
			elevation="4"
			variant="flat"
		>
			<v-toolbar
				class="px-3"
			>
				<v-row class="align-center bg-grey-lighten-3">
					<v-col cols="12">
						<header-box title="Informazioni aggiuntive" icon="fa-solid fa-note-sticky" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row
				class="px-4 mt-6 pb-3"
			>
				<v-col
				  cols="12"
				  class="py-0"
				>
					<v-textarea
						v-model="form.note"
						label="Note"
						:color="color"
						:error-messages="errors && errors.note ? errors.note : ''"
						:readonly="readonly"
					></v-textarea>
				</v-col>
				<attachments 
					:allegati="data.allegati"
					:color="color"
					:errorMessages="errors && errors.allegati ? errors.allegati : ''"
					:readonly="readonly"
					:multiple="true"
					ref="attachmentsRef"
				/>
			</v-row>
		</v-card>
	</v-col>
</v-row>
</template>

<script>
import Attachments from '@/Components/Attachments.vue';
import DocumentsTrasporto from '@/Components/Documents/Footer/DocumentsTrasporto.vue';

export default {
	name: 'DocumentFooter',
	components: {
		Attachments,
		DocumentsTrasporto
	},
	props: {
		color: {
			type: String,
			required: true
		},
		errors: {
			type: Object,
			default: () => ({})
		},
		readonly: {
			type: Boolean,
			default: false
		}
	},
	emits: ['ready'],
	mounted() {
		this.$emit('ready');
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				trasportoActive: false,
				form: {
					note: null
				},
				data: {
					allegati: [],
					trasporto: {
						colli: null,
						peso: 0,
						causale: null,
						porto: null,
						a_cura: null,
						annotazioni: null
					}
				},
				combinedFormData: {}
			};
		},
		getForm() {
			let combinedData = null;
			if(this.$refs.trasportoDocumentsRef && this.$refs.trasportoDocumentsRef.form) {
				combinedData = {
					...combinedData,
					...this.$refs.trasportoDocumentsRef.form
				};
			}
			if(this.$refs.attachmentsRef && this.$refs.attachmentsRef.form) {
				combinedData = {
					...combinedData,
					...this.$refs.attachmentsRef.form
				};
			} 
			if(combinedData !== null) {
				return {
					...this.form,
					...combinedData
				};
			} else {
				return this.form;
			}
		}
	}
};
</script>