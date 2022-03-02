console.log(id);
$.ajax(
    {
        type: "GET",
        url: '/api/words/' + id,
        dataType: "json",
        success: function (response) {
                $("#inputWord").val(response.data.word);
                $("#inputHyphenatedWord").val(response.data.hyphenated_word);
        }, error: function (jqXHR, exception) {
                handleError(jqXHR, exception);
                window.location.href = '/words';
            },
    }
);