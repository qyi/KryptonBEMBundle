BEM.DOM.decl({'name': 'b-input'}, {

    onSetMod : {

        'js' : {

            'inited' : function() {

                this
                    .bindTo(this.elem('field'), {
                        'blur'  : this.validateField
                    })

            }

        }

    },
    
    validateField: function() {
        var validation = this.params.validation;
        
        if (!validation || !(validation instanceof Object)) {
            return;
        }

        for (var name in validation) {
            if (this.validators[name]) {
                this.validators[name].call(this, validation[name]);
            }
        }
    },
    
    validators: {
        minLength: function(value) {
            if (this.elem('field').val().length <= value) {
                console.log('field invalid');
            }
        }
    }
    
    
        
}, {

    live : function() {
        this.liveBindTo({ 'elem': 'field' }, 'leftclick', function(e) {}, true);
    }

});
