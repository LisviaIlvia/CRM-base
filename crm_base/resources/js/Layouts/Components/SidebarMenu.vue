<template>
	<v-navigation-drawer
		v-model="drawer"
		class="bg-blue-grey-lighten-5"
		permanent
		:rail="rail"
		elevation="8"
	>
		<v-list density="compact" nav>
			<v-list-item 
				v-for="(item, index) in filteredItems" 
				:key="index"
				:title="item.title"
				class="dim-icon"
				:prepend-icon="item.icon" 
				@click="visitLink(item.link)"
			>
			</v-list-item>
			<v-list-group v-if="filteredLogisticaItems.length > 0" value="Logistica">
				<template v-slot:activator="{ props }">
					<v-list-item
					  v-bind="props"
					  class="dim-icon"
					  prepend-icon="fa solid fa-truck-front"
					  title="Logistica"
					></v-list-item>
				</template>
				<v-list-item 
					v-for="(item, index) in filteredLogisticaItems" 
					:key="index" 
					:title="item.title" 
					 class="dim-icon"
					:prepend-icon="item.icon" 
					@click="visitLink(item.link)"
				>
				</v-list-item>
			</v-list-group>
			<v-list-group v-if="filteredAcquistiItems.length > 0" value="Acquisti">
				<template v-slot:activator="{ props }">
					<v-list-item
					  v-bind="props"
					  class="dim-icon"
					  prepend-icon="custom:acquisti"
					  title="Acquisti"
					></v-list-item>
				</template>
				<v-list-item 
					v-for="(item, index) in filteredAcquistiItems" 
					:key="index" 
					:title="item.title" 
					 class="dim-icon"
					:prepend-icon="item.icon" 
					@click="visitLink(item.link)"
				>
				</v-list-item>
			</v-list-group>
			<v-list-group v-if="filteredVenditeItems.length > 0" value="Vendite">
				<template v-slot:activator="{ props }">
					<v-list-item
					  v-bind="props"
					  class="dim-icon"
					  prepend-icon="custom:vendite"
					  title="Vendite"
					></v-list-item>
				</template>
				<v-list-item 
					v-for="(item, index) in filteredVenditeItems" 
					:key="index" 
					:title="item.title" 
					class="dim-icon"
					:prepend-icon="item.icon" 
					@click="visitLink(item.link)"
				>
				</v-list-item>
			</v-list-group>
			<v-list-group v-if="filteredProductsItems.length > 0" value="Prodotti">
				<template v-slot:activator="{ props }">
					<v-list-item
						v-bind="props"
						class="dim-icon"
						prepend-icon="fa-solid fa-box"
						title="Prodotti"
					></v-list-item>
				</template>
				<v-list-item 
					v-for="(item, index) in filteredProductsItems" 
					:key="index" 
					:title="item.title" 
					 class="dim-icon"
					:prepend-icon="item.icon" 
					@click="visitLink(item.link)"
				>
				</v-list-item>
			</v-list-group>
			<v-list-group v-if="filteredSettingsItems.length > 0" value="Impostazioni">
				<template v-slot:activator="{ props }">
					<v-list-item
					  v-bind="props"
					  class="dim-icon"
					  prepend-icon="fa-solid fa-gear"
					  title="Impostazioni"
					></v-list-item>
				</template>
				<v-list-item 
					v-for="(item, index) in filteredSettingsItems" 
					:key="index" 
					:title="item.title" 
					 class="dim-icon"
					:prepend-icon="item.icon" 
					@click="visitLink(item.link)"
				>
				</v-list-item>
			</v-list-group>
		 </v-list>
	</v-navigation-drawer>
</template>

<style>
	.custom-badge {
		line-height: 1.3rem;
		vertical-align: text-top;
	}
	.custom-badge .v-badge__wrapper {
		margin-top: -4px;
	}
	.dim-icon i {
		font-size: 18px;
	}
	.dim-icon .v-icon svg path {
		fill: #000;
	}
	
</style>

<script>

