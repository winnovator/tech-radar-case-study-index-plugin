/**
 * All of the code for your public-facing JavaScript source
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

//Causes no conflict with $ symbol
jQuery.noConflict();

//Case study index functionalities
var current_page_count = 1;

jQuery(document).ready(function () {
    load_csi();
    refresh_data(180000);
});

function refresh_data(ms_time) {
    setInterval(function () {
        get_csi_data(wtr_csi_public_ajax.url, wtr_csi_public_ajax.nonce).done(function (data) {
            remove_storage(sessionStorage, 'wtr-csi-public-default-data');
            save_storage(sessionStorage, 'wtr-csi-public-default-data', data);
            console.info('Data refresh successful');
        });
    }, ms_time);
}

async function load_csi() {
    remove_storage(sessionStorage, 'wtr-csi-public-filtered-data');

    if (!check_if_storage_key_exists(sessionStorage, 'wtr-csi-public-sbi-list')) {
        let sbi_data = await get_all_sbi_data(wtr_csi_public_ajax_all_sbi.url, wtr_csi_public_ajax_all_sbi.nonce)
        remove_storage(sessionStorage, 'wtr-csi-public-sbi-list');
        save_storage(sessionStorage, 'wtr-csi-public-sbi-list', sbi_data);
    }

    if (!check_if_storage_key_exists(sessionStorage, 'wtr-csi-public-default-data')) {
        let csi_data = await get_csi_data(wtr_csi_public_ajax.url, wtr_csi_public_ajax.nonce);
        remove_storage(sessionStorage, 'wtr-csi-public-default-data');
        save_storage(sessionStorage, 'wtr-csi-public-default-data', csi_data);
    }

    let json_default_data = get_storage(sessionStorage, 'wtr-csi-public-default-data');
    let total_page_count = divide_arr(json_default_data).length;
    let current_page = get_current_page(json_default_data, current_page_count - 1);

    render_side_panel(json_default_data);
    render_sbi_tree();
    render_output(current_page);
    set_paginate_buttons(total_page_count);
    init_mobile_responsive();

    jQuery('#wtr-csi-public-previous-page').click(function () {
        prev_page();
        let filtered_data = get_storage(sessionStorage, 'wtr-csi-public-filtered-data') === null ?
            get_storage(sessionStorage, 'wtr-csi-public-default-data') : get_storage(sessionStorage, 'wtr-csi-public-filtered-data');
        let total_page_count = divide_arr(filtered_data).length;
        let current_page = get_current_page(filtered_data, current_page_count - 1);
        render_output(current_page);
        set_paginate_buttons(total_page_count);
        window.scrollTo(0, document.body.scrollHeight);
    });

    jQuery('#wtr-csi-public-next-page').click(function () {
        let filtered_data = get_storage(sessionStorage, 'wtr-csi-public-filtered-data') === null ?
            get_storage(sessionStorage, 'wtr-csi-public-default-data') : get_storage(sessionStorage, 'wtr-csi-public-filtered-data');
        let total_page_count = divide_arr(filtered_data).length;
        next_page(total_page_count);
        let current_page = get_current_page(filtered_data, current_page_count - 1);
        render_output(current_page);
        set_paginate_buttons(total_page_count);
        window.scrollTo(0, document.body.scrollHeight);
    });

    jQuery('#wtr-csi-public-submit').click(function () {
        current_page_count = 1;
        let filtered_data = filter(get_all_checked_input(), json_default_data);
        save_storage(sessionStorage, 'wtr-csi-public-filtered-data', filtered_data);
        let json_filtered_data = get_storage(sessionStorage, 'wtr-csi-public-filtered-data');
        let total_page_count = divide_arr(json_filtered_data).length;
        let current_page = get_current_page(json_filtered_data, current_page_count - 1);
        render_output(current_page);
        set_paginate_buttons(total_page_count);
    });
}

//Start API call functions
function get_csi_data(url, nonce, bool = true) {
    return jQuery.ajax({
        dataType: 'json',
        method: 'GET',
        url: url,
        data: { public_csi_security_nonce: nonce },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', nonce);
        },
        async: bool
    });
}

function get_all_sbi_data(url, nonce, bool = true) {
    return jQuery.ajax({
        dataType: 'json',
        method: 'GET',
        url: url,
        data: { public_csi_security_nonce: nonce },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', nonce);
        },
        async: bool
    });
}

//Filter functions
function get_all_checked_input() {
    let search_arr = [];

    jQuery('input:checked').each(function () {
        search_arr.push(jQuery(this).val());
    });

    return search_arr;
}

function filter(needle, arr) {
    let result_arr = [];
    let haystack = get_haystack(arr);

    if (needle.length > 0) {
        haystack.forEach((element, index) => {
            if (needle.every(i => element.includes(i))) {
                result_arr.push(arr[index]);
            }
        });
        
        return result_arr;
    }
    else {
        return arr
    }
}

function get_haystack(arr) {
    result_arr = [];

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            main_arr = [element.minor, element.sbi];
            result_arr.push(main_arr.concat(element.value_chain, element.tech_trends, element.sdg));
        });
    }

    return result_arr;
}

function get_sbi_code_title(code) {
    let all_sbi_data = get_storage(sessionStorage, 'wtr-csi-public-sbi-list');
    let result = all_sbi_data.find(element => code == element.id);

    if (result) {
        return result.title;
    }

    return 'Onbekend'
}

function get_available_sbi_codes() {
    let csi_data = get_storage(sessionStorage, 'wtr-csi-public-default-data');
    if (!csi_data) { return false; }
    let all_available_sbi = csi_data.map(element => element.sbi);
    let all_sbi_data = get_storage(sessionStorage, 'wtr-csi-public-sbi-list');
    return all_sbi_data.filter(element => all_available_sbi.includes(element.id));
}

//Pagination functions
function prev_page() {
    if (current_page_count > 1) {
        current_page_count--;
    }
}

function next_page(max_page_count) {
    if (current_page_count < max_page_count) {
        current_page_count++;
    }
}

function divide_arr(arr) {
    let cases_per_page = 10;
    let result_arr = [];

    if (arr !== null && arr.length > 0) {
        for (let i = 0; i < arr.length; i += cases_per_page) {
            result_arr.push(arr.slice(i, i + cases_per_page));
        }

        return result_arr;
    }
    else {
        return result_arr;
    }
}

function get_current_page(arr, page_index) {
    if (arr !== null && arr.length > 0 && divide_arr(arr)[page_index] !== undefined) {
        return divide_arr(arr)[page_index];
    }
    else {
        return arr;
    }
}

function convert_to_single_type_arr(arr, prop) {
    let parent_arr = [];

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            if (Array.isArray(element[prop])) {
                element[prop].forEach(sub_element => {
                    parent_arr.push(sub_element)
                });
            }
            else {
                parent_arr.push(element[prop]);
            }
        });
    }

    return parent_arr;
}

function set_paginate_buttons(total_page_count) {
    if (total_page_count > 0) {
        if (total_page_count == 1) {
            jQuery('#wtr-csi-public-previous-page').prop('disabled', true);
            jQuery('#wtr-csi-public-next-page').prop('disabled', true);
            jQuery('#wtr-csi-public-previous-page').show();
            jQuery('#wtr-csi-public-next-page').show();
            jQuery('#wtr-csi-public-current-page').text(current_page_count + ' - ' + total_page_count);
            return;
        }

        if (current_page_count == 1) {
            jQuery('#wtr-csi-public-previous-page').prop('disabled', true);
            jQuery('#wtr-csi-public-next-page').prop('disabled', false);
            jQuery('#wtr-csi-public-current-page').text(current_page_count + ' - ' + total_page_count);
            return;
        }

        if (current_page_count == total_page_count) {
            jQuery('#wtr-csi-public-next-page').prop('disabled', true);
            jQuery('#wtr-csi-public-previous-page').prop('disabled', false);
            jQuery('#wtr-csi-public-current-page').text(current_page_count + ' - ' + total_page_count);
            return;
        }

        jQuery('#wtr-csi-public-previous-page').prop('disabled', false);
        jQuery('#wtr-csi-public-next-page').prop('disabled', false);
        jQuery('#wtr-csi-public-previous-page').show();
        jQuery('#wtr-csi-public-next-page').show();
        jQuery('#wtr-csi-public-current-page').text(current_page_count + ' - ' + total_page_count);
        return;
    }

    jQuery('#wtr-csi-public-current-page').text('Geen pagina\'s.');
    jQuery('#wtr-csi-public-previous-page').hide();
    jQuery('#wtr-csi-public-next-page').hide();
}

//Generic functions
function array_unique(arr) {
    return arr.filter(function (item, pos) {
        return arr.indexOf(item) == pos;
    });
}

function remove_empty_elements(arr) {
    return arr.filter(item => item);
}

function output_table_row(title, element, type) {
    let output = '';

    if (element && element.length > 0) {
        switch (type) {
            case 'text':
                output += '<tr class="wtr-csi-public-item-tr"><th class="wtr-csi-public-item-th">' + title + '</th><td class="wtr-csi-public-item-td">' + element + '</td></tr>';
                break;
            case 'array':
                output += '<tr class="wtr-csi-public-item-tr"><th class="wtr-csi-public-item-th">' + title + '</th><td class="wtr-csi-public-item-td">' + element.join(', ') + '</td></tr>';
                break;
        }
    }

    return output;
}

//Render functions
function render_output(arr) {
    let content_selector = jQuery('#wtr-csi-public-content');
    let html_string = '';

    if (arr.length > 0) {
        arr.forEach(element => {
            html_string += '<div class="wtr-csi-public-element-container wtr-csi-public-element-item">';

            if (element.id && element.project_name) {
                html_string += '<h1><button class="wtr-csi-public-info-modal-open" data-wtr-csi-public-sub-id="' + element.id + '">' + element.project_name + '</button></h1>';
            }

            html_string += '<div class="wtr-csi-public-item-content">';
            html_string += '<table class="wtr-csi-public-item-table">';

            html_string += output_table_row('Windesheim Minor:', element.minor, 'text');
            html_string += output_table_row('Value Chain (Michael Porter):', element.value_chain, 'array');
            html_string += output_table_row('SBI-code:', element.sbi + ' - ' + get_sbi_code_title(element.sbi), 'text');
            html_string += output_table_row('Trends:', element.tech_trends, 'array');
            html_string += output_table_row('SDG\'s', element.sdg, 'array');

            html_string += '</table>';

            if (element.case_study_image) {
                html_string += '<img class="wtr-csi-public-item-img" src="' + Object.values(element.case_study_image).join('') + '">';
            }
            else {
                html_string += '<img class="wtr-csi-public-item-img" src="' + wtr_csi_public_tech_radar_logo_image.url + '">';
            }

            html_string += '</div>';
            html_string += '</div>';
        });
    }
    else {
        html_string += '<p>Geen resultaten gevonden.</p>';
    }

    content_selector.html(jQuery.parseHTML(html_string));

    //Reset modal event handlers
    render_info_page();
}

function filter_side_panel_item(title, arr, label_for, input_name) {
    let output = '';

    if (arr.length > 0) {
        output += '<div>';
        output += '<h1>' + title + '</h1>';
        output += '<ul class="wtr-csi-public-side-panel-ul">';
    
        arr.forEach(element => {
            output += '<li><label for="' + label_for + '"><input class="wtr-csi-public-side-panel-checkbox" type="checkbox" name="' + input_name + '" value="' + element + '"/>' + element + '</label></li>';
        });
    
        output += '</ul>';
        output += '</div>';
    }

    return output;
}

function render_side_panel(arr) {
    let content_selector = jQuery('#wtr-csi-public-side-panel');
    let unique_tech_trends_arr = remove_empty_elements(array_unique(convert_to_single_type_arr(arr, 'tech_trends'))).sort();
    let unique_value_chain_arr = remove_empty_elements(array_unique(convert_to_single_type_arr(arr, 'value_chain'))).sort();
    let unique_minor_arr = remove_empty_elements(array_unique(convert_to_single_type_arr(arr, 'minor'))).sort();
    let unique_sdg_arr = remove_empty_elements(array_unique(convert_to_single_type_arr(arr, 'sdg'))).sort(function (a, b) { return a.split('.')[0] - b.split('.')[0]; });
    let all_sbi_data = get_storage(sessionStorage, 'wtr-csi-public-sbi-list');
    let html_string = '';

    if (arr.length > 0) {
        html_string += filter_side_panel_item('Trends', unique_tech_trends_arr, 'tech-trends', 'tech_trends');
        html_string += filter_side_panel_item('Value Chain (Michael Porter)', unique_value_chain_arr, 'value-chain', 'value_chain');

        if (all_sbi_data.length > 0) {
            html_string += '<h1>Sector (SBI-code)</h1>';
            html_string += '<div id="wtr-csi-public-sbi-tree-view-container"></div>';
        }

        html_string += filter_side_panel_item('Windesheim Minor', unique_minor_arr, 'minor', 'minor');
        html_string += filter_side_panel_item('SDG\'s', unique_sdg_arr, 'sdg', 'sdg');

        html_string += '<div id="wtr-csi-public-submit-container"><button id="wtr-csi-public-submit">Verzenden</button></div>';
    }
    else {
        html_string += '<p>Geen filters beschikbaar.</p>';
    }

    content_selector.html(jQuery.parseHTML(html_string));
}

function render_sbi_tree() {
    let html_string = '';
    let available_sbi_codes = get_available_sbi_codes();
    if (!available_sbi_codes) { return false; }
    let all_sbi_data = get_storage(sessionStorage, 'wtr-csi-public-sbi-list');
    let id_one_char = all_sbi_data.filter(element => element.id.length == 1);
    let id_two_char = all_sbi_data.filter(element => element.id.length == 2);
    let id_three_char = all_sbi_data.filter(element => element.id.length == 3);
    let id_four_char = all_sbi_data.filter(element => element.id.length == 4);
    let id_five_char = all_sbi_data.filter(element => element.id.length == 5);

    //First rows
    html_string += '<ul id="wtr-csi-public-sbi-ul">';
    for (let element of id_one_char) {
        html_string += '<li class="wtr-csi-public-sbi-li">' + '<span class="wtr-csi-public-sbi-caret">' + element.id + ' - ' + element.title + '<b class="wtr-csi-public-sbi-element-count"></b></span><ul class="wtr-csi-public-sbi-nested" data-wtr-csi-public-sbi-parent-id="' + element.id + '"></ul></li>';
    }
    html_string += '</ul>';
    jQuery('#wtr-csi-public-sbi-tree-view-container').append(jQuery.parseHTML(html_string));

    //Second rows
    for (let element of id_two_char) {
        html_string = '';
        html_string += '<li class="wtr-csi-public-sbi-li"><span class="wtr-csi-public-sbi-caret"></span><input class="wtr-csi-public-side-panel-checkbox" type="checkbox" name="sbi" value="' + element.id + '"/>' + element.id + ' - ' + element.title + '<ul class="wtr-csi-public-sbi-nested" data-wtr-csi-public-sbi-parent-id="' + element.id + '"></ul></i>';
        jQuery('.wtr-csi-public-sbi-nested[data-wtr-csi-public-sbi-parent-id="' + element.parent_id + '"]').append(jQuery.parseHTML(html_string));
    }

    //Third rows
    for (let element of id_three_char) {
        html_string = '';
        html_string += '<li class="wtr-csi-public-sbi-li"><span class="wtr-csi-public-sbi-caret"></span><input class="wtr-csi-public-side-panel-checkbox" type="checkbox" name="sbi" value="' + element.id + '"/>' + element.id + ' - ' + element.title + '<ul class="wtr-csi-public-sbi-nested" data-wtr-csi-public-sbi-parent-id="' + element.id + '"></ul></i>';
        jQuery('.wtr-csi-public-sbi-nested[data-wtr-csi-public-sbi-parent-id="' + element.parent_id + '"]').append(jQuery.parseHTML(html_string));
    }

    //Fourth rows
    for (let element of id_four_char) {
        html_string = '';
        html_string += '<li class="wtr-csi-public-sbi-li"><span class="wtr-csi-public-sbi-caret"></span><input class="wtr-csi-public-side-panel-checkbox" type="checkbox" name="sbi" value="' + element.id + '"/>' + element.id + ' - ' + element.title + '<ul class="wtr-csi-public-sbi-nested" data-wtr-csi-public-sbi-parent-id="' + element.id + '"></ul></i>';
        jQuery('.wtr-csi-public-sbi-nested[data-wtr-csi-public-sbi-parent-id="' + element.parent_id + '"]').append(jQuery.parseHTML(html_string));
    }

    //Fifth rows
    for (let element of id_five_char) {
        html_string = '';
        html_string += '<li class="wtr-csi-public-sbi-li"></span><input class="wtr-csi-public-side-panel-checkbox" type="checkbox" name="sbi" value="' + element.id + '"/>' + element.id + ' - ' + element.title + '</i>';
        jQuery('.wtr-csi-public-sbi-nested[data-wtr-csi-public-sbi-parent-id="' + element.parent_id + '"]').append(jQuery.parseHTML(html_string));
    }

    //Styling sanitization
    jQuery('input[name="sbi"]').attr('disabled', true);
    
    for (let element of available_sbi_codes) {
        jQuery('input[name="sbi"][value="' + element.id + '"]').removeAttr('disabled');
    }

    jQuery('.wtr-csi-public-sbi-li').each(function() {
        let elementCount = jQuery(this).find('input[name="sbi"]').not(':disabled').length;

        if (elementCount > 0) {
            jQuery(this).find('.wtr-csi-public-sbi-element-count').text(' - ' + elementCount + ' filters');
            jQuery(this).find('.wtr-csi-public-sbi-element-count').css('font-size', '15px');
        }

        if (elementCount == 0) {
            jQuery(this).remove();
            jQuery('.wtr-csi-public-sbi-nested:empty').parent('.wtr-csi-public-sbi-li').find('span').remove();
            jQuery('.wtr-csi-public-sbi-nested:empty').remove();
        }
    });

    //Init events
    init_sbi_tree_events();
}

function init_sbi_tree_events() {
    let toggler = jQuery('.wtr-csi-public-sbi-caret');

    toggler.each(function () {
        jQuery(this).on('click', function () {
            this.parentElement.querySelector(".wtr-csi-public-sbi-nested").classList.toggle("wtr-csi-public-sbi-tree-active");
            this.classList.toggle("wtr-csi-public-sbi-tree-caret-down");
        });
    });
}

function render_modal(header, body, footer) {
    //Adding html elements
    let content_selector = jQuery('#wtr-csi-public-info-modal-container');
    let html_string = '';

    html_string += '<div id="wtr-csi-public-info-modal" class="wtr-csi-public-info-modal">';
    html_string += '<div class="wtr-csi-public-info-modal-content">';
    html_string += '<div class="wtr-csi-public-info-modal-header">';
    html_string += '<span id="wtr-csi-public-info-modal-close">&times;</span>';
    html_string += '<h1 id="wtr-csi-public-info-modal-header-h1">' + header + '</h1>';
    html_string += '</div>';
    html_string += '<div class="wtr-csi-public-info-modal-body">';
    html_string += body;
    html_string += '</div>';
    html_string += '<div class="wtr-csi-public-info-modal-footer">';
    html_string += '<p id="wtr-csi-public-info-modal-footer-p">' + footer + '</p>';
    html_string += '</div>';
    html_string += '</div>';
    html_string += '</div>';

    content_selector.html(jQuery.parseHTML(html_string));
}

function render_info_page() {
    jQuery('.wtr-csi-public-info-modal-open').click(function (event) {
        event.preventDefault();
        let sub_id = event.currentTarget.getAttribute("data-wtr-csi-public-sub-id");
        let span_open = jQuery('.wtr-csi-public-info-modal-open');
        
        span_open.attr('disabled', 'disabled');

        jQuery.when(get_csi_data(wtr_csi_public_ajax_info.url + sub_id, wtr_csi_public_ajax_info.nonce)).done(function (data) {
            render_modal('Case studie informatie', render_info_modal_body(data), 'Copyright © 2022 Windesheim Technology Radar');
            init_modal_events();
        });
    });
}

function info_model_item(title, element, type) {
    let output = '';
    
    if (element) {
        output += '<tr><td><h3 class="wtr-csi-public-info-modal-item-title">' +  title + '</h3></td></tr>';

        switch (type) {
            case 'text':
                output += '<tr><td class="wtr-csi-public-info-modal-item-contents">' + element + '</td></tr>';
                break;
            case 'array':
                output += '<tr><td class="wtr-csi-public-info-modal-item-contents">' + element.join(', ') + '</td></tr>';
                break;
            case 'link':
                output += '<tr><td class="wtr-csi-public-info-modal-item-contents"><a href="' + element + '" target="_blank">' + element + '</a></td></tr>';
        }
    }

    return output;
}

function render_info_modal_body(data) {
    let html_string = '';

    if (data) {
        html_string += '<div id="wtr-csi-public-info-modal-body-container">';
        html_string += '<div id="wtr-csi-public-info-modal-body-content">';

        if (data.project_name || data.project_owner || data.project_owner_email) {
            html_string += '<h2>Contactinformatie</h2>';

            html_string += '<table>';
            html_string += info_model_item('Projectnaam', data.project_name, 'text');
            html_string += info_model_item('Projecteigenaar', data.project_owner, 'text');
            html_string += info_model_item('Email', data.project_owner_email, 'text');
            html_string += '</table>';
        }

        if (data.project_stage || data.minor || data.minor || data.tech_innovations || 
            data.tech_providers || data.tech_trends || data.value_chain) {
                html_string += '<h2>Details</h2>';

                html_string += '<table>';
                html_string += info_model_item('Projectfase', data.project_stage, 'text');
                html_string += info_model_item('Windesheim Minor', data.minor, 'text');
                html_string += info_model_item('SBI-code', data.sbi + ' - ' + get_sbi_code_title(data.sbi), 'text');
                html_string += info_model_item('Technologie innovaties', data.tech_innovations, 'text');
                html_string += info_model_item('Technologieleveranciers', data.tech_providers, 'text');
                html_string += info_model_item('Trends', data.tech_trends, 'array');
                html_string += info_model_item('Value Chain (Michael Porter)', data.value_chain, 'array');
                html_string += '</table>';
        }


        if (data.sdg.length > 0 || data.sdg_impact_positive || data.sdg_impact_negative) {
            html_string += '<h2>SDG\'s</h2>';

            html_string += '<table>';
            html_string += info_model_item('Categorieën', data.sdg, 'array');
            html_string += info_model_item('SDG impact (positief)', data.sdg_impact_positive, 'text');
            html_string += info_model_item('SDG impact (negatief)', data.sdg_impact_negative, 'text');
            html_string += '</table>';
        }

        if (data.project_context || data.project_problem || data.project_goal) {
            html_string += '<h2>Projectinformatie</h2>';

            html_string += '<table>';
            html_string += info_model_item('Achtergrond', data.project_context, 'text');
            html_string += info_model_item('Probleemstelling', data.project_problem, 'text');
            html_string += info_model_item('Doelstelling', data.project_goal, 'text');
            html_string += '</table>';
        }

        if (data.case_study_link || data.case_study_video_link) {
            html_string += '<h2>Links</h2>';

            html_string += '<table>';
            html_string += info_model_item('Case studie link', data.case_study_link, 'link');
            html_string += info_model_item('Videolink', data.case_study_video_link, 'link');
            html_string += '</table>';
        }
        
        html_string += '</div>';

        if (data.case_study_image) {
            html_string += '<div id="wtr-csi-public-info-modal-body-image">';
            html_string += '<img class="wtr-csi-public-item-img" src="' + Object.values(data.case_study_image).join('') + '">';
            html_string += '</div>';
        }
        else {
            html_string += '<div id="wtr-csi-public-info-modal-body-image">';
            html_string += '<img class="wtr-csi-public-item-img" src="' + wtr_csi_public_tech_radar_logo_image.url + '">';
            html_string += '</div>';
        }
    
        html_string += '<div id="wtr-csi-public-info-modal-body-actions">';

        // Action buttons for future development
        // html_string += '<p>actions</p>';

        html_string += '</div>';
    
        html_string += '</div>';
    }

    return html_string;
}

function init_modal_events() {
    let modal = jQuery("#wtr-csi-public-info-modal");
    let span = jQuery("#wtr-csi-public-info-modal-close");
    let span_open = jQuery('.wtr-csi-public-info-modal-open');
    let body = jQuery("body");

    modal.css('display', 'block');
    body.css('overflow', 'hidden');

    span.click(function () {
        span_open.removeAttr('disabled');
        modal.css('display', 'none');
        body.css('overflow', 'auto');
    });
}

//JSON storage funcions
function save_storage(type, name, value) {
    let json_string = JSON.stringify(value);
    type.setItem(name, json_string);
}

function get_storage(type, name) {
    let json_string = type.getItem(name);
    return JSON.parse(json_string);
}

function remove_storage(type, name) {
    type.removeItem(name);
}

function check_if_storage_key_exists(type, name) {
    if (type.getItem(name) === null) {
        return false;
    }
    return true;
}

//Mobile responsive functionalities
function init_mobile_responsive() {
    let content = jQuery('#wtr-csi-public-content');
    let side_panel = jQuery('#wtr-csi-public-side-panel');
    let pagination = jQuery('#wtr-csi-public-pagination');
    let filter_button = jQuery('#wtr-csi-public-filter-button');
    let submit_button = jQuery('#wtr-csi-public-submit');

    filter_button.click(function () {
        if (filter_button.attr('data-toggle') == 'false') {
            filter_button.attr('data-toggle', 'true');
            side_panel.show();
            content.hide();
            pagination.hide();
        }
        else {
            filter_button.attr('data-toggle', 'false');
            side_panel.hide();
            content.show();
            pagination.show();
        }
    });

    submit_button.click(function () {
        if (screen.width <= 900) {
            filter_button.attr('data-toggle', 'false');
            side_panel.hide();
            content.show();
            pagination.show();
        }
    });
}