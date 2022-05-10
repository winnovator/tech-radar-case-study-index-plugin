jQuery(document).ready(function () {
    jQuery("#admin-csi-info-publish-button").click(function () {
        if (confirm('Are you sure you want to publish this case study?')) {
            postAdminCsiInfo('publish');
        }
    });

    jQuery("#admin-csi-info-depublish-button").click(function () {
        if (confirm('Are you sure you want to depublish this case study?')) {
            postAdminCsiInfo('depublish');
        }
    });

    jQuery("#admin-csi-info-delete-button").click(function () {
        if (confirm('Are you sure you want to delete this case study?')) {
            postAdminCsiInfo('delete');
        }
    });
    
    jQuery('#csi-admin-info-img').on('error', function () {
        jQuery(this).attr('src', admin_csi_info_tech_radar_logo_image.url);
    });
});

function postAdminCsiInfo(actionName) {
    jQuery.ajax({
        method: "POST",
        url: admin_csi_info_ajax_obj.url,
        data: {
            admin_csi_info_security_nonce: admin_csi_info_ajax_obj.nonce,
            button_action: actionName,
            post_sub_id: getUrlParam('sub_id'),
            redirect_url: admin_csi_info_ajax_obj.redirect_url,
            admin_csi_email: jQuery('#admin-csi-email').data('admin-csi-email'),
            admin_csi_status: jQuery('#admin-csi-status').data('admin-csi-status')
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', admin_csi_info_ajax_obj.nonce);
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

function getUrlParam(paramName) {
    let queryString = window.location.search;
    let urlParams = new URLSearchParams(queryString);
    let result = urlParams.get(paramName);

    return result;
}