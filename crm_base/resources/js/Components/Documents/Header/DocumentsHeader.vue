<template>
	<v-row
		v-if="data.parents != null && data.children != null"
		class="mb-6"
	>
		<v-col cols="6" class="py-0 d-flex flex-column">
			<v-card elevation="4" variant="flat" class="flex-grow-1 d-flex flex-column">
				<v-toolbar class="px-3">
					<v-row class="align-center bg-grey-lighten-3">
						<v-col cols="12">
							<header-box title="Documenti ascendenti collegati" icon="fa-solid fa-note-sticky" />
						</v-col>
					</v-row>
				</v-toolbar>
				<v-row class="py-6 px-2 flex-grow-1">
					<v-col
						v-if="data.parents.length > 0"
						v-for="parent in data.parents"
						:key="parent.data.id"
						cols="4"
						class="pa-0"
					>
						<v-list-item
							:subtitle="`Nr ${parent.numero} del ${formatDateRelation(parent.data)}`"
							:title="parent.type"
						>
							<template v-slot:prepend>
								<v-btn
									color="color-show"
									icon="fa-solid fa-up-right-from-square"
									variant="text"
									@click="visitLink(parent.link)"
								></v-btn>
							</template>
						</v-list-item>
					</v-col>
					<v-col v-else cols="12">
						<p class="px-3 py-1">Nessun documento collegato</p>
					</v-col>
				</v-row>
			</v-card>
		</v-col>
		<v-col cols="6" class="py-0 d-flex flex-column">
			<v-card elevation="4" variant="flat" class="flex-grow-1 d-flex flex-column">
				<v-toolbar class="px-3">
					<v-row class="align-center bg-grey-lighten-3">
						<v-col cols="12">
							<header-box title="Documenti discendenti collegati" icon="fa-solid fa-note-sticky" />
						</v-col>
					</v-row>
				</v-toolbar>
				<v-row class="py-6 px-2 flex-grow-1">
					<v-col
						v-if="data.children.length > 0"
						v-for="child in data.children"
						:key="child.data.id"
						cols="4"
						class="pa-0"
					>
						<v-list-item
							:subtitle="`Nr ${child.numero} del ${formatDateRelation(child.data)}`"
							:title="child.type"
						>
							<template v-slot:prepend>
								<v-btn
									color="color-show"
									icon="fa-solid fa-up-right-from-square"
									variant="text"
									@click="visitLink(child.link)"
								></v-btn>
							</template>
						</v-list-item>
					</v-col>
					<v-col v-else cols="12">
						<p class="px-3 py-1">Nessun documento collegato</p>
					</v-col>
				</v-row>
			</v-card>
		</v-col>
	</v-row>
	<v-row>
		<v-col
			class="py-0 col-info"
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
							<header-box title="Informazioni" icon="fa-solid fa-note-sticky" />
						</v-col>
					</v-row>
				</v-toolbar>
				<v-row
					class="px-4 mt-6 pb-3"
				>
					<v-col
						cols="6"
						class="py-0"
					>
						<v-text-field
							v-model="form.numero"
							label="Numero"
							:color="color"
							:error-messages="errors && errors.numero ? errors.numero : ''"
							:readonly="readonly || numeroBlock === true"
						></v-text-field>
					</v-col>
					<v-col
						cols="6"
						class="py-0"
					>
						<v-text-field
							v-if="readonly"
							:model-value="showDate()"
							label="Data"
							:color="color"
							prepend-inner-icon="fa-solid fa-calendar"
						></v-text-field>
						<v-date-input
							v-else
							v-model="form.data"
							placeholder="gg/mm/aaaa"
							variant="outlined"
							label="Data"
							:color="color"
							:min="rangeData.min"
							:max="rangeData.max"
							:year="rangeData.year"
							:prepend-icon="null"
							prepend-inner-icon="fa-solid fa-calendar"
							:error-messages="errors && errors.data ? errors.data : ''"
						></v-date-input>
					</v-col>
					<v-col
						cols="12"
						class="py-0"
					>
						<v-select
							v-model="form.stato"
							:items="data.stati"
							label="Stato"
							:color="color"
							:error-messages="errors && errors.stato ? errors.stato : ''"
							:readonly="readonly"
						></v-select>
					</v-col>
				</v-row>
			</v-card>
		</v-col>
		<v-col
			class="py-0 col-interlocutore"
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
							<header-box :title="interlocutore" icon="fa-solid fa-address-card" />
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
						<v-select
							v-model="form.entity_type"
							:items="tipiIntestatari"
							label="Seleziona Tipo"
							:color="color"
							:error-messages="errors && errors.entity_type ? errors.entity_type : ''"
							:readonly="readonly || entityType != null"
							@update:modelValue="form.entity_id = null"
						></v-select>
					</v-col>
					<v-col
						cols="12"
						class="py-0"
					>
						<v-select
							v-model="form.entity_id"
							:items="intestatari[form.entity_type]"
							item-title="nome"
							item-value="id"
							:label="`Seleziona ${form.entity_type.charAt(0).toUpperCase() + form.entity_type.slice(1)}`"
							:color="color"
							:disabled="!form.entity_type"
							:error-messages="errors && errors.entity_id ? errors.entity_id : ''"
							:readonly="readonly || entityType != null"
						></v-select>
					</v-col>
				</v-row>
			</v-card>
		</v-col>
		<v-col
			class="py-0 col-indirizzo"
		>
			<documents-indirizzo
				:entityId="form.entity_id"
				:entityType="form.entity_type"
				:intestatari="intestatari"
				:color="color"
				:errors="errors"
				:readonly="readonly"
				@ready="handleReadyIndirizzo"
				ref="indirizzoDocumentsRef"
			/>
		</v-col>
	</v-row>
