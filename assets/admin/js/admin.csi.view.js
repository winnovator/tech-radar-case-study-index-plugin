jQuery(document).ready( function() {
    jQuery('#nfFormTable').DataTable( {
        ajax: {
            url: admin_csi_ajax_obj.url + '?action=get_csi_datatables_subdata',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },        
            { data: 'project_name' },
            { data: 'sbi' },
            { data: 'project_owner' },
            { data: 'status' },
            { data: 'published' },
            { data: 'link' }
        ],
        'lengthMenu': [ 5, 10, 25 ],
        'pageLength': 25
    });
} );