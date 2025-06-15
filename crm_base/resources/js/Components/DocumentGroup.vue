<template>
	<v-card>
		<v-tabs v-model="tab" bg-color="grey-lighten-3">
			<v-tab v-for="tabItem in availableTabs" :key="tabItem.key" :value="tabItem.key">
				{{ tabItem.label }}
			</v-tab>
		</v-tabs>
		<v-card-text>
			<v-tabs-window v-model="tab">
				<keep-alive>
					<v-tabs-window-item 
						v-for="tabItem in availableTabs"
						:value="tabItem.key"
					>
						<crud-index
							:options="tabItem.setup"
							:headersChild="tabItem.headers"
							:crudTableChild="tabItem.crudTable"
							:componentsChild="tabItem.components"
							:dialogSetupChild="tabItem.dialogSetup"
							:elementId="entityId"
							:elementCustom="entityType"
						/>
					</v-tabs-window-item>
				</keep-alive>
			</v-tabs-window>
		</v-card-text>
	</v-card>
</template>

<script>
import CrudIndex from '@/Pages/Crud/CrudIndex.vue';
export default {
	name: 'DocumentoGroup',
	components: {
		CrudIndex
	},
	props: {
		documents: {
			type: Object,
			required: true,
			default: () => ({})
		},
		entityId: {
			type: Number,
			default: null
		},
		entityType: {
			type: String,
			default: null
		}
	},
	data() {
		return {
			tab: null
		};
	},
	computed: {
		availableTabs() {
			const tabs = Object.keys(this.documents).map(key => {
				const doc = this.documents[key];
				return {
					key: key,
					rawKey: key,
					label: this.documents[key].title,
					crudTable: new this.$crudTable(this.documents[key]),
					components: this.documents[key].components,
					setup: {
						single: this.documents[key].single,
						type: this.documents[key].type,
						order: this.documents[key].order,
						attrNameDialog: this.documents[key].nameDialog,
						activeYear: true
					},
					headers: this.documents[key].headers,
					dialogSetup: this.documents[key].dialogSetup
				};
			});
			return tabs;
		}
	},
	created() {
		if (this.availableTabs.length > 0) {
			this.tab = this.availableTabs[0].key;
		}
	}
};
</script>
