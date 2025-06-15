<template>
	<v-row class="mx-0">
		<v-col cols="4" class="py-0">
			<number-decimal
				v-model="form.prezzo"
				label="Prezzo netto"
				:color="color"
				prefix="€"
				:readonly="form.tax_in || readonly"
				:errorMessages="errorMessages.prezzo"
                @update:modelValue="updateElement"
			/>
		</v-col>

		<v-col cols="4" class="py-0">
			<v-select
				v-model="form.aliquota_iva_id"
				:items="aliquoteIva.map(item => ({
					...item,
					aliquota: item.nome ? `${formatIva(item.aliquota)} - ${item.nome}` : formatIva(item.aliquota)
				}))"
				item-title="aliquota"
				item-value="id"
				prefix="%"
				label="Aliquota IVA"
				:color="color"
				:readonly="readonly"
				:disabled="form.aliquota_iva_predefinita"
				:errorMessages="errorMessages.aliquota_iva_id"
                @update:modelValue="updateElement"
			/>
		</v-col>

		<v-col cols="4" class="py-0">
			<number-decimal
				v-model="prezzoIvaInclusa"
				label="Prezzo lordo"
				:color="color"
				prefix="€"
				:readonly="!form.tax_in || readonly"
				@update:modelValue="updateElement"
			/>
		</v-col>

		<v-col cols="auto" class="py-0 mt-n5 mb-5">
			<v-switch
				v-model="form.aliquota_iva_predefinita"
				label="Utilizza l'aliquota IVA predefinita"
				:color="color"
				hide-details
				:readonly="readonly"
				@update:modelValue="initializeSelectedIva"
			/>
		</v-col>

		<v-col cols="auto" class="py-0 mt-n5 mb-5">
			<v-switch
				v-model="form.tax_in"
				label="Tasse incluse"
				:color="color"
				hide-details
				:readonly="readonly"
                @update:modelValue="updateElement"
			/>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'PrezzoIva',
	emits: ['update:modelValue'],
	props: {
		modelValue: {
			type: Object,
			required: true
		},
		aliquoteIva: {
			type: Array,
			default: () => []
		},
		color: {
			type: String,
			required: true
		},
		errorMessages: {
			type: Object,
			default: () => {}
		},
		readonly: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			form: { ...this.modelValue },
			prezzoIvaInclusa: 0,
			aliquotaPredefinitaActive: true
		};
	},
	methods: {
		formatIva(value) {
			return Number(value).toLocaleString('it-IT', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			});
		},
		calcolaPrezzoIvato() {
			if(this.form.tax_in === true) {
				if(this.prezzoIvaInclusa != null && this.form.aliquota_iva_id != null) {
					if(this.form.prezzo > 0 && this.prezzoIvaInclusa == 0) {
						this.initializePrice();
					} else {
						const ivaSelezionata = this.aliquoteIva.find(iva => iva.id === this.form.aliquota_iva_id);
						const aliquotaIVA = ivaSelezionata ? parseFloat(ivaSelezionata.aliquota) : 0;
						const prezzoIvaInclusa = parseFloat(this.prezzoIvaInclusa) || 0;
						this.form.prezzo = prezzoIvaInclusa / (1 + aliquotaIVA / 100);
						this.form.prezzo = parseFloat(this.form.prezzo.toFixed(2));
					}
				}
			} else {
				this.initializePrice();
			}
		},
		initializePrice() {
			if (this.form.prezzo != null && this.form.aliquota_iva_id != null) {
				const ivaSelezionata = this.aliquoteIva.find(iva => iva.id === this.form.aliquota_iva_id);
				const aliquotaIVA = ivaSelezionata ? parseFloat(ivaSelezionata.aliquota) : 0;
				const prezzoBase = parseFloat(this.form.prezzo) || 0;
				this.prezzoIvaInclusa = prezzoBase + (prezzoBase * aliquotaIVA) / 100;
				this.prezzoIvaInclusa = parseFloat(this.prezzoIvaInclusa.toFixed(2));
			}
		},
		initializeSelectedIva() {
			const predefinitaIva = this.aliquoteIva.find(iva => iva.predefinita === true);
			if (predefinitaIva) {
				this.form.aliquota_iva_id = predefinitaIva.id;
			}
			this.updateElement();
		},
        updateElement() {
			this.calcolaPrezzoIvato();
            this.$emit('update:modelValue', { ...this.form });
        }
	},
	watch: {
		modelValue: {
			immediate: true,
			deep: true,
			handler(newVal) {
				this.form = { ...this.form, ...newVal };
				this.calcolaPrezzoIvato();
			}
		},
		aliquoteIva: {
			handler(newVal) {
				if (newVal.length > 0) {
					this.initializeSelectedIva();
					this.calcolaPrezzoIvato();
                    this.updateElement();
				}
			},
			immediate: true
		}
	}
};
</script>
