const url = 'api/words'
const urlWithParams = 'api/words?page=' + parseUrl()
const urlApi = 'api/words/'
const urlName = 'words'

$.ajax(
    {
        type: "GET",
        url: urlWithParams,
        dataType: "json",
        success: function (response) {
            for (let i in response.data) {
                let data = response.data[i];
                $('#wordsTable tbody').append($('<tr>')
                    .append($('<td>', {text: data.id}))
                    .append($('<td>', {text: data.word}))
                    .append($('<td>', {text: data.hyphenated_word}))
                    .append(
                        `<a class="btn btn-danger" href='/patterns/${data.id}'>Edit</a>`+
                        `<a class="btn btn-warning" onclick="deleteWord(${data.id})">Delete</a>`
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
        url: urlApi + id,
        type: 'DELETE',
        contentType: 'application/json',
        success: function (response) {
            alert('Word has been deleted');
            window.location.href = '/words';
        }
    });
}

createPagination(urlName)