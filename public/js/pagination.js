const page_limit = 10

function createPagination(urlName)
{
    $.ajax(
        {
            type: "GET",
            url: 'api/' + urlName,
            dataType: "json",
            success: function (response) {
                changeButtonHref(response.data, urlName)
            }, error: function (jqXHR, exception) {
                handleError(jqXHR, exception);
                window.location.href = '/';
            },
        });
}

function parseUrl() {
    const queryString = window.location.search
    const urlParams = new URLSearchParams(queryString)
    return urlParams.get('page')
}

function changeButtonHref(data, url){
    let pages_count = parseInt(data.length / page_limit)
    let current_page = parseUrl()
    let next_page = parseInt(current_page) + 1
    let previous_page = parseInt(current_page) - 1
    if(current_page < pages_count){
        let next_page_url = url + '?page=' + next_page
        let previous_page_url = url + '?page=' + previous_page
        $("#next").attr("href", next_page_url)
        if(previous_page != 0){
            $("#previous").attr("href", previous_page_url)
        }
    }
}