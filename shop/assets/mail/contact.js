$(function () {
    // Apply validation to form elements
    $("#contactForm input, #contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
            // Additional error handling if needed
        },
        filter: function () {
            return $(this).is(":visible");
        },
    });

    // Handle tab click events
    $("a[data-toggle=\"tab\"]").click(function (e) {
        e.preventDefault();
        $(this).tab("show");
    });
});

// Clear success/error messages on focus of the
