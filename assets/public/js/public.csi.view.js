//Causes no conflict with $ symbol
jQuery.noConflict();

//Case study index functionalities
var currentPageCount = 1;

jQuery(document).ready(function () {
    loadCsi();  
});

async function loadCsi() {
    let csiData = await getCsiData(public_csi_ajax_obj.url, public_csi_ajax_obj.nonce);
    let sbiData = await getAllSbiData(public_csi_ajax_all_sbi_obj.url, public_csi_ajax_all_sbi_obj.nonce)

    removeStorage(sessionStorage, 'public-csi-default-data');
    removeStorage(sessionStorage, 'public-csi-filtered-data');
    saveStorage(sessionStorage, 'public-csi-default-data', csiData);

    removeStorage(sessionStorage, 'public-csi-sbi-list');
    saveStorage(sessionStorage, 'public-csi-sbi-list', sbiData);

    let jsonDefaultData = getStorage(sessionStorage, 'public-csi-default-data');
    let totalPageCount = divideArr(jsonDefaultData).length;
    let currentPage = getCurrentPage(jsonDefaultData, currentPageCount - 1);

    renderSidePanel(jsonDefaultData);
    renderSbiTree();
    renderOutput(currentPage);
    setPaginationButtons(totalPageCount);
    initMobileResponsive();

    jQuery('#csi-previous-page').click(function () {
        prevPage();
        let filteredData = getStorage(sessionStorage, 'public-csi-filtered-data') === null ?
            getStorage(sessionStorage, 'public-csi-default-data') : getStorage(sessionStorage, 'public-csi-filtered-data');
        let totalPageCount = divideArr(filteredData).length;
        let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
        renderOutput(currentPage);
        setPaginationButtons(totalPageCount);
        window.scrollTo(0, document.body.scrollHeight);
    });

    jQuery('#csi-next-page').click(function () {
        let filteredData = getStorage(sessionStorage, 'public-csi-filtered-data') === null ?
            getStorage(sessionStorage, 'public-csi-default-data') : getStorage(sessionStorage, 'public-csi-filtered-data');
        let totalPageCount = divideArr(filteredData).length;
        nextPage(totalPageCount);
        let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
        renderOutput(currentPage);
        setPaginationButtons(totalPageCount);
        window.scrollTo(0, document.body.scrollHeight);
    });

    jQuery('#csi-submit').click(function () {
        currentPageCount = 1;
        let filteredData = filter(getAllCheckedInput(), jsonDefaultData);
        saveStorage(sessionStorage, 'public-csi-filtered-data', filteredData);
        let jsonFilteredData = getStorage(sessionStorage, 'public-csi-filtered-data');
        let totalPageCount = divideArr(jsonFilteredData).length;
        let currentPage = getCurrentPage(jsonFilteredData, currentPageCount - 1);
        renderOutput(currentPage);
        setPaginationButtons(totalPageCount);
    });
}

//Start API call functions
function getCsiData(url, nonce, bool = true) {
    return jQuery.ajax({
        dataType: "json",
        method: "GET",
        url: url,
        data: { public_csi_security_nonce: nonce },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', nonce);
        },
        async: bool
    });
}

function getAllSbiData(url, nonce, bool = true) {
    return jQuery.ajax({
        dataType: "json",
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
function getAllCheckedInput() {
    let searchArr = [];

    jQuery('input:checked').each(function () {
        searchArr.push(jQuery(this).val());
    });

    return searchArr;
}

function filter(needle, arr) {
    let resultsArr = [];
    let haystack = getHaystack(arr);

    if (needle.length > 0) {
        haystack.forEach((element, index) => {
            if (needle.every(i => element.includes(i))) {
                resultsArr.push(arr[index]);
            }
        });

        return resultsArr;
    }
    else {
        return arr
    }
}

function getHaystack(arr) {
    resultArr = [];

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            mainArr = [element.minor, element.project_stage, element.sbi];
            porterArr = element.porter;
            metaArr = element.meta_trends;

            resultArr.push(mainArr.concat(porterArr, metaArr));
        });
    }

    return resultArr;
}

