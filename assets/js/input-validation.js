/*
Author:Yasir Mehboob
Email: yasir.rind@outlook.com
Date:19-07-2020
* */
/*
Documentation

.allow-string [only allows A-Z a-z and space]
.allow-number [only allows 0-9 numbers]
.allow-mobile-number [only allows 0-9 numbers and not greater then 11 digits]
.allow-address [only allows 0-9 a-z A-Z @ # : ; / . -]

* */
$(document).ready(function() {

	$('.allow-string').keypress(function (key) {
// 		alert(key.keyCode);
		if ((key.charCode < 65 || key.charCode > 90) && (key.charCode < 97 || key.charCode > 122) && key.charCode !==32) return false;
	});
		$('.allow-cast').keypress(function (key) {
// 		alert(key.keyCode);
		if ((key.charCode < 65 || key.charCode > 90) && (key.charCode < 97 || key.charCode > 122) && key.charCode !==32 && key.charCode !==46) return false;
	});

	$('.allow-number').keypress(function (key) {
		if (key.charCode < 48 || key.charCode > 57) return false;
	});
	$('.allow-mobile-number').keypress(function (key) {
		var no = $(this).val();
		if (no.length >10) return false;
		if (key.charCode < 48 || key.charCode > 57) return false;
	});
	$('.allow-address').keypress(function (key) {
		if ((key.charCode < 44 || key.charCode > 59) && (key.charCode < 64 || key.charCode > 90) && (key.charCode < 97 || key.charCode > 122) && key.charCode !== 32 && key.charCode !== 35) return false;
	});

	$("input[type=text],textarea").blur(function () {
		let value = $.trim($(this).val());
			value = value.toUpperCase();
			$(this).val(value);
	});

	$('.allow_grade_cgpa').keypress(function (key) {
		if ((key.charCode < 48 || key.charCode > 57) && key.charCode > 46) return false;
	});

	$('.allow-string-number-special').keypress(function (key) {
		// alert("working");
		if ((key.charCode < 65 || key.charCode > 90) && (key.charCode < 97 || key.charCode > 122) && (key.charCode < 44 || key.charCode > 59) && key.charCode !==32 && key.charCode !==44 && key.charCode !==45 && key.charCode !==46 && key.charCode !==40 && key.charCode !==41 && key.charCode !==47  ) return false;
	});
	
});

