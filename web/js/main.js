function show_modal(id, metod, page, datas) {
    $("#" + id).modal("show");
    $("#" + id).on("hidden.bs.modal", function() {
        $("#" + id + " .modal-body").html("");
        $(this).off('hidden.bs.modal');
    });
    $.ajax({
        url: page,
        type: metod,
        data: datas,
        success: function(response) {
            $("#" + id + " .modal-body").html(response);
        },
        error: function(response) {
        },
    });
}

$(document).on("click", ".btn_delete_modal", function() {
	var data=$(this).data();
	
	show_delete_modal(data.id_modal,data);
});

function show_delete_modal(id,datas) {
	$("#"+id).modal("show");
	$("#"+id+" .modal-body").html("Вы уверены, что хотите удалить эту запись?");
	$("#delete-confirm-btn").data(datas);
}

$(document).on("click", "#delete-confirm-btn", function(){
	delete_row($(this).data());
});

function delete_row(datas){
	if ("bs.tooltip" in datas){
		delete datas["bs.tooltip"];
	}
	$(".fade.modal.show").modal("hide");
	$("#"+(datas.id_modal)).on("hidden.bs.modal", function() {
		$.ajax({
			url: datas.path,
			type: "post",
			data:  "info="+JSON.stringify(datas),
			success: function(response){
				$.pjax.defaults.timeout = false;//IMPORTANT
				$.pjax.reload({container:"#"+datas.pjax_container});  //Reload GridView
			},
			error: function (response) {
			}
		});
		$(this).off("hidden.bs.modal");
	});
}