function getSingleSbiByCode(code) {
    let allSbiData = getStorage(sessionStorage, 'public-csi-sbi-list');

    for (let sbiElement of allSbiData) {
        for (let element of sbiElement.Codes)
            if (code === element.Code) {
                return { Code: code, Title: element.Title, Letter: sbiElement.Letter, SectionTitle: sbiElement.Title }
            }
    }

    return { Code: code, Title: 'Onbekend', Letter: 'Overige', SectionTitle: 'Overige' };
}

function getAvailableSbiCodes() {
    let availableSbiData = getStorage(sessionStorage, 'public-csi-default-data');
    let resultArr = [];
    let exist = [];
    
    availableSbiData.forEach(element => {
        if (!exist.includes(element.sbi)) {
            resultArr.push(getSingleSbiByCode(element.sbi));
            exist.push(element.sbi);
        }
    });

    return sortByLetter(resultArr);
}

function getAllSbiByLetter(letter) {
    let availableSbiData = getAvailableSbiCodes();
    let resultArr = [];

    availableSbiData.forEach(element => {
        if (letter == element.Letter) {
            resultArr.push(element);
        }
    });

    return resultArr;
}

function sortByLetter(dataArr) {
    let letterArr = [];
    dataArr.forEach(element => { letterArr.push(element.Letter); });
    let filteredLetterArr = arrayUnique(letterArr);
    let sortedLetterArr = filteredLetterArr.sort();
    let resultArr = [];

    sortedLetterArr.forEach(letterElement => {
        dataArr.forEach(element => { 
            if (letterElement == element.Letter) {
                resultArr.push(element);
            }
        });
    });

    return resultArr;
}

//Pagination functions
function prevPage() {
    if (currentPageCount > 1) {
        currentPageCount--;
    }
}

function nextPage(maxPageCount) {
    if (currentPageCount < maxPageCount) {
        currentPageCount++;
    }
}

function divideArr(arr) {
    let casesPerPage = 10;
    let resultArr = [];

    if (arr !== null && arr.length > 0) {
        for (let i = 0; i < arr.length; i += casesPerPage) {
            resultArr.push(arr.slice(i, i + casesPerPage));
        }

        return resultArr;
    }
    else {
        return resultArr;
    }
}

function getCurrentPage(arr, pageIndex) {
    if (arr !== null && arr.length > 0 && divideArr(arr)[pageIndex] !== undefined) {
        return divideArr(arr)[pageIndex];
    }
    else {
        return arr;
    }
}

function convertToSingleTypeArr(arr, prop) {
    let parentArr = [];

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            if (Array.isArray(element[prop])) {
                element[prop].forEach(subElement => {
                    parentArr.push(subElement)
                });
            }
            else {
                parentArr.push(element[prop]);
            }
        });
    }

    return parentArr
}

function setPaginationButtons(totalPageCount) {
    if (totalPageCount > 0) {
        if (totalPageCount == 1) {
            jQuery('#csi-previous-page').prop('disabled', true);
            jQuery('#csi-next-page').prop('disabled', true);
            jQuery('#csi-previous-page').show();
            jQuery('#csi-next-page').show();
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
            return;
        }
        
        if (currentPageCount == 1) {
            jQuery('#csi-previous-page').prop('disabled', true);
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
            return;
        }

        if (currentPageCount == totalPageCount) {
            jQuery('#csi-next-page').prop('disabled', true);
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
            return;
        }

        jQuery('#csi-previous-page').prop('disabled', false);
        jQuery('#csi-next-page').prop('disabled', false);
        jQuery('#csi-previous-page').show();
        jQuery('#csi-next-page').show();
        jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
        return;
    }

    jQuery('#csi-current-page').text('Geen pagina\'s.');
    jQuery('#csi-previous-page').hide();
    jQuery('#csi-next-page').hide();
}

//Generic functions
function arrayUnique(arr) {
    return arr.filter(function (item, pos) {
        return arr.indexOf(item) == pos;
    });
}

function removeEmptyElements(arr) {
    return arr.filter(item => item);
}

