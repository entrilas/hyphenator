$.ajax(
    {
        type: "GET",
        url: '/api/patterns',
        dataType: "json",
        success: function (response) {
            for (var i in response.data) {
                var data = response.data[i];
                $('#patternsTable tbody').append($('<tr>')
                    .append($('<td>', {text: data.id}))
                    .append($('<td>', {text: data.pattern}))
                    .append(
                        `<a class="btn btn-default btn-outline-dark" href='/patterns/${data.id}'>Edit</a>`+
                        `<a class="btn btn-default btn-outline-dark" onclick="deletePattern(${data.id})">Delete</a>`
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
        url: "/api/patterns/" + id,
        type: "DELETE",
        contentType: "application/json",
        success: function (data) {
            alert('Pattern has been deleted');
            window.location.href = '/patterns';
        }
    });
}