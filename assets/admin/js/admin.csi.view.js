jQuery(document).ready( function() {
    jQuery('#nfFormTable').DataTable( {
        ajax: {
            method: 'GET',
            url: admin_csi_ajax_obj.url,
            data: { admin_csi_security_nonce: admin_csi_ajax_obj.nonce },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', admin_csi_ajax_obj.nonce);
            },
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