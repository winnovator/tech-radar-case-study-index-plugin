//Causes no conflict with $ symbol
jQuery.noConflict();

//Case study index functionalities
var currentPageCount = 1;

jQuery(document).ready(function() {
    jQuery.when(getCsiData(public_csi_ajax_obj.url, public_csi_ajax_obj.nonce)).done(function(data) {
        removeStorage(sessionStorage, 'public-csi-default-data');
        removeStorage(sessionStorage, 'public-csi-filtered-data');
        saveStorage(sessionStorage, 'public-csi-default-data', data);
        let jsonDefaultData = getStorage(sessionStorage, 'public-csi-default-data');
        let totalPageCount = divideArr(jsonDefaultData).length;
        let currentPage = getCurrentPage(jsonDefaultData, currentPageCount - 1);

        renderSidePanel(jsonDefaultData);
        renderOutput(currentPage);
        setPaginationButtons(totalPageCount);

        jQuery('#csi-previous-page').click(function() {
            prevPage();
            let filteredData = getStorage(sessionStorage, 'public-csi-filtered-data') === null ? 
            getStorage(sessionStorage, 'public-csi-default-data') : getStorage(sessionStorage, 'public-csi-filtered-data');
            let totalPageCount = divideArr(filteredData).length;
            let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
            renderOutput(currentPage);
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
        });

        jQuery('#csi-next-page').click(function() {
            let filteredData = getStorage(sessionStorage, 'public-csi-filtered-data') === null ? 
            getStorage(sessionStorage, 'public-csi-default-data') : getStorage(sessionStorage, 'public-csi-filtered-data');
            let totalPageCount = divideArr(filteredData).length;
            nextPage(totalPageCount);
            let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
            renderOutput(currentPage);
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
        });
    
        jQuery('#csi-submit').click(function() {
            currentPageCount = 1;
            let filteredData = filter(getAllCheckedInput(), jsonDefaultData);
            saveStorage(sessionStorage, 'public-csi-filtered-data', filteredData);
            let jsonFilteredData = getStorage(sessionStorage, 'public-csi-filtered-data');
            let totalPageCount = divideArr(jsonFilteredData).length;
            let currentPage = getCurrentPage(jsonFilteredData, currentPageCount - 1);
            renderOutput(currentPage);
            setPaginationButtons(totalPageCount);
        });

        //Mobile responsive functionalities referrences
        initMobileResponsive();

        //Render sbi tree view
        renderSbiTree();

        //Render csi info modal
        renderInfoPage();
    });
});

//Start API call functions
function getCsiData(url, nonce, async = true) {
    return jQuery.ajax({
        method: "GET",
        url: url,
        data: { public_csi_security_nonce: nonce },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', nonce);
        },
        async: async
    });
}

function getSbiSectionData() {
    return jQuery.ajax({
        method: "GET",
        url: "https://sbi.cbs.nl/CBS.TypeerModule.TypeerServiceWebAPI/api/SBIData/Sections"
    });
}

function getSbiSectionData() {
    return jQuery.ajax({
        method: "GET",
        url: "https://sbi.cbs.nl/CBS.TypeerModule.TypeerServiceWebAPI/api/SBIData/Sections"
    });
}

function getSbiDataPerSection(letter) {
    return jQuery.ajax({
        method: "GET",
        url: "https://sbi.cbs.nl/CBS.TypeerModule.TypeerServiceWebAPI/api/SBIData/SectionChildrenTree/" + letter
    });
}

function getSbiDataPerNumber(number) {
    return jQuery.ajax({
        method: "GET",
        url: "https://sbi.cbs.nl/CBS.TypeerModule.TypeerServiceWebAPI/api/SBIData/SbiInfo/" + number,
        async: false
    });
}

//Filter functions
function getAllCheckedInput() {
    let searchArr = [];
    
    jQuery('input:checked').each(function() {
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
        jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
        jQuery('#csi-previous-page').show();
        jQuery('#csi-next-page').show();
    }
    else {
        jQuery('#csi-current-page').text('Geen pagina\'s.');
        jQuery('#csi-previous-page').hide();
        jQuery('#csi-next-page').hide();
    }
}

//Generic functions
function arrayUnique(arr) {
    return arr.filter(function(item, pos) {
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
            htmlString += '<div class="csi-element-container csi-element-item">';
            htmlString += '<h1><a class="csi-public-info-modal-open" href="#/" data-sub-id="' + element.id + '">' + element.project_name + '</a></h1>';
            htmlString += '<table class="csi-item-table">';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Minor: </th><td class="csi-item-td">' + (element.minor == '' ? 'Geen minor.' : element.minor) + '</td></tr>';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Project Stage: </th><td class="csi-item-td">' + element.project_stage +'</td></tr>';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Michael Porter\'s Value Chain: </th><td class="csi-item-td">' + element.porter.join(', ') + '</td></tr>';

            jQuery.when(getSbiDataPerNumber(element.sbi)).done(function(data) {
                htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">SBI-code: </th><td class="csi-item-td">' + data.Code + ' - ' + data.Title + '</td></tr>';
            });

            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Meta Trends: </th><td class="csi-item-td">' + (Array.isArray(element.meta_trends) ? element.meta_trends.join(', ') : (element.meta_trends.length > 0 ? element.meta_trends : 'Geen trends')) + '</td></tr>';
            htmlString += '</table>';
            htmlString += '</div>';
        });
    }
    else {
        htmlString += '<div class="element-container element-item">';
        htmlString += '<p>Geen resultaten gevonden.</p>';
        htmlString += '</div>';
    }

    contentSelector.html(jQuery.parseHTML(htmlString));
}

