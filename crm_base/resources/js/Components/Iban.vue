<template>
	<v-col
	  cols="12"
	  class="py-0"
	>
		<v-text-field
			v-model="iban"
			label="IBAN"
			:color="color"
			:hide-details="hideDetails"
			:readonly="readonly"
			:error-messages="errorMessages"
			@input="onIbanChange"
		></v-text-field>
	</v-col>
	<v-col
	  cols="2"
	  class="py-0"
	>
		<v-text-field
			v-model="cin"
			label="CIN"
			:color="color"
			readonly
		></v-text-field>
	</v-col>
	<v-col
	  cols="3"
	  class="py-0"
	>
		<v-text-field
			v-model="abi"
			label="ABI"
			:color="color"
			readonly
		></v-text-field>
	</v-col>
	<v-col
	  cols="3"
	  class="py-0"
	>
		<v-text-field
			v-model="cab"
			label="CAB"
			:color="color"
			readonly
		></v-text-field>
	</v-col>
	<v-col
	  cols="4"
	  class="py-0"
	>
		<v-text-field
			v-model="conto_corrente"
			label="Conto Corrente"
			:color="color"
			readonly
		></v-text-field>
	</v-col>
</template>

<script>
export default {
	name: 'Iban',
	props: {
		modelValue: {
            type: [String, null],
            required: true
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
            default: null
        },
		hideDetails: {
            type: Boolean,
            default: false
        }
	},
	mounted() {
		this.onIbanChange();
	},
	data() {
		return this.initialState();
	},
    methods: {
		initialState() {
			return {
				iban: this.modelValue,
				cin: null,
				abi: null,
				cab: null,
				conto_corrente: null
			};
		},
		onIbanChange() {
			if(typeof this.iban === 'string') {
				this.iban = this.iban.replace(/\s+/g, '');
				this.$emit('update:modelValue', this.iban);
				if(this.iban && this.iban.length >= 27) {
					this.cin = this.iban.substring(4, 5);
					this.abi = this.iban.substring(5, 10);
					this.cab = this.iban.substring(10, 15);
					this.conto_corrente = this.iban.substring(15);
				} else {
					this.resetValues();
				}
			} else {
				this.resetValues();
			}
		},
		resetValues() {
			this.cin = null;
			this.abi = null;
			this.cab = null;
			this.conto_corrente = null;
		}
	},
	watch: {
        modelValue(newVal) {
            this.iban = newVal;
			if (newVal) {
				this.onIbanChange();
			}
        }
    }
}
</script>