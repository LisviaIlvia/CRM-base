<template>
	<v-col
	  :cols="spedizioneActive ? 6 : 12"
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
						<header-box title="Metodo di Pagamento" icon="fa-solid fa-cash-register" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row
				class="px-4 mt-8 pb-2 mb-3"
			>
				<v-col
					cols="6"
					class="py-0 mb-2"
				>
					<v-select
						v-model="form.metodo_pagamento_id"
						:items="metodiPagamento"
						item-value="id"
						item-title="nome"
						label="Metodo di pagamento"
						:color="color"
						hide-details
						:readonly="readonly"
					></v-select>
				</v-col>
				<v-col
					cols="6"
					class="py-0 mb-2"
				>
					<v-select
						v-model="form.conto_bancario_id"
						:items="contiBancari"
						item-value="id"
						item-title="nome"
						label="Conti bancari"
						:color="color"
						hide-details
						:readonly="readonly"
						:disabled="form.metodo_pagamento_id === 0"
					></v-select>
				</v-col>
			</v-row>
		</v-card>
	</v-col>
</template>

<script>
export default {
    name: 'DocumentsMetodoPagamento',
    props: {
		metodiPagamento: {
			type: Array,
			required: true
		},
		contiBancari: {
			type: Array,
			required: true
		},
		spedizioneActive: {
			type: Boolean,
			default: false
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
	mounted() {
		this.$emit('ready');
	},
    data() {
        return {
			form: {
				metodo_pagamento_id: null,
				conto_bancario_id: null
			}
        };
    },
	methods: {
		getForm() {
			return this.form;
		}
	}
}
</script>