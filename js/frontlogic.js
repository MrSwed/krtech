$(function(){
	$("form").each(function(){
		var f = $(this);
		var m = $(".message", f);
		var verify = ["name", "birthday"];
		var verifed = verify.length;
		f.submit(function(e){
			e.preventDefault();
			for (var k in verify) {
				$("input[name='" + verify[k] + "']").val() && verifed--;
			}
			if (verifed < 1) {
				m.html("").removeClass("error info");
				$.ajax({
					type: f.attr("method"),
					url: f.attr("action"),
					data: f.serialize(),
					dataType: "json",
					success: function(data){
						m.addClass("info").html(data["discount"] ? "Вы можете расчитывать на скидку " + data["discount"] + "!": "Нет доступных скидок.:( ");
					}
				});
			} else {
				m.addClass("error").html("Пожалуйста, заполните корректно обязательные поля");
			}
		});
		$("[type='reset']",f).click(function(){
			m.html("").removeClass("error info");
		})
	})
});