</template>

<style scoped>
	.col-info {
		flex: 0 0 23%;
		max-width: 23%;
	}

	.col-interlocutore {
		flex: 0 0 20%;
		max-width: 20%;
	}

	.col-indirizzo {
		flex: 0 0 57%;
		max-width: 57%;
	}
</style>

<script>
import { useYearStore } from '@/store/yearStore';
import DocumentsIndirizzo from '@/Components/Documents/Header/DocumentsIndirizzo.vue';
import DocumentsLuogoDestinazione from '@/Components/Documents/Header/DocumentsLuogoDestinazione.vue';

export default {
	name: 'DocumentsHeader',
	components: {
		DocumentsIndirizzo,
		DocumentsLuogoDestinazione
	},
	props: {
		tipiIntestatari: {
			type: Array,
			required: true
		},
		intestatari: {
			type: Object,
			required: true
		},
		numero: {
			type: String,
			default: ''
		},
		previousDocDate: {
			type: String,
			default: ''
		},
		nextDocDate: {
			type: String,
			default: ''
		},
		interlocutore: {
			type: String,
			required: true
		},
		entityId: {
			type: Number,
			default: null
		},
		entityType: {
			type: String,
			default: null
		},
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
	computed: {
		rangeData() {
			const yearStore = useYearStore();
			const year = parseInt(yearStore.selectedYear);
			let min = null;
			let max = null;

			if(this.previousDocDate == '') {
				min = `${year}-01-01`;
			} else {
				min = this.previousDocDate;
			}

			if(this.nextDocDate == '') {
				max = `${year}-12-31`;
			} else {
				max = this.nextDocDate;
			}

			return { min, max, year };
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				numeroBlock: true,
				trasportoActive: false,
				form: {
					numero: '',
					stato: null,
					data: null,
					entity_type: '',
					entity_id: null
				},
				data: {
					indirizzo: null,
					stati: [],
					parents: null,
					children: null
				}
			};
		},
		formatDate() {
            let data = null;
			if(this.form.data !== null) {
				const day = `0${this.form.data.getDate()}`.slice(-2);
				const month = `0${this.form.data.getMonth() + 1}`.slice(-2);
				const year = this.form.data.getFullYear();
				data = `${year}-${month}-${day}`;
			}
			return data;
		},
		formatDateRelation(date) {
			const [year, month, day] = date.split('-');
			return `${day}/${month}/${year}`;
		},
		showDate() {
			const date = new Date(this.form.data);

			const day = String(date.getDate()).padStart(2, '0');
			const month = String(date.getMonth() + 1).padStart(2, '0');
			const year = date.getFullYear();

			return `${day}/${month}/${year}`;
		},
		visitLink(link) {
			window.open(link, '_blank');
		},
        getForm() {
            const data = this.formatDate();
            let form_data = { ...this.form, ...this.$refs.indirizzoDocumentsRef.getForm() };
            form_data.data = data;
			return form_data;
		},
		handleReadyIndirizzo() {
			this.$emit('ready');
			this.$refs.indirizzoDocumentsRef.form.indirizzo = this.data.indirizzo;
			this.$refs.indirizzoDocumentsRef.trasportoActive = this.trasportoActive;
		}
	},
	watch: {
		numero(newVal) {
			this.form.numero = newVal;
		},
		entityType: {
			immediate: true,
			handler(newVal) {
				if (newVal !== null) {
					this.form.entity_type = newVal;
				}
			}
		},
		entityId: {
			immediate: true,
			handler(newVal) {
				if (newVal !== null) {
					this.form.entity_id = newVal;
				}
			}
		}
	}
};
</script>
