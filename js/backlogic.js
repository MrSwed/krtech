$(function(){
	(function(o){
		$(o).each(function(){
			var f = this;
			$(f).submit(function(e){
				e.preventDefault();
			});
			f.tb = $("tbody", f);
			f.m = $(".message", f);
			f.message = function(p){
				if (!p||p.toLowerCase()=="clear") p={"clear":true}; 
				p = $.extend({},{"text":"","class":"info","clear":false},p);
				p.clear && $(f.m).attr("class",'message').html('');
				p.class && $(f.m).attr("class",'message '+p.class);
				p.text && $(f.m).html(p.text);
			};
			f.init = function(){
				$("tr:not(.template)", f.tb).remove();
				f.message();
				$.ajax({
					type: $(f).attr("method"),
					url: $(f).attr("action"),
					data: {"action": "get", "target": $(f).attr("data-source")},
					dataType: "json",
					success: function(data){
						for (r in data) {
							var nr = $(".template", f).clone(true).appendTo(f.tb).removeClass("template");
							for (k in data[r]) {
								if (k.match(/^date_/)) {
									data[r][k] = data[r][k].split(" ")[0];
								}
								var inp = $("[name='" + k + "']", nr);
								inp.length && inp.val(data[r][k]);
							}
						}
					},
					error: function(event, request, settings){
						f.message({"text":"Error sending request " + settings.url + "","class":"error"});
						console.log(event, request, settings);
					}
				});
			};

			// handle data
			$("[name]", f).change(function(e){
				$(this).addClass("changed").closest("tr").find("button.save").attr("disabled", false);
			});
			$(f).on("click", "button", function(e){
				e.preventDefault();
				var t = $(this);
				var rowData = t.closest("tr");
				// console.log("button click",e);
				switch (true) {
					case t.is(".add"):
						var nr = $(".template", f).clone(true).appendTo(f.tb).removeClass("template");
						$("[name]:not([readonly])", nr).addClass("changed").filter(":first").focus();
						// $("")
						break;
					case t.is(".del"):
//todo:
						rowData.remove();
						break;
					case t.is(".clone"):
						rowData.clone(true).insertAfter(rowData).find("[name]").change().filter("[name='id']").val('');
						break;
					case t.is(".save"):
						// save data
						var data = {};
						$("[name]", rowData).each(function(){
							data[this.name] = this.value;
						});
						$.ajax({
							type: $(f).attr("method"),
							url: $(f).attr("action"),
							data: {"action": "save", "target": $(f).attr("data-source"), "data": data},
							dataType: "json",
							success: function(result){
								console.log(result);
								if (result["ok"]) {
									$("[name]", rowData).removeClass("changed");
									$(f.m).removeClass("error").addClass("info").html("Данные успешно сохранены для строки " + $(rowData).index());
									if (result["id"]) $("[name='id']", rowData).val(result["id"]);
								} else {
									$(f.m).removeClass("info").addClass("error").html("Произошда ошибка при сохранении данных" + (result["error"] ? ' <br><b>"' + result["error"] + '"</b>' : ""));

								}
							}
						});
						break;
				}
			});

			// Init and re-init 
			f.init();
			$(".reload", f).click(function(e){
				e.preventDefault();
				f.init();
			});

		});
	})("form");
});