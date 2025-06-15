<template>
	<i v-html="iconContent" class="v-icon--size-default"></i>
</template>

<style scoped>
	.svg-icon {
		display: inline-block;
	}
</style>

<script>
import ddtEntrataIcon from '@/assets/icons/ddt-entrata.svg?raw';
import ddtUscitaIcon from '@/assets/icons/ddt-uscita.svg?raw';
import documentiMaceroIcon from '@/assets/icons/documenti-macero.svg?raw';
import fattureAcquistoIcon from '@/assets/icons/fatture-acquisto.svg?raw';
import fattureVenditaIcon from '@/assets/icons/fatture-vendita.svg?raw';
import ricevuteVenditaIcon from '@/assets/icons/ricevute-vendita.svg?raw';
import saggiOmaggioIcon from '@/assets/icons/saggi-omaggio.svg?raw';
import ordiniAcquistoIcon from '@/assets/icons/ordini-acquisto.svg?raw';
import ordiniVenditaIcon from '@/assets/icons/ordini-vendita.svg?raw';
import notaCreditoAttiveIcon from '@/assets/icons/note-credito-attive.svg?raw';
import notaCreditoPassiveIcon from '@/assets/icons/note-credito-passive.svg?raw';
import acquisti from '@/assets/icons/acquisti.svg?raw';
import vendite from '@/assets/icons/vendite.svg?raw';

export default {
	props: {
		icon: {
			type: String,
			required: true,
		}
	},
	computed: {
		iconContent() {
			const icons = {
				'ddt-entrata': ddtEntrataIcon,
				'ddt-uscita': ddtUscitaIcon,
				'documenti-macero': documentiMaceroIcon,
				'fatture-acquisto': fattureAcquistoIcon,
				'fatture-vendita': fattureVenditaIcon,
				'ricevute-vendita': ricevuteVenditaIcon,
				'saggi-omaggio': saggiOmaggioIcon,
				'ordini-acquisto': ordiniAcquistoIcon,
				'ordini-vendita': ordiniVenditaIcon,
				'note-credito-attive': notaCreditoAttiveIcon,
				'note-credito-passive': notaCreditoPassiveIcon,
				'acquisti': acquisti,
				'vendite': vendite
			};
			const content = icons[this.icon] || '';
			return content;
		}
	}
};
</script>

