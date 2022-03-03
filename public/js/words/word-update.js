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
            window.location.href = '/words';
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/words?page=1';
        },
    });
});
