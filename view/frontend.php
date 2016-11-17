<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
	<meta charset="UTF-8"/>
	<title>Форма заказа (test)</title>
	<link rel="stylesheet" href="/css/styles.css">
	<script src="/jquery-1.9.0-min.js"></script>
	<script src="/js/frontlogic.js"></script>
</head>
<body>
<form action="" method="post">
	<div class="table">
		<label class="required"><span>ФИО</span>
			<span><input type="text" name="name" value="<?=$form["name"];?>"></span></label>
		<div><span>Услуги</span>
			<span><? if (is_array($form["services"]))
				foreach ($form["services"] as $service) {
					?><label><input type="checkbox" name="services[]" value="<?=$service["id"]?>"><?=$service["name"]?></label><?
				} ?></span>
		</div>
		<label class="required"><span>Дата рождения</span>
			<span><input type="date" name="birthday" value="<?=$form["birthday"];?>" placeholder="09.10.2015"></span></label>
		<label><span>Телефон</span>
			<span><input type="tel" name="phone" value="<?=$form["phone"];?>" placeholder="+7"></span></label>
		<div><span>Пол</span>
			<span><label><input type="radio" name="gender" value="F">Женский</label> <label><input type="radio" name="gender" value="M">Мужской</label></span>
		</div>
		<div class="required"><span>Обязательные поля</span></div>
		<div><input type="submit" name="Рассчитать"><input type="reset"></div>
	</div>
	<div class="message"></div>
</form>
<div style="text-align: center;"><a href="/admin.php">Управление данными</a></div>
</body>
</html>