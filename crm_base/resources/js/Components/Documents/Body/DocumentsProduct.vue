<template>
	<v-col class="py-0 col-nome">
		<v-text-field
			v-model="nome"
			label="Nome"
			:color="color"
			:readonly="tipo !== 'altro' || readonly"
			hide-details
			@update:modelValue="updateElement"
		></v-text-field>
	</v-col>
	<v-col class="py-0 col-qt">
		<v-text-field
			v-model="quantita"
			label="Qta"
			:color="color"
			type="number"
			hide-details
			:readonly="readonly"
			@update:modelValue="updateElement"
		></v-text-field>
	</v-col>
	<v-col class="py-0 col-um">
		<v-select
			v-model="unita_misura"
			:items="['NR']"
			label="Um"
			:color="color"
			hide-details
			:readonly="tipo !== 'altro' || readonly"
			@update:modelValue="updateElement"
		></v-select>
	</v-col>
	<v-col class="py-0 col-prezzo">
		<number-decimal
			v-model="prezzo"
			label="Prezzo"
			:color="color"
			prefix="€"
			:hideDetails="true"
			@update:modelValue="updateElement"
			:readonly="readonly"
		/>
	</v-col>
	<v-col class="py-0 col-tipo-sconto">
		<v-select
			v-model="tipo_sconto"
			:items="['%', '€']"
			label="Tipo sconto"
			:color="color"
			hide-details
			:readonly="readonly"
			@update:modelValue="updateElement"
		></v-select>
	</v-col>
	<v-col class="py-0 col-sconto">
		<number-decimal
			v-model="sconto"
			label="Sconto"
			:color="color"
			:prefix="tipo_sconto"
			:hideDetails="true"
			@update:modelValue="updateElement"
			:readonly="readonly"
		/>
	</v-col>
	<v-col class="py-0 col-prezzo">
		<number-decimal
			v-model="importo"
			label="Importo"
			:color="color"
			prefix="€"
			:hideDetails="true"
			@update:modelValue="updateElement"
			:readonly="true"
		/>
	</v-col>
	<v-col class="py-0 col-iva">
		<v-select
			v-model="iva.aliquota_iva_id"
			:items="aliquoteIva.map(item => ({
				...item,
				aliquota: item.nome
					? `${formatIva(item.aliquota)} - ${item.nome}`
					: formatIva(item.aliquota)
			}))"
			item-value="id"
			item-title="aliquota"
			prefix="%"
			label="IVA"
			:color="color"
			hide-details
			:readonly="readonly"
			@update:modelValue="updateElement"
		></v-select>
	</v-col>
	<v-col class="py-0 col-ricorrenza">
		<v-select
			v-model="ricorrenza"
			:items="ricorrenze"
			item-value="value"
			item-title="title"
			label="Ricorrenza"
			:color="color"
			hide-details
			:readonly="readonly || tipo == 'merci'"
			@update:modelValue="updateElement"
		></v-select>
	</v-col>
</template>

<style scoped>
	.col-descrizione {
		flex: 0 0 94%;
		max-width: 94%;
	}
	.col-nome {
		flex: 0 0 28%;
		max-width: 28%;
	}
	.col-prezzo {
		flex: 0 0 8%;
		max-width: 8%;
	}
	.col-tipo-sconto {
		flex: 0 0 8%;
		max-width: 8%;
	}
	.col-sconto {
		flex: 0 0 7%;
		max-width: 7%;
	}
	.col-qt {
		flex: 0 0 7%;
		max-width: 7%;
	}
	.col-um {
		flex: 0 0 6%;
		max-width: 6%;
	}
	.col-iva {
		flex: 0 0 11%;
		max-width: 11%;
	}
	.col-ricorrenza {
		flex: 0 0 11%;
		max-width: 11%;
	}
</style>

<script>
export default {
	name: 'DocumentsProduct',
	props: {
		aliquoteIva: {
			type: Object,
			required: true
		},
		ricorrenze: {
			type: Array,
			required: true
		},
		modelValue: {
			type: Object,
			required: true
		},
		color: {
			type: String,
			required: true
		},
		readonly: {
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
				...this.modelValue
			};
		},
		updateElement() {
			this.$emit("update:modelValue", { ...this.$data });
		},
		formatIva(value) {
			return Number(value).toLocaleString("it-IT", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}
	},
	watch: {
		modelValue: {
			deep: true,
			handler(newVal) {
				Object.assign(this.$data, newVal);
			},
		}
	}
}
</script>