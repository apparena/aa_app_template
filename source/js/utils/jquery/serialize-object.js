// extend jquery to be able to pass form data as a json automatically
// (calling serializeObject will pack the data from the name attributes as a js-object)
/**
 * Description
 * @method serializeObject
 * @return items
 */
$.fn.serializeObject = function () {
    var items = {},
        form = this[ 0 ],
        index,
        item;

    if (typeof form === 'undefined') {
        return {};
    }

    for (index = 0; index < form.length; index++) {
        item = form[ index ];

        if (typeof( item.type ) !== 'undefined' && item.type === 'checkbox') {
            item.value = $(item).is(':checked');
        }

        if (typeof( item.name ) !== 'undefined' && item.name.length > 0) {
            items[ item.name ] = item.value;
        } else {
            if (typeof( item.id ) !== 'undefined' && item.id.length > 0) {
                items[ item.id ] = item.value;
            }
        }
    }
    return items;
};