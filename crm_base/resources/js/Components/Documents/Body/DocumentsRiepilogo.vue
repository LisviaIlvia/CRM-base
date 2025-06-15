<template>
	<v-col v-if="rateActive"
		cols="4" 
		class="py-0 mt-6 d-flex flex-column"
	>
		<v-card elevation="4" variant="flat" class="flex-grow-1 d-flex flex-column">
			<v-toolbar class="px-3">
				<v-row class="align-center bg-grey-lighten-3">
					<v-col cols="12">
						<header-box title="Rate pagamenti" icon="fa-solid fa-cash-register" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row class="px-4 mt-8 pb-5 flex-grow-1">
				<v-col cols="12">
					<v-row class="mb-7">
						<v-col cols="12" class="py-0">
							<v-number-input 
								v-model="numeroRate"
								:min="1"
								:color="color"
								control-variant="split"
								variant="outlined"
								:disabled="readonly"
								hide-details
							></v-number-input>
						</v-col>
					</v-row>
					<v-row v-for="(rata, index) in form.rate" :key="index" class="mb-5">
						<v-col class="py-0">
							<v-text-field
								v-if="readonly"
								:model-value="showDate(rata.data)"
								label="Data2"
								:color="color"
								prepend-inner-icon="fa-solid fa-calendar"
								readonly
								hide-details
							></v-text-field>
							<v-date-input 
								v-else
								v-model="rata.data"
								placeholder="gg/mm/aaaa"
								variant="outlined"
								label="Data"
								:color="color"
								:prepend-icon="null"
								prepend-inner-icon="fa-solid fa-calendar"
								hide-details
							></v-date-input>
						</v-col>
						<v-col class="py-0">
							<number-decimal
								v-model="rata.percentuale"
								label="Percentuale"
								:color="color"
								prefix="%"
								:hide-details="true"
								:readonly="readonly"
								@update:modelValue="() => { updateRataImporto(value, rata); checkPercentuali(); }"
							/>
						</v-col>
						<v-col class="py-0">
							<number-decimal
								v-model="rata.importo"
								label="Importo imponibile"
								:color="color"
								prefix="€"
								:hide-details="true"
								:readonly="true"
							/>
						</v-col>
					</v-row>
					<v-row v-if="errorPercentuale" class="mt-3">
						<v-col cols="12">
							<v-alert
								text="La somma delle percentuali deve essere 100%"
								type="error"
								variant="tonal"
							/>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card>
	</v-col>
	<v-col 
		:cols="rateActive ? 4 : 6" 
		class="py-0 mt-6 d-flex flex-column"
	>
		<v-card elevation="4" variant="flat" class="flex-grow-1 d-flex flex-column">
			<v-toolbar class="px-3">
				<v-row class="align-center bg-grey-lighten-3">
					<v-col cols="12">
						<header-box title="Riepilogo IVA" icon="fa-solid fa-percent" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row class="px-4 mt-8 pb-2 flex-grow-1">
				<v-col cols="12">
					<v-col cols="12" v-if="isTotaliPerIVAEmpty" class="text-center py-5 mt-n6">
						<span class="grey--text">Non sono presenti elementi per il calcolo IVA</span>
					</v-col>
					<v-row v-else v-for="(totals, aliquotaIVA) in totaliPerIVA" :key="aliquotaIVA" class="mb-5">
						<v-col cols="4" class="py-0">
							<number-decimal
								v-model="totals.imponibile"
								label="Imponibile"
								:color="color"
								prefix="€"
								:hide-details="true"
								:readonly="true"
							/>
						</v-col>
						<v-col cols="4" class="py-0">
							<v-text-field
								v-model="totals.aliquota"
								label="Aliquota"
								:color="color"
								prefix="%"
								readonly
								hide-details
							></v-text-field>
						</v-col>
						<v-col cols="4" class="py-0">
							<number-decimal
								v-model="totals.iva"
								label="IVA"
								:color="color"
								prefix="€"
								:hide-details="true"
								:readonly="true"
							/>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card>
	</v-col>
	<v-col 
		:cols="rateActive ? 4 : 6" 
		class="py-0 mt-6 d-flex flex-column"
	>
		<v-card elevation="4" variant="flat" class="flex-grow-1 d-flex flex-column">
			<v-toolbar class="px-3">
				<v-row class="align-center bg-grey-lighten-3">
					<v-col cols="12">
						<header-box title="Riepilogo Documento" icon="fa-solid fa-cash-register" />
					</v-col>
				</v-row>
			</v-toolbar>
			<v-row class="px-4 mt-8 pb-5 flex-grow-1">
				<v-col cols="12" class="py-0 mb-5">
					<number-decimal
						v-model="totaliComplessivi.subtotale"
						label="Subtotale"
						:color="color"
						prefix="€"
						:hide-details="true"
						:readonly="true"
					/>
				</v-col>
				<v-col cols="12" class="py-0 mb-5">
					<number-decimal
						v-model="totaliComplessivi.sconto"
						label="Totale sconto"
						:color="color"
						prefix="€"
						:hide-details="true"
						:readonly="true"
					/>
				</v-col>
				<v-col cols="12" class="py-0 mb-5">
					<number-decimal
						v-model="totaliComplessivi.imponibile"
						label="Totale imponibile"
						:color="color"
						prefix="€"
						:hide-details="true"
						:readonly="true"
					/>
				</v-col>
				<v-col cols="12" class="py-0 mb-5">
					<number-decimal
						v-model="totaliComplessivi.iva"
						label="Totale IVA"
						:color="color"
						prefix="€"
						:hide-details="true"
						:readonly="true"
					/>
				</v-col>
				<v-col cols="12" class="py-0 mb-5">
					<number-decimal
						v-model="totaliComplessivi.totale"
						label="Totale documento"
						:color="color"
						:bold="true"
						prefix="€"
						:hide-details="true"
						:readonly="true"
					/>
				</v-col>
			</v-row>
		</v-card>
	</v-col>
