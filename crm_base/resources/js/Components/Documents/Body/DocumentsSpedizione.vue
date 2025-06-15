<template>
	<v-col
	  :cols="metodoPagamentoActive ? 6 : 12"
	  class="py-0 mt-6"
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
						<header-box title="Spedizione" icon="fa-solid fa-truck-fast" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row
				class="px-4 mt-8 pb-2 mb-3"
			>
				<v-col
					cols="3"
					class="py-0 mb-2"
				>
					<v-select
						v-model="form.spedizione.spedizione_id"
						:items="spedizioni"
						item-value="id"
						item-title="nome"
						label="Spedizione"
						:color="color"
						hide-details
						:readonly="readonly"
						@update:modelValue="updateSpedizione"
					></v-select>
				</v-col>
				<v-col
					cols="3"
					class="py-0 mb-2"
				>
					<number-decimal
						v-model="form.spedizione.prezzo"
						label="Prezzo"
						:color="color"
						prefix="€"
						:hideDetails="true"
						@update:modelValue="activeSpedizione"
						:readonly="readonly"
						:disabled="form.spedizione.spedizione_id === 0"
					/>
				</v-col>
				<v-col
					cols="3"
					class="py-0 mb-2"
				>
					<number-decimal
						v-model="form.spedizione.sconto"
						label="Sconto"
						:color="color"
						prefix="%"
						:hideDetails="true"
						@update:modelValue="activeSpedizione"
						:readonly="readonly"
						:disabled="form.spedizione.spedizione_id === 0"
					/>
				</v-col>
				<v-col
					cols="3"
					class="py-0 mb-2"
				>
					<v-select
						v-model="form.spedizione.iva.aliquota_iva_id"
						:items="aliquoteIva.map(item => ({
							...item,
							aliquota: item.nome ? `${formatIva(item.aliquota)} - ${item.nome}` : formatIva(item.aliquota)
						}))"
						item-value="id"
						item-title="aliquota"
						prefix="%"
						label="IVA"
						:color="color"
						hide-details
						:readonly="readonly"
						@update:modelValue="updateAliquotaIva()"
						:disabled="form.spedizione.spedizione_id === 0"
					></v-select>
				</v-col>
			</v-row>
		</v-card>
	</v-col>
</template>

<script>
export default {
    name: 'DocumentsSpedizione',
    props: {
        color: {
            type: String,
            required: true
        },
		errors: {
			type: Object,
			default: () => ({})
		},
		aliquoteIva: {
			type: Object,
			required: true
		},
		spedizioni: {
			type: Array,
			required: true
		},
		spedizione: {
			type: Object,
			default: () => ({})
		},
		metodoPagamentoActive: {
			type: Boolean,
			default: false
		},
		readonly: {
			type: Boolean,
			default: false
		}
    },
	emits: ['spedizione'],
    data() {
        return {
			form: {
				spedizione: this.spedizione
			}
        };
    },
	methods: {
		formatIva(value) {
			return Number(value).toLocaleString("it-IT", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		},
		updateAliquotaIva() {
			const iva = this.aliquoteIva.find(item => item.id === this.form.spedizione.iva.aliquota_iva_id);
			this.form.spedizione.iva.aliquota = iva.aliquota;
			this.activeSpedizione();
		},
		updateSpedizione() {
			if( this.form.spedizione.spedizione_id === 0 ) {
				this.form.spedizione = {
					prezzo: 0,
					sconto: 0,
					iva: {
						aliquota_iva_id: null,
						aliquota: ''
					},
					spedizione_id: 0
				};
			} else {
				const spedizione = this.spedizioni.find(item => item.id === this.form.spedizione.spedizione_id);
				this.form.spedizione.prezzo = spedizione.prezzo;
				this.form.spedizione.iva.aliquota_iva_id = spedizione.aliquota_iva.id;
				this.form.spedizione.iva.aliquota = spedizione.aliquota_iva.aliquota;
			}
			this.activeSpedizione();
		},
		activeSpedizione() {
			this.$emit('spedizione', this.form.spedizione);
		},
		getForm() {
			return this.form;
		}
	},
    watch: {
		spedizione: {
            deep: true,
            handler(newVal) {
                this.form.spedizione = { ...newVal };
            }
        }
    }
}
</script>