export default {
	name: 'SidebarMenu',
	props: {
		rail: {
			type: Boolean,
			default: false
		},
		role: {
			type: String,
			required: true
		},
		permissions: {
			type: Array,
			required: true
		}
	},
	data() {
		return {
			drawer: true,
			items: [
				{
					title: 'Dashboard',
					icon: 'fa-solid fa-sliders',
					link: '/dashboard'
				},
				{
					title: 'Azienda',
					icon: 'fa-solid fa-building',
					link: '/azienda'
				},
				{
					title: 'Clienti',
					icon: 'fa-solid fa-handshake',
					link: '/clienti'
				},
				{
					title: 'Fornitori',
					icon: 'fa-solid fa-industry',
					link: '/fornitori'
				}
			],
			logistica: [
				{
					title: 'Magazzino',
					icon: 'fa-solid fa-warehouse',
					link: '/magazzino'
				},
				{
					title: 'DDT Entrata',
					icon: 'custom:ddt-entrata',
					link: '/ddt-entrata'
				},
				{
					title: 'DDT Uscita',
					icon: 'custom:ddt-uscita',
					link: '/ddt-uscita'
				}
			],
			acquisti: [
				{
					title: 'Ordini Acquisto',
					icon: 'custom:ordini-acquisto',
					link: '/ordini-acquisto'
				},
				{
					title: 'Fatture Acquisto',
					icon: 'custom:fatture-acquisto',
					link: '/fatture-acquisto'
				},
				{
					title: 'Note di Credito',
					icon: 'custom:note-credito-passive',
					link: '/note-credito-passive'
				}
			],
			vendite: [
				{
					title: 'Ordini Vendita',
					icon: 'custom:ordini-vendita',
					link: '/ordini-vendita'
				},
				{
					title: 'Fatture Proforma',
					icon: 'custom:fatture-vendita',
					link: '/fatture-proforma'
				},
				{
					title: 'Fatture Vendita',
					icon: 'custom:fatture-vendita',
					link: '/fatture-vendita'
				},
				{
					title: 'Note di Credito',
					icon: 'custom:note-credito-attive',
					link: '/note-credito-attive'
				}
			],
			prodotti: [
				{
					title: 'Merci',
					icon: 'fa-solid fa-dolly',
					link: '/merci'
				},
				{
					title: 'Servizi',
					icon: 'fa-solid fa-cogs',
					link: '/servizi'
				},
				{
					title: 'Categorie',
					icon: 'fa-solid fa-tag',
					link: '/categorie'
				}
			],
			impostazioni: [
				{
					title: 'Utenti',
					icon: 'fa-solid fa-users',
					link: '/utenti'
				},
				{
					title: 'Ruoli',
					icon: 'fa-solid fa-user-shield',
					link: '/ruoli'
				},
				{
					title: 'Aliquote IVA',
					icon: 'fa-solid fa-percent',
					link: '/aliquote-iva'
				},
				{
					title: 'Conti bancari',
					icon: 'fa-solid fa-building-columns',
					link: '/conti-bancari',
				},
				{
					title: 'Metodi di pagamento',
					icon: 'fa-solid fa-cash-register',
					link: '/metodi-pagamento',
				},
				{
					title: 'Spedizioni',
					icon: 'fa-solid fa-truck-fast',
					link: '/spedizioni',
				}
			]
		};
	},
	computed: {
		filteredItems() {
			return this.items.filter(item => {
				if (item.link == '/dashboard') return true;
				const permissionName = item.permission ? `${item.permission}.show` : `${item.link.replace('/', '')}.show`;
				return item.roles ? this.can(permissionName, item.roles) : this.can(permissionName);
			});
		},
		filteredLogisticaItems() {
			return this.logistica.filter(item => {
				const permissionName = item.permission ? `${item.permission}.show` : `${item.link.replace('/', '')}.show`;
				return item.roles ? this.can(permissionName, item.roles) : this.can(permissionName);
			});
		},
		filteredAcquistiItems() {
			return this.acquisti.filter(item => {
				const permissionName = `${item.link.replace('/', '')}.show`;
				return item.roles ? this.can(permissionName, item.roles) : this.can(permissionName);
			});
		},
		filteredVenditeItems() {
			return this.vendite.filter(item => {
				const permissionName = item.permission ? `${item.permission}.show` : `${item.link.replace('/', '')}.show`;
				return item.roles ? this.can(permissionName, item.roles) : this.can(permissionName);
			});
		},
		filteredProductsItems() {
			return this.prodotti.filter(item => {
				const permissionName = item.permission ? `${item.permission}.show` : `${item.link.replace('/', '')}.show`;
				return item.roles ? this.can(permissionName, item.roles) : this.can(permissionName);
			});
		},
		filteredSettingsItems() {
			return this.impostazioni.filter(item => {
				const permissionName = item.permission ? `${item.permission}.show` : `${item.link.replace('/', '')}.show`;
				return item.roles ? this.can(permissionName, item.roles) : this.can(permissionName);
			});
		}
	},
	methods: {
		visitLink(link) {
			this.$inertia.visit(link);
		},
		can(permission, roles = null) {
			const userPermissions = this.permissions;
			let hasPermission = userPermissions.includes(permission);
			if (roles != null && this.role != null) {
				let hasRole = roles.includes(this.role);
				if (!hasRole) return false;
			}
			return hasPermission;
		}
	}
}
</script>