//Render functions
function renderOutput(arr) {
    let contentSelector = jQuery('#csi-content');
    let htmlString = '';

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            let sbiCode = getSingleSbiByCode(element.sbi);

            htmlString += '<div class="csi-element-container csi-element-item">';
            htmlString += '<h1><a class="csi-public-info-modal-open" href="#/" data-sub-id="' + element.id + '">' + element.project_name + '</a></h1>';
            htmlString += '<div class="csi-public-item-content">';
            htmlString += '<table class="csi-public-item-table">';

            if (element.minor != '') {
                htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Minor: </th><td class="csi-item-td">' + element.minor + '</td></tr>';
            }

            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Michael Porter\'s Value Chain: </th><td class="csi-item-td">' + element.porter.join(', ') + '</td></tr>';

            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">SBI-code: </th><td class="csi-item-td">' + sbiCode.Code + ' - ' + sbiCode.Title + '</td></tr>';

            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Meta Trends: </th><td class="csi-item-td">' + (Array.isArray(element.meta_trends) ? element.meta_trends.join(', ') : (element.meta_trends.length > 0 ? element.meta_trends : 'Geen trends')) + '</td></tr>';
            htmlString += '</table>';
            htmlString += '<img class="csi-public-item-img" src=' + element.case_study_image_url + '>';
            htmlString += '</div>';
            htmlString += '</div>';
        });
    }
    else {
        htmlString += '<div class="element-container element-item">';
        htmlString += '<p>Geen resultaten gevonden.</p>';
        htmlString += '</div>';
    }

    contentSelector.html(jQuery.parseHTML(htmlString));

    //Reset modal event handlers
    renderInfoPage();
}

function renderSidePanel(arr) {
    let contentSelector = jQuery('#csi-side-panel');
    let uniqueMinorArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'minor')));
    let uniquePorterArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'porter')));
    let uniqueMetaTrendsArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'meta_trends')));

    let htmlString = '';

    if (arr !== null && arr.length > 0) {
        htmlString += '<div>';
        htmlString += '<h1>Trends</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';

        uniqueMetaTrendsArr.forEach(element => {
            htmlString += '<li><label for="meta-trends"><input class="csi-side-panel-checkbox" type="checkbox" name="meta_trends" value="' + element + '"/>' + element + '</label></li>';
        });

        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<div>';
        htmlString += '<h1>Value Chain (Michael Porter)</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';

        uniquePorterArr.forEach(element => {
            htmlString += '<li><label for="porter"><input class="csi-side-panel-checkbox" type="checkbox" name="porter" value="' + element + '"/>' + element + '</label></li>';
        });

        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<h1>Sector (SBI-code)</h1>';
        htmlString += '<div id="sbi-tree-view-container"></div>';

        htmlString += '<div>';
        htmlString += '<h1>Windesheim Minor</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';

        uniqueMinorArr.forEach(element => {
            htmlString += '<li><label for="minor"><input class="csi-side-panel-checkbox" type="checkbox" name="minor" value="' + element + '"/>' + element + '</label></li>';
        });

        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<div id="csi-submit-container"><button id="csi-submit">Verzenden</button></div>';
    }
    else {
        htmlString += '<div class="element-container element-item">';
        htmlString += '<p>Geen filters beschikbaar.</p>';
        htmlString += '</div>';
    }

    contentSelector.html(jQuery.parseHTML(htmlString));
}

function renderSbiTree() {
    let allSbiCodes = getAvailableSbiCodes();
    
    renderSbiTreeSections(allSbiCodes);
    renderSbiTreePerSection(allSbiCodes);
    initSbiTreeEvents();
}

function renderSbiTreeSections(dataArr) {
    let contentSelector = jQuery('#sbi-tree-view-container');
    let htmlString = '';
    let existsArr = [];

    htmlString += '<ul id="csi-sbi-ul">';

    dataArr.forEach(element => {
        if (!existsArr.includes(element.Letter)) {
            htmlString += '<li id="sbi-tree-view-section-' + element.Letter + '" class="sbi-tree-view-section">' + '<span class="csi-sbi-caret">' + element.Letter + ' - ' + element.SectionTitle + '</span></li>';
            existsArr.push(element.Letter);
        }
    });

    htmlString += '</ul>';

    contentSelector.html(jQuery.parseHTML(htmlString));
}

function renderSbiTreePerSection(dataArr) {
    dataArr.forEach(element => {
        let contentSelector = jQuery('#sbi-tree-view-section-' + element.Letter);
        let htmlString = '';

        htmlString += '<ul class="csi-sbi-nested">';
        
        getAllSbiByLetter(element.Letter).forEach(element => {
            htmlString += '<li class="csi-sbi-li"><label for="sbi"><input class="csi-side-panel-checkbox" type="checkbox" name="sbi" value="' + element.Code + '"/>' + element.Code + ' - ' + element.Title + '</label>';
        });
        
        htmlString += '</ul>';

        contentSelector.append(jQuery.parseHTML(htmlString));
    });
}

