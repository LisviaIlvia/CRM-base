<template>
    <v-text-field
        :model-value="formatNumero(numeroValido)"
        :label="label"
        :prefix="prefix"
        :suffix="suffix"
        :color="color"
		:class="computedClass"
        @blur="handleBlur"
        @input="verificaInput"
        @focus="isFocused = true"
        :hide-details="hideDetails"
        :readonly="readonly"
        :error-messages="errorMessages"
    ></v-text-field>
</template>

<script>
export default {
    name: 'NumberDecimal',
    props: {
        modelValue: {
            type: [Number, String],
            required: true
        },
        label: {
            type: String,
            required: true
        },
        color: {
            type: String,
            required: true
        },
		bold: {
			type: Boolean,
			default: false
		},
		class: {
			type: String,
			default: ''
		},
        prefix: {
            type: String,
            default: undefined
        },
        suffix: {
            type: String,
            default: undefined
        },
        hideDetails: {
            type: Boolean,
            default: false
        },
        readonly: {
            type: Boolean,
            default: false
        },
        errorMessages: {
            type: [String, Array],
            default: () => []
        },
        decimal: {
            type: Number,
            default: 2
        }
    },
	data() {
		return this.initialState();
	},
	computed: {
		computedClass() {
			if (this.class !== '') {
				return this.class;
			} else if (this.bold) {
				return 'font-weight-bold';
			} else {
				return '';
			}
		}
	},
    methods: {
		initialState() {
			return {
				numeroValido: this.modelValue,
				isFocused: false
			};
		},
        formatNumero(value) {
            if (value !== null && !isNaN(value) && !this.isFocused) {
                return Number(value).toLocaleString("it-IT", { minimumFractionDigits: this.decimal, maximumFractionDigits: this.decimal });
            }
			if (this.readonly === false && (value === null || isNaN(Number(value)))) {
				return '0,00';
			}
			return value;
        },
        troncaNumero(value) {
            value = value.replace(",", ".");
            if (value !== null && !isNaN(value)) {
                const troncato = parseFloat(parseFloat(value).toFixed(this.decimal));
                this.$emit('update:modelValue', troncato);
            }
        },
        handleBlur(event) {
            this.troncaNumero(event.target.value);
            this.isFocused = false;
        },
        verificaInput(event) {
            const regex = /^[0-9]*[.,]?[0-9]*$/;
            const fullValue = event.target.value;
            if (!regex.test(fullValue)) {
                event.target.value = this.numeroValido;
            } else {
                this.numeroValido = fullValue;
            }
        }
    },
    watch: {
        modelValue(newVal) {
            this.numeroValido = newVal;
        }
    }
}
</script>
