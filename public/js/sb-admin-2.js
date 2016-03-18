$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});

var Admin = {
    Panel: {
        Fields: {
            JsonMylti: {
                init: function(){},
                initEvents: function(){},
                addValue: function(){},
                removeValue: function(){},
                reCalculateValue: function(){},
                jsonParseFields: function(){}
            }
        }
    }
}

Admin.Panel.Fields.JsonMylti = {
    fieldMultiName: "",
    init: function(){
        var informInput = $(document).find('.json-multiply .disabled-jsonmulti.jsonmultivalue');
        var initHiddenInput = $('<input/>');
            initHiddenInput.addClass('jsonmultivalue');
            initHiddenInput.prop('type', 'hidden');
            initHiddenInput.attr('name', informInput.attr('name'));
            initHiddenInput.val(informInput.val());
        informInput.after(initHiddenInput);

        this.setFieldName(informInput);

        if( informInput.val() )
            this.jsonParseFields(informInput);

        this.initEvents();
    },
    addValue: function(e, value){
        var $e = $(e);
        var btnRemove  = $('<a class="input-group-addon remove btn btn-primary" href="javascript:void(0);">-</a>');
        var inputValue = $('<input class="form-control" name="'+this.fieldMultiName+'" type="text">').val(value);
        var blockGroup = $('<div></div>').addClass('form-group').addClass('input-group').append(btnRemove).append(inputValue);
        $e.closest('.json-multiply').append(blockGroup);
    },
    setFieldName: function($e){
        this.fieldMultiName = $e.attr('name')+"_";
    },
    removeValue: function(e){
        var $e = $(e);
        $e.closest('.form-group').remove();
    },
    initEvents: function(){
        var self = this;
        $(document).on('click', '.json-multiply a.input-group-addon.add', function(e){
            self.addValue(this);
            e.preventDefault();
        });
        $(document).on('click', '.json-multiply a.input-group-addon.remove', function(e){
            self.removeValue(this);
            self.reCalculateValue();
            e.preventDefault();
        });
        $(document).on('focusout', '.json-multiply input.form-control', function(e){
            self.reCalculateValue();
            e.preventDefault();
        });
    },
    reCalculateValue: function(){
        var features = {};
        $('.json-multiply input.form-control[name="'+this.fieldMultiName+'"]').each(function(){
            var val = $(this).val();
            if( val )
            features[val] = val;
        });
        var json = JSON.stringify(features);
        $('.jsonmultivalue').val(json);
    },
    jsonParseFields: function($e){
        var self = this;
        var data = JSON.parse($e.val());
        this.setFieldName($e);
        var item = document.querySelector('.json-multiply a.input-group-addon.add');
        $.each(data, function(i, e){
            self.addValue(item, e);
        });
    }
}

$(document).ready(function(){
    Admin.Panel.Fields.JsonMylti.init();
});