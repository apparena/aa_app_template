// @see http://jqueryvalidation.org/
$.validator.setDefaults({
    debug:        true,
    validClass:   'has-success',
    focusCleanup: false,
    focusInvalid: true,
    errorClass:   'has-error',
    errorElement: 'span', // contain the error msg in a span tag
    ignore:       '.ignore',

    /**
     * Description
     * @method errorPlacement
     * @param {} error
     * @param {} element
     * @return 
     */
    errorPlacement: function (error, element) {
        // modify error object
        error.addClass('help-block');

        if (element.parent().hasClass('input-prepend') || element.parent().hasClass('input-append')) {
            // if the input has a prepend or append element, put the validation msg after the parent div
            error.insertAfter(element.parent());
        } else {
            // else just place the validation message immediatly after the input
            error.insertAfter(element);
        }
    },

    /**
     * Description
     * @method highlight
     * @param {} element
     * @param {} errorClass
     * @param {} validClass
     * @return 
     */
    highlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
    },

    /**
     * Description
     * @method unhighlight
     * @param {} element
     * @param {} errorClass
     * @param {} validClass
     * @return 
     */
    unhighlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass(errorClass).addClass(validClass); // add the Bootstrap error class to the control group
    },

    /**
     * Description
     * @method success
     * @param {} element
     * @return 
     */
    success: function (element) {
        element.remove();
        this.unhighlight();
    }/*,

     // this works not in chrome
     submitHandler: function (form) {
     _.debug.log('validate submit handler', form);
     $(form).find('fieldset').prop('disabled', false).find('button').button('reset');
     }*/
});