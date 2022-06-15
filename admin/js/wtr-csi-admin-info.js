jQuery(document).ready(function () {
    jQuery("#admin-csi-info-publish-button").click(function () {
        if (confirm('Are you sure you want to publish this case study?')) {
            submit_post_csi('publish');
        }
    });

    jQuery("#admin-csi-info-depublish-button").click(function () {
        if (confirm('Are you sure you want to depublish this case study?')) {
            submit_post_csi('depublish');
        }
    });

    jQuery("#admin-csi-info-delete-button").click(function () {
        if (confirm('Are you sure you want to delete this case study?')) {
            submit_post_csi('delete');
        }
    });
});

function submit_post_csi(actionName) {
    jQuery.ajax({
        method: "POST",
        url: wtr_csi_admin_info.url,
        data: {
            wtr_csi_admin_info_nonce: wtr_csi_admin_info.nonce,
            button_action: actionName,
            post_sub_id: get_url_param('sub_id'),
            redirect_url: wtr_csi_admin_info.redirect_url,
            admin_csi_email: jQuery('#admin-csi-email').data('admin-csi-email'),
            admin_csi_status: jQuery('#admin-csi-status').data('admin-csi-status')
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', wtr_csi_admin_info.nonce);
        }
    })
    .done(function (data) {
        let parsedData = JSON.parse(data);

        if (parsedData.action == 'reload') {
            location.reload();
        }

        if (parsedData.action == 'redirect') {
            window.location.href = parsedData.redirect_url;
        }
    });
}

function get_url_param(param_name) {
    let query_string = window.location.search;
    let url_params = new URLSearchParams(query_string);
    let result = url_params.get(param_name);

    return result;
}