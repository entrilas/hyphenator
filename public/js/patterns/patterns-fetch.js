const url = 'api/patterns'
const urlWithParams = 'api/patterns?page=' + parseUrl()
const urlApi = 'api/patterns/'
const urlName = 'patterns'

$.ajax(
    {
        type: "GET",
        url: urlWithParams,
        dataType: "json",
        success: function (response) {
            for (let i in response.data) {
                let data = response.data[i];
                $('#patternsTable tbody').append($('<tr>')
                    .append($('<td>', {text: data.id}))
                    .append($('<td>', {text: data.pattern}))
                    .append(
                        `<a class="btn btn-warning" href='/patterns/${data.id}'>Edit</a>`+
                        `<a class="btn btn-danger" onclick="deletePattern(${data.id})">Delete</a>`
                    )
                )
            }
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/';
        },
    });

function deletePattern(id)
{
    $.ajax({
        url: urlApi + id,
        type: 'DELETE',
        contentType: 'application/json',
        success: function (response) {
            alert('Item has been deleted');
            window.location.href = '/patterns';
        }
    });
}

createPagination(urlName)