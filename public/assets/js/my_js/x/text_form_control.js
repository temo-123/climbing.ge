    $('#contact-form').bootstrapValidator({
    // live: 'disabled',
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The Name is required and cannot be empty'
                    }
                }
            },            
            surname: {
                validators: {
                    notEmpty: {
                        message: 'The Surname is required and cannot be empty'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The email address is not valid'
                    }
                }
            },
            text: {
                validators: {
                    notEmpty: {
                        message: 'The Comment is required and cannot be empty'
                    }
                }
            }
        }
    });