function initSbiTreeEvents() {
    let toggler = jQuery('.csi-sbi-caret');

    toggler.each(function () {
        jQuery(this).on('click', function () {
            this.parentElement.querySelector(".csi-sbi-nested").classList.toggle("sbi-tree-active");
            this.classList.toggle("sbi-tree-caret-down");
        });
    });
}

function renderModal(header, body, footer) {
    //Adding html elements
    let contentSelector = jQuery('#csi-public-info-modal-container');
    let htmlString = '';

    htmlString += '<div id="csi-public-info-modal" class="csi-public-info-modal">';
    htmlString += '<div class="csi-public-info-modal-content">';
    htmlString += '<div class="csi-public-info-modal-header">';
    htmlString += '<span id="csi-public-info-modal-close">&times;</span>';
    htmlString += '<h1 id="csi-public-info-modal-header-h1">' + header + '</h1>';
    htmlString += '</div>';
    htmlString += '<div class="csi-public-info-modal-body">';
    htmlString += body;
    htmlString += '</div>';
    htmlString += '<div class="csi-public-info-modal-footer">';
    htmlString += '<p id="csi-public-info-modal-footer-p">' + footer + '</p>';
    htmlString += '</div>';
    htmlString += '</div>';
    htmlString += '</div>';

    contentSelector.html(jQuery.parseHTML(htmlString));
}

function renderInfoPage() {
    jQuery('.csi-public-info-modal-open').click(function (event) {
        event.preventDefault();
        let subID = event.currentTarget.getAttribute("data-sub-id");
        
        jQuery.when(getCsiData(public_csi_ajax_info_obj.url + subID, public_csi_ajax_info_obj.nonce)).done(function (data) {
            renderModal('Case studie informatie', renderInfoModalBody(data), 'Copyright © 2022 Windesheim Technology Radar');
            initModalEvents();
        });
    });
}

