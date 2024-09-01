/**
 * Returns an object with the select2 options based on the given selector.
 *
 * @param {jQuery} select - The jQuery object representing the select element.
 * @param {string} parents - The optional parent selector to use for the dropdown parent. Defaults to '.offcanvas '.
 * @return {object} An object with the select2 options.
 */
function select2Options(select, parents ) {
    let parent = parents ?? '.offcanvas ';
    let inFilter = select.parents(parent)

    return $.extend({
        theme: 'bootstrap-5',
    }, inFilter.length > 0 ? {
        dropdownParent: $(parent)
    } : {});
}
