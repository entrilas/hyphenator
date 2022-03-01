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
                        '<a class="btn btn-default btn-outline-dark" href="">Edit</a>'+
                        '<a class="btn btn-default btn-outline-dark" href="">Delete</a>'
                    )
                )
            }
        }
    });