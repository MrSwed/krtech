<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
	<meta charset="UTF-8"/>
	<title>Управление данными (test)</title>
	<link rel="stylesheet" href="/css/styles.css">
	<link rel="stylesheet" href="/css/backend.css">
	<script src="/jquery-1.9.0-min.js"></script>
	<script src="/js/backlogic.js"></script>
</head>
<body>
<!--<pre>
	<? /*
	print_r($form);
	*/ ?>
</pre>
-->

<form action="" method="post" data-source="discounts">
	<h2>Скидки <a href="#" class="reload" title="Обновить">&orarr;</a></h2>
	<div class="message"></div>
	<table border="1" align="center" cellpadding="5px">
		<thead>
		<tr>
			<th rowspan="2">ID</th>
			<th rowspan="2" title="Только число">Процент скидки</th>
			<th rowspan="2" title="Идентификаторы услуг через запятую">Услуги</th>
			<th colspan="2">День рождения</th>
			<th rowspan="2" title="1 - Наличие, другие цифры - соотв. последние цифры телефона">Телефон</th>
			<th rowspan="2">Пол</th>
			<th colspan="2">Период действия</th>
			<th rowspan="2" colspan="3">
				<button title="Добавить" class="add"> + </button>
			</th>
		</tr>
		<tr>
			<th>Период до</th>
			<th>Период после</th>
			<th>Дата начала</th>
			<th>Дата окончания</th>
		</tr>
		</thead>
		<tbody>
		<tr style="text-align: center;" class="template">
			<td><label><input type="text" name="id" readonly="readonly"></label></td>
			<td><label><input type="text" name="percent"></label></td>
			<td><label><input type="text" name="services" data-type="numbers-sort-list"></label></td>
			<td><label><select name="birthday_before">
						<option value=""></option>
						<option value="86400">Сутки</option>
						<option value="604800">Неделя</option>
						<option value="1209600">Две недели</option>
						<option value="2592000">Месяц</option>
					</select></label></td>
			<td><label><select name="birthday_after">
						<option value=""></option>
						<option value="86400">Сутки</option>
						<option value="604800">Неделя</option>
						<option value="1209600">Две недели</option>
						<option value="2592000">Месяц</option>
					</select></label></td>
			<td><label><input type="text" name="phone"></label></td>
			<td><label><select name="gender">
						<option value=""></option>
						<option value="M">Мужской</option>
						<option value="F">Женский</option>
					</select></label></td>
			<td><label><input type="date" name="date_start"></label></td>
			<td><label><input type="date" name="date_end"></label></td>
			<td><button title="Записать" class="save" disabled="disabled">Save</button></td>
			<td><button title="Удалить" class="del"> - </button></td>
			<td><button title="Клонировать" class="clone"> &copy; </button></td>
		</tr>
		</tbody>
	</table>
</form>
<? /*
form[method=post][data-source=services]>(h2{Услуги }>a.reload[href=#][title=Обновить]{&orarr;})+div.message+table[border=1][align=center][cellpadding=5px]>(thead>tr>th{id}+th{Название}+th[colspan=3]>button.add[title=Добавить]{ + })+tbody>tr[style="text-align: center;"][class="template"]>(td>label>input[type=text][name=id][readonly=readonly])+(td>label>input[type=text][name=name])+(td>button.save[title=Удалить][disabled=disabled]{Save})+(td>button.del[title=Удалить]{ - })+(td>button.copy[title=Клонировать]{ &copy; })
  */?>
<form action="" method="post" data-source="services">
	<h2>Услуги <a href="#" class="reload" title="Обновить">&orarr;</a></h2>
	<div class="message"></div>
	<table border="1" align="center" cellpadding="5px">
		<thead>
		<tr>
			<th>id</th>
			<th>Название</th>
			<th colspan="3">
				<button class="add" title="Добавить"> +</button>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr style="text-align: center;" class="template">
			<td><label><input type="text" name="id" readonly="readonly"></label></td>
			<td><label><input type="text" name="name"></label></td>
			<td><button class="save" title="Удалить" disabled="disabled">Save</button></td>
			<td><button class="del" title="Удалить"> - </button></td>
			<td><button class="copy" title="Клонировать"> &copy; </button></td>
		</tr>
		</tbody>
	</table>
</form>
<div style="text-align: center;"><a href="/">Frontend</a></div>

</body>
</html>