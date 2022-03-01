$( "#patternUpdateForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();
    var pattern={
        'pattern': $("#inputPattern").val()
    };
    $.ajax({
        url: "/api/patterns/" + id,
        type: "PUT",
        data: JSON.stringify(pattern),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            alert('Pattern has been updated');
            window.location.href = '/patterns';
        }, error: function (jqXHR, exception) {
            handleError(jqXHR, exception);
            window.location.href = '/patterns';
        },
    });
});
