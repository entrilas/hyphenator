$( "#wordUpdateForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();
    var word={
        'word': $("#inputWord").val(),
        'hyphenated_word': $("#inputWord").val()
    };
    $.ajax({
        url: "/api/words/" + id,
        type: "PUT",
        data: JSON.stringify(word),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            alert('Word has been updated');
            window.location.href = '/strings?page=1';
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/strings?page=1';
        },
    });
});
