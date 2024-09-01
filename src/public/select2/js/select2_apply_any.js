// runner select2 in all select2 if not inited
$(() => {
    let selector = 'select.form-control:not(.select2-hidden-accessible)';
    let selects = $(selector);
    let options = select2Options(selects);

    selects.select2(options);
});
