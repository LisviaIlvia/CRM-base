<template>
	<v-col
	  cols="12"
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
						<header-box title="Trasporto" icon="fa-solid fa-truck-fast" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row
				class="px-4 mt-4 pb-2"
			>
				<v-col cols="2" class="py-0">
					<v-text-field
						v-model="form.trasporto.colli"
						label="Colli"
						:color="color"
						type="number"
						:error-messages="errors && errors['trasporto.colli'] ? errors['trasporto.colli'] : ''"
						:readonly="readonly"
					></v-text-field>
				</v-col>
				<v-col cols="2" class="py-0">
					<number-decimal
						v-model="form.trasporto.peso"
						label="Peso"
						:color="color"
						prefix="Kg"
						:error-messages="errors && errors['trasporto.peso'] ? errors['trasporto.peso'] : ''"
						:readonly="readonly"
					/>
				</v-col>
				<v-col cols="2" class="py-0">
					<v-text-field
						v-model="form.trasporto.porto"
						label="Porto"
						:color="color"
						:error-messages="errors && errors['trasporto.porto'] ? errors['trasporto.porto'] : ''"
						:readonly="readonly"
					></v-text-field>
				</v-col>
				<v-col cols="3" class="py-0">
					<v-select
						v-model="form.trasporto.a_cura"
						:items="data.trasportatori"
						label="Trasporto a cura del"
						:color="color"
						:error-messages="errors && errors['trasporto.a_cura'] ? errors['trasporto.a_cura'] : ''"
						:readonly="readonly"
					></v-select>
				</v-col>
				<v-col cols="3" class="py-0">
					<v-text-field
						v-model="form.trasporto.vettore"
						label="Vettore"
						:color="color"
						:error-messages="errors && errors['trasporto.vettore'] ? errors['trasporto.vettore'] : ''"
						:disabled="form.trasporto.a_cura !== 'Vettore'"
						:readonly="readonly"
					></v-text-field>
				</v-col>
				<v-col cols="12" class="py-0">
					<v-text-field
						v-model="form.trasporto.causale"
						label="Causale trasporto"
						:color="color"
						:error-messages="errors && errors['trasporto.causale'] ? errors['trasporto.causale'] : ''"
						:readonly="readonly"
					></v-text-field>
				</v-col>
				<v-col cols="12" class="py-0">
					<v-textarea
						v-model="form.trasporto.annotazioni"
						label="Annotazioni"
						:color="color"
						:error-messages="errors && errors['trasporto.annotazioni'] ? errors['trasporto.annotazioni'] : ''"
						:readonly="readonly"
					></v-textarea>
				</v-col>
			</v-row>
		</v-card>
	</v-col>
</template>

<script>
export default {
    name: 'DocumentsTrasporto',
    props: {
		trasporto: {
			type: Object,
			default: () => ({})
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
    data() {
        return {
			form: {
				trasporto: this.trasporto
			},
			data: {
				trasportatori: ['Destinatario', 'Mittente', 'Vettore']
			}
        };
    },
	methods: {
		getForm() {
			return this.form;
		}
	},
    watch: {
        trasporto: {
            deep: true,
            handler(newVal) {
                this.form.trasporto = { ...newVal };
            }
        }
    }
}
</script>