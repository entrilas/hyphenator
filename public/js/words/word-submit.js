$( "#wordSubmitForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();
    var word={
        'word': $("#inputWord").val()
    };
    $.ajax({
        url: "/api/words",
        type: "POST",
        data: JSON.stringify(word),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            alert('Word has been hyphenated');
            window.location.href = '/strings?page=1';
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/strings?page=1';
        },
    });
});
