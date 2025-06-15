import axiosService from '@/lib/axiosService';
import apiDunpService from '@/lib/apiDunpService';
import crudTable from '@/lib/crudTable';
import crudDialog from '@/lib/crudDialog';
import tooltipDialog from '@/lib/tooltipDialog';
import { createApp, h } from 'vue';
import { createInertiaApp, Link, usePage } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';
import NProgress from 'nprogress';
import vuetify from '@/plugins/vuetify';

import LayoutGuest from '@/Layouts/LayoutGuest.vue';
import LayoutAuth from '@/Layouts/LayoutAuth.vue';
import LayoutComponent from '@/Layouts/LayoutComponent.vue';

import DialogCreate from '@/Components/Dialog/DialogCreate.vue';
import DialogEdit from '@/Components/Dialog/DialogEdit.vue';
import DialogShow from '@/Components/Dialog/DialogShow.vue';
import DialogDelete from '@/Components/Dialog/DialogDelete.vue';
import DialogClone from '@/Components/Dialog/DialogClone.vue';
import DialogMagic from '@/Components/Dialog/DialogMagic.vue';

import HeaderBox from '@/Components/HeaderBox.vue';
import NumberDecimal from '@/Components/NumberDecimal.vue';

import DocumentsHeader from '@/Components/Documents/Header/DocumentsHeader.vue';
import DocumentsBody from '@/Components/Documents/Body/DocumentsBody.vue';
import DocumentsFooter from '@/Components/Documents/Footer/DocumentsFooter.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
		const defaultLayout = name.startsWith('Public/') ? LayoutGuest : LayoutAuth;
		const page = resolvePageComponent(
			`./Pages/${name}.vue`,
			import.meta.glob("./Pages/**/*.vue")
		);
		page.then((module) => {
			module.default.layout = defaultLayout;
		});
		return page;
	},
	progress: (progress) => {
		NProgress.set(progress / 100)
	},
	setup({ el, App, props, plugin }) {
		const app = createApp({
			render: () => h(App, props) },
		)
		.use(plugin)
		.use(vuetify)
		.use(createPinia())
		.use(ZiggyVue)
		.component('Link', Link)
		.component('DialogCreate', DialogCreate)
		.component('DialogEdit', DialogEdit)
		.component('DialogShow', DialogShow)
		.component('DialogDelete', DialogDelete)
		.component('DialogClone', DialogClone)
		.component('DialogMagic', DialogMagic)
		.component('HeaderBox', HeaderBox)
		.component('NumberDecimal', NumberDecimal)
		.component('DocumentsHeader', DocumentsHeader)
		.component('DocumentsBody', DocumentsBody)
		.component('DocumentsFooter', DocumentsFooter)
		
		const myAxiosInstance = new axiosService();
		app.config.globalProperties.$axiosService = myAxiosInstance;
		app.config.globalProperties.$apiDunpService = apiDunpService;
		app.config.globalProperties.$crudTable = crudTable;
		app.config.globalProperties.$crudDialog = crudDialog;
		app.config.globalProperties.$tooltipDialog = tooltipDialog;
		app.config.globalProperties.$usePage = usePage;
		app.config.globalProperties.$route = route
		app.mount(el);
	}
});

