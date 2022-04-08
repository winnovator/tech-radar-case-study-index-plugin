var currentPageCount = 1;

jQuery(document).ready(function() {
    jQuery.when(getData(public_csi_ajax_obj.url, public_csi_ajax_obj.nonce)).done(function(data) {
        let jsonObj = data;
        let filteredData = filter(getAllCheckedInput(), jsonObj);
        let totalPageCount = divideArr(filteredData).length;
        let currentPage = getCurrentPage(filteredData, currentPageCount - 1);

        renderSidePanel(jsonObj);
        renderOutput(currentPage);

        if (totalPageCount > 0) {
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
            jQuery('#csi-previous-page').show();
            jQuery('#csi-next-page').show();
        }
        else {
            jQuery('#csi-current-page').text('No pages.');
            jQuery('#csi-previous-page').hide();
            jQuery('#csi-next-page').hide();
        }

        jQuery('#csi-previous-page').click(function() {
            prevPage();
            let filteredData = filter(getAllCheckedInput(), jsonObj);
            let totalPageCount = divideArr(filteredData).length;
            let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
            renderOutput(currentPage);
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
        });

        jQuery('#csi-next-page').click(function() {
            let filteredData = filter(getAllCheckedInput(), jsonObj);
            let totalPageCount = divideArr(filteredData).length;
            nextPage(totalPageCount);
            let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
            renderOutput(currentPage);
            jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
        });
    
        jQuery('#csi-submit').click(function() {
            currentPageCount = 1;
            let filteredData = filter(getAllCheckedInput(), jsonObj);
            let totalPageCount = divideArr(filteredData).length;
            let currentPage = getCurrentPage(filteredData, currentPageCount - 1);
            renderOutput(currentPage);

            if (totalPageCount > 0) {
                jQuery('#csi-current-page').text(currentPageCount + ' - ' + totalPageCount);
                jQuery('#csi-previous-page').show();
                jQuery('#csi-next-page').show();
            }
            else {
                jQuery('#csi-current-page').text('No pages.');
                jQuery('#csi-previous-page').hide();
                jQuery('#csi-next-page').hide();
            }
        });
    });
});

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
            if (needle.some(i => element.includes(i))) {
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

function getData(url, nonce) {
    return jQuery.ajax({
        method: "GET",
        url: url,
        data: { public_csi_security_nonce: nonce },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', nonce);
        }
    });
}

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
    let casesPerPage = 5;
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

function arrayUnique(arr) {
    return arr.filter(function(item, pos) {
        return arr.indexOf(item) == pos;
    });
}

function removeEmptyElements(arr) {
    return arr.filter(item => item);
}

function renderOutput(arr) {
    let contentSelector = jQuery('#csi-content');
    let htmlString = '';

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            htmlString += '<div class="csi-element-container csi-element-item">';
            htmlString += '<h1><a href="' + element.case_study_url + '" target="_blank">' + element.project_name + ' - ' + element.tech_providers + '</a></h1>';
            htmlString += '<table class="csi-item-table">';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Minor: </th><td class="csi-item-td">' + element.minor +'</td></tr>';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Project Stage: </th><td class="csi-item-td">' + element.project_stage +'</td></tr>';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Michael Porter\'s Value Chain: </th><td class="csi-item-td">' + element.porter.join(', ') +'</td></tr>';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">SBI-code: </th><td class="csi-item-td">' + element.sbi +'</td></tr>';
            htmlString += '<tr class="csi-item-tr"><th class="csi-item-th">Meta Trends: </th><td class="csi-item-td">' + element.meta_trends.join(', ') +'</td></tr>';
            htmlString += '</table>';
            htmlString += '</div>';
        });
    }
    else {
        htmlString += '<div class="element-container element-item">';
        htmlString += '<p>No results found.</p>';
        htmlString += '</div>';
    }

    contentSelector.html(htmlString);
}

function renderSidePanel(arr) {
    let contentSelector = jQuery('#csi-side-panel');
    let uniqueMinorArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'minor')));
    let uniqueProjectStageArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'project_stage')));
    let uniquePorterArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'porter')));
    let uniqueSbiArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'sbi')));
    let uniqueMetaTrendsArr = removeEmptyElements(arrayUnique(convertToSingleTypeArr(arr, 'meta_trends')));

    let htmlString = '';

    if (arr !== null && arr.length > 0) {
        htmlString += '<div>';
        htmlString += '<h1>Windesheim Minor</h1>';
        htmlString += '<ul>';
        
        uniqueMinorArr.forEach(element => {
            htmlString += '<li><label for="minor"><input type="checkbox" name="minor" value="' + element + '"/>' + element + '</label></li>';
        });

        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<div>';
        htmlString += '<h1>Project Stage</h1>';
        htmlString += '<ul>';

        uniqueProjectStageArr.forEach(element => {
            htmlString += '<li><label for="project-stage"><input type="checkbox" name="project_stage" value="' + element + '"/>' + element + '</label></li>';
        });
        
        htmlString += '</ul>';
        htmlString += '</div>';
        
        htmlString += '<div>';
        htmlString += '<h1>Michael Porter\'s Value Chain</h1>';
        htmlString += '<ul>';

        uniquePorterArr.forEach(element => {
            htmlString += '<li><label for="porter"><input type="checkbox" name="porter" value="' + element + '"/>' + element + '</label></li>';
        });
        
        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<div>';
        htmlString += '<h1>SBI-code</h1>';
        htmlString += '<ul>';

        uniqueSbiArr.forEach(element => {
            htmlString += '<li><label for="sbi"><input type="checkbox" name="sbi" value="' + element + '"/>' + element + '</label></li>';
        });
        
        htmlString += '</ul>';
        htmlString += '</div>';
        
        htmlString += '<div>';
        htmlString += '<h1>Meta Trends</h1>';
        htmlString += '<ul>';

        uniqueMetaTrendsArr.forEach(element => {
            htmlString += '<li><label for="meta-trends"><input type="checkbox" name="meta_trends" value="' + element + '"/>' + element + '</label></li>';
        });
        
        htmlString += '</ul>';
        htmlString += '</div>';

        htmlString += '<div id="csi-submit-container"><button id="csi-submit" class="button-4">Verzenden</button></div>';
    }
    else {
        htmlString += '<div class="element-container element-item">';
        htmlString += '<p>No filter data available.</p>';
        htmlString += '</div>';
    }

    contentSelector.html(htmlString);
}