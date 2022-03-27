jQuery(document).ready( function() {
    jQuery('#nfFormTable').DataTable({"lengthMenu": [ 5, 10, 25, 50]});

    setTimeout(removeCsiSuccessMessage, 5000);
} );

function removeCsiSuccessMessage() {
    jQuery("#csi-success-message").fadeOut( "slow", function() {
        jQuery(this).remove();
    });
}