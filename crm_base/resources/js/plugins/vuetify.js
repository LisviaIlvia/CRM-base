import 'vuetify/styles';
import '@fortawesome/fontawesome-free/css/all.css';

import { createVuetify } from 'vuetify';
import { it } from 'vuetify/locale';
import * as components from 'vuetify/components';
import { VNumberInput } from 'vuetify/labs/VNumberInput';
import { VDateInput } from 'vuetify/labs/VDateInput'
import { VTimePicker } from 'vuetify/labs/VTimePicker'
import * as directives from 'vuetify/directives';
import colors from 'vuetify/lib/util/colors';
import { aliases, fa } from 'vuetify/iconsets/fa';
import SvgIcon from '@/Components/SvgIcon.vue';

const themeLight = {
    dark: false,
	colors: {
		background: '#FFFFFF',
		surface: '#FFFFFF',
		'surface-variant': '#424242',
		'on-surface-variant': '#EEEEEE',
		primary: '#006580',
		'primary-darken-1': '#3700B3',
		secondary: '#03DAC6',
		'secondary-darken-1': '#018786',
		error: '#B00020',
		info: '#2196F3',
		success: '#4CAF50',
		warning: '#FB8C00'
	},
	variables: {
		'border-color': '#000000',
		'border-opacity': 0.12,
		'high-emphasis-opacity': 0.87,
		'medium-emphasis-opacity': 0.60,
		'disabled-opacity': 0.38,
		'idle-opacity': 0.04,
		'hover-opacity': 0.04,
		'focus-opacity': 0.12,
		'selected-opacity': 0.08,
		'activated-opacity': 0.12,
		'pressed-opacity': 0.12,
		'dragged-opacity': 0.08,
		'theme-kbd': '#212529',
		'theme-on-kbd': '#FFFFFF',
		'theme-code': '#F5F5F5',
		'theme-on-code': '#000000'
	}
};

const myTheme = {
	dark: false,
	colors: {
		background: '#FFFFFF',
		surface: '#FFFFFF',
		'surface-variant': '#424242',
        'on-surface-variant': colors.grey.lighten5,
		primary: '#2496d4',
		'primary-darken-1': colors.deepPurple.darken1,
		secondary: colors.blueGrey.lighten1,
		'secondary-darken-1': colors.blueGrey.darken1,
		error: colors.red.lighten1,
		info: colors.cyan.lighten1,
		success: colors.green.lighten1,
		warning: colors.orange.lighten1,
		'color-show': colors.teal.lighten1,
		'color-create': colors.blue.lighten1,
		'color-edit': colors.amber.darken1,
		'color-delete': colors.red.lighten1,
		'color-export-excel': colors.green.lighten1,
		'color-clone': colors.pink.darken4,
		'color-pdf': colors.red.darken4,
		'color-magic': colors.purple.lighten1,
		'color-email': colors.indigo.lighten1,
		'color-doc': colors.deepPurple.lighten1,
		'color-mag': colors.pink.lighten1
	},
	variables: {
		'border-color': '#d9d9d9',
		'border-opacity': 1,
		'high-emphasis-opacity': 1,
		'medium-emphasis-opacity': 1,
		'disabled-opacity': 0.38,
		'idle-opacity': 0.04,
		'hover-opacity': 0.04,
		'focus-opacity': 0.12,
		'selected-opacity': 0.08,
		'activated-opacity': 0.12,
		'pressed-opacity': 0.12,
		'dragged-opacity': 0.08,
		'theme-kbd': '#212529',
		'theme-on-kbd': '#FFFFFF',
		'theme-code': '#F5F5F5',
		'theme-on-code': '#000000',
		'theme-on-surface': '#495057',
		'theme-on-warning': '#FFFFFF',
		'theme-on-background': '#495057',
		'theme-on-color-show': '#FFFFFF',
		'theme-on-color-create': '#FFFFFF',
		'theme-on-color-edit': '#FFFFFF',
		'theme-on-color-delete': '#FFFFFF'
    }
}

export default createVuetify({
	ssr: true,
	locale: {
		locale: 'it',
		fallback: 'it',
		messages: { it }
	},
	theme: {
		defaultTheme: 'myTheme',
		themes: {
			myTheme
		}
	},
	defaults: {
		VCard: {
			elevation: 8
		},
		VBtn: {
			elevation: 0
		},
		VTextField: {
			variant: 'outlined',
			color: 'primary'
		},
		VAutocomplete: {
			variant: 'outlined',
			color: 'primary'
		},
		VTextarea: {
			variant: 'outlined',
			color: 'primary'
		},
		VFileInput: {
			variant: 'outlined',
			color: 'primary'
		},
		VSelect: {
			variant: 'outlined',
			color: 'primary'
		},
		VCheckbox: {
			color: 'primary'
		}
	},
	icons: {
		defaultSet: 'fa',
		aliases,
		sets: {
			fa,
			custom: {
				component: SvgIcon,
			}
		},
	},
	components: {
		...components,
		VNumberInput,
		VDateInput,
		VTimePicker
	},
	directives
});