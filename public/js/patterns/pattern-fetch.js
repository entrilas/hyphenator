$.ajax(
    {
        type: "GET",
        url: '/api/patterns/' + id,
        dataType: "json",
        success: function (response) {
                $("#inputPattern").val(response.data.pattern);
        }, error: function (jqXHR, exception) {
                handleError(jqXHR, exception);
                window.location.href = '/patterns';
            },
    }
);