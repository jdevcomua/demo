$(function () {
    $('.datepicker input').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        language: "uk",
        locale: "uk",
        autoclose: true
    });
    $.datepicker.regional.uk = {
        closeText: "Закрити",
        prevText: "&#x3C;",
        nextText: "&#x3E;",
        currentText: "Сьогодні",
        monthNames: [ "Січень","Лютий","Березень","Квітень","Травень","Червень",
            "Липень","Серпень","Вересень","Жовтень","Листопад","Грудень" ],
        monthNamesShort: [ "Січень","Лютий","Березень","Квітень","Травень","Червень",
            "Липень","Серпень","Вересень","Жовтень","Листопад","Грудень" ],
        dayNames: [ "неділя","понеділок","вівторок","середа","четвер","п’ятниця","субота" ],
        dayNamesShort: [ "нед","пнд","вів","срд","чтв","птн","сбт" ],
        dayNamesMin: [ "Нд","Пн","Вт","Ср","Чт","Пт","Сб" ],
        weekHeader: "Тиж",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "" };
    $.datepicker.setDefaults( $.datepicker.regional.uk );
});