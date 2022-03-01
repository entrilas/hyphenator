$.ajax(
    {
        type: "GET",
        url: '/api/words',
        dataType: "json",
        success: function (response) {
            for (var i in response.data) {
                var data = response.data[i];
                $('#wordsTable tbody').append($('<tr>')
                    .append($('<td>', {text: data.id}))
                    .append($('<td>', {text: data.word}))
                    .append($('<td>', {text: data.hyphenated_word}))
                    .append(
                        `<a class="btn btn-default btn-outline-dark" href='/words/${data.id}'>Edit</a>`+
                        `<a class="btn btn-default btn-outline-dark" onclick="deleteWord(${data.id})">Delete</a>`
                    )
                )
            }
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/';
        },
    });

function deleteWord(id)
{
    $.ajax({
        url: "/api/words/" + id,
        type: "DELETE",
        contentType: "application/json",
        success: function (data) {
            alert('Word has been deleted');
            window.location.href = '/words';
        }
    });
}