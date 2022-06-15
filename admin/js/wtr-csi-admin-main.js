/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * $(function() {
 *
 * });
 *
 * When the window is loaded:
 *
 * $( window ).load(function() {
 *
 * });
 *
 * ...and/or other possibilities.
 *
 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 */

jQuery(document).ready(function () {
	jQuery('#wtr-csi-admin-main-form-table').DataTable({
		ajax: {
			method: 'GET',
			url: wtr_csi_admin_main.url,
			data: { wtr_csi_admin_main_nonce: wtr_csi_admin_main.nonce },
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', wtr_csi_admin_main.nonce);
			},
			dataSrc: ''
		},
		columnDefs: [
			{title: 'ID', targets: 0},
			{title: 'Projectnaam', targets: 1},
			{title: 'Projecteigenaar', targets: 2},
			{title: 'Status', targets: 3},
			{title: 'Gepubliceerd', targets: 4},
			{title: 'Link', targets: 5},
		],
		columns: [
            { data: 'id' },        
            { data: 'project_name' },
            { data: 'project_owner' },
            { data: 'status' },
            { data: 'published' },
            { data: 'link' }
        ],
		lengthMenu: [5, 10, 25, 50],
		pageLength: 25,
		language: {
			url: wtr_csi_admin_datatables_dutch_lang.url
		}
	});
});