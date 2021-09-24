$(document).on('beforeSubmit', '#form_create', function(){
    var formData = new FormData(this);
	$(".fade.modal.show").modal("hide");
    $.ajax({
        url: 'department-creating',
        type: "post",
        data: formData,
        cache       : false,
        contentType : false,
        processData : false,
        success: function(response){
            $.pjax.defaults.timeout = false;
            $.pjax.reload({container:"#department_grid_pjax"});
        },
        error: function (response) {
        }
    });
    return false;

});

$(document).on('beforeSubmit', '#form_update', function(){
    var formData = new FormData(this);
	$(".fade.modal.show").modal("hide");
    $.ajax({
        url: 'department-updating',
        type: "post",
        data: formData,
        cache       : false,
        contentType : false,
        processData : false,
        success: function(response){
            $.pjax.defaults.timeout = false;
            $.pjax.reload({container:"#department_grid_pjax"});
        },
        error: function (response) {
        }
    });
    return false;
});