</template>

<script>
export default {
    name: 'DocumentsRiepilogo',
    props: {
        elementi: {
            type: Array,
            required: true
        },
		spedizione: {
			type: Object,
			default: () => ({})
		},
		rate: {
            type: Array,
            required: true
        },
		rateActive: {
			type: Boolean,
			default: false
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
	computed: {
		isTotaliPerIVAEmpty() {
			return Object.keys(this.totaliPerIVA).length === 0;
		}
	},
    data() {
        return {
			form: {
				rate: [
					{ 
						data: null, 
						percentuale: 100,
						importo: 0
					}
				]
			},
			numeroRate: 1,
			errorPercentuale: false,
            totaliPerIVA: {},
			totaliComplessivi: {
				subtotale: 0,
				sconto: 0,
				imponibile: 0,
				iva: 0,
				totale: 0
			}
        };
    },
    methods: {
        calcolaTotaliIVA() {
			let totali = {};
			this.elementi.forEach(elemento => {
				if(elemento.tipo !== 'descrizione') {
					const aliquotaIVA = elemento.iva.aliquota_iva_id;
					if (!totali[aliquotaIVA]) {
						totali[aliquotaIVA] = { subtotale: 0, sconto: 0, imponibile: 0, aliquota: '', iva: 0, totale: 0 };
					}
					
					const subtotale = elemento.quantita ? parseFloat(elemento.quantita) * parseFloat(elemento.prezzo) : 0;
					
					let sconto = 0;
					if (elemento.tipo_sconto === '%') {
						sconto = subtotale * (parseFloat(elemento.sconto) / 100);
					} else {
						sconto = parseFloat(elemento.sconto) || 0;
					}

					const imponibile = subtotale - sconto;
					const aliquota = elemento.iva.aliquota;
					const iva = imponibile * (parseFloat(elemento.iva.aliquota) / 100);

					totali[aliquotaIVA].subtotale += subtotale;
					totali[aliquotaIVA].sconto += sconto;
					totali[aliquotaIVA].imponibile += imponibile;
					totali[aliquotaIVA].aliquota = aliquota;
					totali[aliquotaIVA].iva += iva;
					totali[aliquotaIVA].totale += imponibile + iva;
				}
			});
			if(this.spedizione && this.spedizione.spedizione_id !== 0) {
				const aliquotaIVA = this.spedizione.iva.aliquota_iva_id;
				if (!totali[aliquotaIVA]) {
					totali[aliquotaIVA] = { subtotale: 0, sconto: 0, imponibile: 0, aliquota: '', iva: 0, totale: 0 };
				}
				
				const subtotale = parseFloat(this.spedizione.prezzo);
				const sconto = subtotale * (parseFloat(this.spedizione.sconto) / 100);
				const imponibile = subtotale - sconto;
				const aliquota = this.spedizione.iva.aliquota;
				const iva = imponibile * (parseFloat(this.spedizione.iva.aliquota) / 100);

				totali[aliquotaIVA].subtotale += subtotale;
				totali[aliquotaIVA].sconto += sconto;
				totali[aliquotaIVA].imponibile += imponibile;
				totali[aliquotaIVA].aliquota = aliquota;
				totali[aliquotaIVA].iva += iva;
				totali[aliquotaIVA].totale += imponibile + iva;
			}
			this.totaliPerIVA = totali;
		},
		calcolaTotaliComplessivi() {
			let complessivi = {
				subtotale: 0,
				sconto: 0,
				imponibile: 0,
				iva: 0,
				totale: 0
			};
			
			Object.values(this.totaliPerIVA).forEach(totale => {
				complessivi.subtotale += totale.subtotale;
				complessivi.sconto += totale.sconto;
				complessivi.imponibile += totale.imponibile;
				complessivi.iva += totale.iva;
				complessivi.totale += totale.totale;
			});

			complessivi.subtotale = complessivi.subtotale.toFixed(2);
			complessivi.sconto = complessivi.sconto.toFixed(2);
			complessivi.imponibile = complessivi.imponibile.toFixed(2);
			complessivi.iva = complessivi.iva.toFixed(2);
			complessivi.totale = complessivi.totale.toFixed(2);

			this.totaliComplessivi = complessivi;
		},
		showDate(data) {
			const date = new Date(data);

			const day = String(date.getDate()).padStart(2, '0');
			const month = String(date.getMonth() + 1).padStart(2, '0');
			const year = date.getFullYear();

			return `${day}/${month}/${year}`;
		},
		updateRataImporto(percentuale, rata) {
			rata.importo = this.totaliComplessivi.imponibile * (percentuale / 100);
		},
		checkPercentuali() {
			const sommaPercentuali = this.form.rate.reduce((acc, rata) => acc + parseFloat(rata.percentuale), 0);
			const sommaFormattata = parseFloat(sommaPercentuali.toFixed(2));

			if (sommaFormattata !== 100) {
				this.errorPercentuale = true;
			} else {
				this.errorPercentuale = false;
			}
		},
		formatDate(data_rata) {
			if(data_rata !== null) {
				const day = `0${data_rata.getDate()}`.slice(-2);
				const month = `0${data_rata.getMonth() + 1}`.slice(-2);
				const year = data_rata.getFullYear();
				data_rata = `${year}-${month}-${day}`;
			}
			
			return data_rata;
		},
		getForm() {
			let form = this.form;
			form.rate.forEach((rata) => {
				if (rata.data !== null) {
					rata.data = this.formatDate(new Date(rata.data));
				}
			});
			return form;
		}
    },
	watch: {
		elementi: {
			immediate: true,
			deep: true,
			handler(newVal, oldVal) {
				this.calcolaTotaliIVA();
				this.calcolaTotaliComplessivi();
				this.form.rate.forEach((rata) => {
					this.updateRataImporto(rata.percentuale, rata);
				});
			}
		},
		spedizione: {
			immediate: true,
			deep: true,
			handler(newVal, oldVal) {
				this.calcolaTotaliIVA();
				this.calcolaTotaliComplessivi();
			}
		},
		numeroRate(newVal) {
			const difference = newVal - this.form.rate.length;
			if (difference > 0) {
				for (let i = 0; i < difference; i++) {
					this.form.rate.push({ data: null, percentuale: 0, importo: 0 });
				}
			} else if (difference < 0) {
				this.form.rate.splice(difference);
			}
		},
		rate(newVal) {
			this.form.rate = newVal;
			this.numeroRate = this.form.rate.length;
		},
		'form.rate': {
			deep: true,
			handler() {
				this.checkPercentuali();
			}
		}
	}
}
</script>