function renderInfoModalBody(data) {
    let htmlString = '';

    htmlString += '<div id="csi-public-info-modal-body-container">';

    htmlString += '<div id="csi-public-info-modal-body-content">';

    htmlString += '<div id="csi-public-info-modal-body-content-contact">';
    htmlString += '<h2>Contactinformatie</h2>';
    htmlString += '<table id="csi-public-info-modal-body-content-contact-table">';
    htmlString += '<tr><th>Projectnaam</th></tr>';
    htmlString += '<tr><td>' + data.project_name + '</td></tr>';
    htmlString += '<tr><th>Projecteigenaar</th></tr>';
    htmlString += '<tr><td>' + data.project_owner + '</td></tr>';
    htmlString += '<tr><th>Projectemail</th></tr>';
    htmlString += '<tr><td>' + data.project_owner_email + '</td></tr>';
    htmlString += '</table>';
    htmlString += '</div>';
    
    htmlString += '<div id="csi-public-info-modal-body-content-details">';
    htmlString += '<h2>Details</h2>';
    htmlString += '<table id="csi-public-info-modal-body-content-details-table">';
    htmlString += data.minor != '' ? '<tr><th>Minor</th></tr><tr><td>' + data.minor + '</td></tr>' : '';
    htmlString += '<tr><th>SBI-code</th></tr>';
    htmlString += '<tr><td>' + data.sbi + '</td></tr>';
    htmlString += '<tr><th>Technologie innovaties</th></tr>';
    htmlString += '<tr><td>' + data.tech_innovations + '</td></tr>';
    htmlString += '<tr><th>Technologieleveranciers</th></tr>';
    htmlString += '<tr><td>' + data.tech_providers + '</td></tr>';
    htmlString += '<tr><th>Trends</th></tr>';
    htmlString += '<tr><td>' + data.meta_trends.join(', ') + '</td></tr>';
    htmlString += '<tr><th>Value Chain (Michael Porter)</th></tr>';
    htmlString += '<tr><td>' + data.porter + '</td></tr>';
    htmlString += '<tr><th>Bedrijfssector</th></tr>';
    htmlString += '<tr><td>' + data.company_sector + '</td></tr>';
    htmlString += '</table>';
    htmlString += '</div>';

    htmlString += '<div id="csi-public-info-modal-body-content-context">';
    htmlString += '<h2>Project context</h2>';
    htmlString += '<table id="csi-public-info-modal-body-content-context-table">';
    htmlString += '<tr><th>Projectcontext</th></tr>';
    htmlString += '<tr><td class="csi-public-info-modal-body-content-context-td">' + data.project_context + '</td></tr>';
    htmlString += '<tr><th>Projectprobleem</th></tr>';
    htmlString += '<tr><td class="csi-public-info-modal-body-content-context-td">' + data.project_problem + '</td></tr>';
    htmlString += '<tr><th>Projectdoel</th></tr>';
    htmlString += '<tr><td class="csi-public-info-modal-body-content-context-td">' + data.project_goal + '</td></tr>';
    htmlString += '</table>';
    htmlString += '</div>';

    htmlString += '<div id="csi-public-info-modal-body-content-links">';
    htmlString += '<h2>Links</h2>';
    htmlString += '<table id="csi-public-info-modal-body-content-links-table">';
    htmlString += '<tr><th>Website link</th></tr>';
    htmlString += '<tr><td><a href="' + (data.case_study_url.includes('http') ? data.case_study_url : 'http://' + data.case_study_url) + '" target="_blank">' + data.case_study_url + '</a></td></tr>';
    htmlString += '<tr><th>Film link</th></tr>';
    htmlString += '<tr><td><a href="' + (data.case_study_movie_url.includes('http') ? data.case_study_movie_url : 'http://' + data.case_study_movie_url) + '" target="_blank">' + data.case_study_movie_url + '</a></td></tr>';
    htmlString += '</table>';
    htmlString += '</div>';

    htmlString += '</div>';

    htmlString += '<div id="csi-public-info-modal-body-image">';
    htmlString += '<img src=' + data.case_study_image_url + '>';
    htmlString += '</div>';

    htmlString += '<div id="csi-public-info-modal-body-actions">';
    // htmlString += '<p>actions</p>';
    htmlString += '</div>';

    htmlString += '</div>';

    return htmlString;
}

function initModalEvents() {
    let modal = jQuery("#csi-public-info-modal");
    let span = jQuery("#csi-public-info-modal-close");
    let body = jQuery("body");

    modal.css('display', 'block');
    body.css('overflow', 'hidden');
    
    span.click(function () {
        modal.css('display', 'none');
        body.css('overflow', 'auto');
    });
}

//JSON storage funcions
function saveStorage(type, name, value) {
    let jsonString = JSON.stringify(value);
    type.setItem(name, jsonString);
}

function getStorage(type, name) {
    let jsonString = type.getItem(name);
    return JSON.parse(jsonString);
}

function removeStorage(type, name) {
    type.removeItem(name);
}

//Mobile responsive functionalities
function initMobileResponsive() {
    let content = jQuery('#csi-content');
    let sidePanel = jQuery('#csi-side-panel');
    let pagination = jQuery('#csi-pagination');
    let filterButton = jQuery('#csi-filter-button');
    let submitButton = jQuery('#csi-submit');

    //Init state
    screenRules(sidePanel, content, pagination, filterButton);

    filterButton.click(function () {
        if (filterButton.data('csi-filter-toggle') == 'true') {
            filterButton.data('csi-filter-toggle', 'false');
            sidePanel.show();
            content.hide();
            pagination.hide();
        }
        else {
            filterButton.data('csi-filter-toggle', 'true');
            sidePanel.hide();
            content.show();
            pagination.show();
        }
    });

    submitButton.click(function () {
        if (screen.width <= 550) {
            filterButton.data('csi-filter-toggle', 'true');
            sidePanel.hide();
            content.show();
            pagination.show();
        }
    });

    //On change state
    jQuery(window).resize(function () {
        screenRules(sidePanel, content, pagination, filterButton);
    });
}

function screenRules(sidePanel, content, pagination, filterButton) {
    if (screen.width > 550) {
        sidePanel.show();
        content.show();
        pagination.show();
    }
    else {
        filterButton.data('csi-filter-toggle', 'true');
        sidePanel.hide();
        content.show();
        pagination.show();
    }
}