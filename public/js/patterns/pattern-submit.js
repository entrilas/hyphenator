$( "#patternSubmitForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();
    var pattern={
        'pattern': $("#inputPattern").val()
    };
    $.ajax({
        url: "/api/patterns",
        type: "POST",
        data: JSON.stringify(pattern),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            alert('Pattern has been submitted');
            window.location.href = '/patterns';
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/patterns';
        },
    });
});
