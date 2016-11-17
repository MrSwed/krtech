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
				if (!p || (typeof p == "string" && p.toLowerCase() == "clear")) p = {"clear": true};
				p = $.extend({}, {"text": "", "class": "info", "clear": false}, p);
				p.clear && $(f.m).attr("class", 'message').html('');
				p.class && $(f.m).attr("class", 'message ' + p.class);
				p.text && $(f.m).html(p.text);
			};
			f.ajax = function(p){
				p = $.extend({}, {
					type: $(f).attr("method"),
					url: $(f).attr("action"),
					data: false,
					dataType: "json",
					success: false,
					error: function(event, request, settings){
						f.message({"text": "Error sending request " + settings.url + "", "class": "error"});
						console.log(event, request, settings);
					}
				}, p);
				if (p.success) $.ajax(p);
			};
			f.init = function(){
				$("tr:not(.template)", f.tb).remove();
				f.message();
				f.ajax({
					data: {"action": "get", "target": $(f).attr("data-source")},
					success: function(data){
						for (r in data) {
							var nr = $(".template", f).clone(true).appendTo(f.tb).removeClass("template");
							for (k in data[r]) {
								if (k.match(/^date_/)) data[r][k] = data[r][k].split(" ")[0];
								var inp = $("[name='" + k + "']", nr);
								inp.length && inp.val(data[r][k]);
							}
						}
						f.message({"text": "Данные загружены"});
					}
				});
			};

			// handle data
			$("[name]", f).change(function(e){
				var t = $(this);
				t.addClass("changed").closest("tr").find("button.save").attr("disabled", false);
				if (t.is("[data-type]")) {
					switch (t.attr("data-type")) {
						// strict order of services ids
						case "numbers-sort-list": t.val($.unique(t.val().split(/[^\d]+/)).sort(function(a,b){return a*1>b*1?1:-1;}).join(","));break;
					}
				}
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
						var id = $("[name='id']", rowData).val();
						if (confirm("Подтвердите удаление строки id#" + id)) { // todo: no confirm for new cloned or empty
							if (id) {
								f.ajax({
									data: {"action": "del", "target": $(f).attr("data-source"), "data": {"id": id}, "confirm": 1},
									success: function(result){
										if (result["ok"]) {
											f.message({"class": "info", "text": "Успешно удалено. id#" + id});
											rowData.remove();
										} else {
											f.message({"class": "error","text": "Произошда ошибка при удалении данных" + (result["error"] ? ' <br><b>"' + result["error"] + '"</b>' : "")});
										}
									}
								});
							} else rowData.remove();
						}
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
						f.ajax({
							data: {"action": "save", "target": $(f).attr("data-source"), "data": data},
							success: function(result){
								if (result["ok"]) {
									$("[name]", rowData).removeClass("changed");
									rowData.find("button.save").attr("disabled", true);
									if (result["id"]) $("[name='id']", rowData).val(result["id"]);
									else result["id"] = $("[name='id']", rowData).val();
									f.message({"class": "info", "text": "Успешно сохранено. id#" + result["id"]});
								} else {
									f.message({
										"class": "error",
										"text": "Произошда ошибка при сохранении данных" + (result["error"] ? ' <br><b>"' + result["error"] + '"</b>' : "")
									});
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
				f.message({"text": "... обновление ..."});
				f.init();
			});

		});
	})("form");
});