function renderSidePanel(arr) {
    let contentSelector = jQuery('#csi-side-panel');
    let uniqueMinorArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'minor')));
    let uniqueProjectStageArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'project_stage')));
    let uniquePorterArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'porter')));
    let uniqueMetaTrendsArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'meta_trends')));

    let htmlString = '';

    if (arr !== null && arr.length > 0) {
        htmlString += '<div>';
        htmlString += '<h1>Windesheim Minor</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';
        
        uniqueMinorArr.forEach(element => {
            htmlString += '<li><label for="minor"><input class="csi-side-panel-checkbox" type="checkbox" name="minor" value="' + element + '"/>' + element + '</label></li>';
        });

        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<div>';
        htmlString += '<h1>Project Stage</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';

        uniqueProjectStageArr.forEach(element => {
            htmlString += '<li><label for="project-stage"><input class="csi-side-panel-checkbox" type="checkbox" name="project_stage" value="' + element + '"/>' + element + '</label></li>';
        });
        
        htmlString += '</ul>';
        htmlString += '</div>';
        
        htmlString += '<div>';
        htmlString += '<h1>Michael Porter\'s Value Chain</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';

        uniquePorterArr.forEach(element => {
            htmlString += '<li><label for="porter"><input class="csi-side-panel-checkbox" type="checkbox" name="porter" value="' + element + '"/>' + element + '</label></li>';
        });
        
        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<h1>SBI-code</h1>';
        htmlString += '<div id="sbi-tree-view-container"></div>';
        
        htmlString += '<div>';
        htmlString += '<h1>Meta Trends</h1>';
        htmlString += '<ul class="csi-side-panel-ul">';

        uniqueMetaTrendsArr.forEach(element => {
            htmlString += '<li><label for="meta-trends"><input class="csi-side-panel-checkbox" type="checkbox" name="meta_trends" value="' + element + '"/>' + element + '</label></li>';
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
    jQuery.when(getSbiSectionData()).then(function(data) {
        let contentSelector = jQuery('#sbi-tree-view-container');
        let htmlString = '';

        htmlString += '<ul id="csi-sbi-ul">';

        data.forEach((element) => {
            htmlString += '<li id="sbi-tree-view-section-' + element.Letter + '" class="sbi-tree-view-section" data-sbi-section-letter="' + element.Letter  + '">' +'<span class="csi-sbi-caret">' + element.Letter + ' - ' + element.Title + '</span></li>';
        });

        htmlString += '</ul>';
        contentSelector.html(jQuery.parseHTML(htmlString));
    }).done(function() {
        let letterArr = [];

        jQuery('.sbi-tree-view-section').each(function() {
            letterArr.push(jQuery(this).data('sbi-section-letter'));
        });

        letterArr.forEach((element) => {
            jQuery.when(getSbiDataPerSection(element)).then(function(data) {
                let htmlString = "";
                let contentSelector = jQuery('#sbi-tree-view-section-' + element);

                htmlString += '<ul class="csi-sbi-nested">';

                data.forEach((element) => {
                    htmlString += '<li class="csi-sbi-li"><label for="sbi"><input class="csi-side-panel-checkbox" type="checkbox" name="sbi" value="' + element.Code + '"/>' + element.Code + ' - ' + element.Title + '</label>';
                });

                htmlString += '</ul>';

                contentSelector.append(jQuery.parseHTML(htmlString));
            }).done(function() {
                let toggler = jQuery('.csi-sbi-caret');
                
                toggler.each(function() {
                    jQuery(this).on('click', function() {
                        this.parentElement.querySelector(".csi-sbi-nested").classList.toggle("active");
                        this.classList.toggle("caret-down");
                    });
                });
            });
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
    htmlString += '<h1>' + header + '</h1>';
    htmlString += '</div>';
    htmlString += '<div class="csi-public-info-modal-body">';
    htmlString += body;
    htmlString += '</div>';
    htmlString += '<div class="csi-public-info-modal-footer">';
    htmlString += '<p>' + footer + '</p>';
    htmlString += '</div>';
    htmlString += '</div>';
    htmlString += '</div>';

    contentSelector.html(jQuery.parseHTML(htmlString));

    //Events
    let modal = jQuery("#csi-public-info-modal");
    let span = jQuery("#csi-public-info-modal-close");
}

function renderInfoPage() {
    jQuery('.csi-public-info-modal-open').click(function(event) {
        event.preventDefault();
        let subID = event.currentTarget.getAttribute("data-sub-id");

        jQuery.when(getCsiData(public_csi_ajax_info_obj.url + subID, public_csi_ajax_info_obj.nonce)).done(function(data) {
            renderModal('test', data.id, 'Made by Mike Harman');
            let modal = jQuery("#csi-public-info-modal");
            let span = jQuery("#csi-public-info-modal-close");

            modal.css('display', 'block');

            span.click(function() {
                modal.css('display', 'none');
            });
        });
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

    filterButton.click(function() {
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

    submitButton.click(function() {
        if (screen.width <= 550) {
            filterButton.data('csi-filter-toggle', 'true');
            sidePanel.hide();
            content.show();
            pagination.show();
        }
    });

    //On change state
    jQuery(window).resize(function() {
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