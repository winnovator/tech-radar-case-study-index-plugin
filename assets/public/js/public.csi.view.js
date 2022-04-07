var currentPageCount = 1;

jQuery(document).ready(function() {
    jQuery.when(getData(public_csi_ajax_obj.url, 'get_csi_data', public_csi_ajax_obj.nonce)).done(function(data) {
        let jsonObj = JSON.parse(data);
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

function getData(url, action, nonce) {
    let dataObj = {
        action: action,
        public_csi_security_nonce: nonce
    };

    return jQuery.get(url, dataObj);
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

function renderOutput(arr) {
    let contentSelector = jQuery('#csi-content');
    let htmlString = '';

    if (arr !== null && arr.length > 0) {
        arr.forEach(element => {
            htmlString += '<div class="csi-element-container csi-element-item">';
            htmlString += '<h1><a href="' + element.case_study_url + '" target="_blank">' + element.project_name + ' - ' + element.tech_providers + '</a></h1>';
            htmlString += '<p>' + element.minor +'</p>';
            htmlString += '<p>' + element.project_stage +'</p>';
            htmlString += '<p>' + element.porter.join(', ') +'</p>';
            htmlString += '<p>' + element.sbi +'</p>';
            htmlString += '<p>' + element.meta_trends.join(', ') +'</p>';
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