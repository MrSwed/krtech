<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
	<meta charset="UTF-8"/>
	<title>Форма заказа (test)</title>
</head>
<body>
<form action="">
	<div class="message"><!--{{message}}--></div>
	<div class="table">
		<label><span>ФИО</span>
			<span><input type="text" name="name" value="<!--{{name}}-->"></span></label>
		<div><span>Услуги</span> <span><!--{{services:<label><input type="checkbox" name="services[]" value="%v">%n</label>}}--></span>
		</div>
		<label><span>Дата рождения</span>
			<span><input type="date" name="birthday" value="<!--{{birthday}}-->" placeholder="09.10.2015"></span></label>
		<label><span>Телефон</span>
			<span><input type="tel" name="phone" value="<!--{{phone}}-->" placeholder="+7"></span></label>
		<div><span>Пол</span>
			<span><label><input type="radio" name="gender" value="F">Женский</label> <label><input type="radio" name="gender" value="M">Женский</label></span>
		</div>
	</div>
</form>
</body>
</html>